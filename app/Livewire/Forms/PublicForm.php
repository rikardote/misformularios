<?php

namespace App\Livewire\Forms;

use App\Models\Form;
use Livewire\Component;

class PublicForm extends Component
{
    public ?Form $form = null;

    public array $answers = [];

    public string $successMessage = '';

    public bool $formAvailable = true;

    /** Honeypot field — bots fill this, humans don't */
    public string $website = '';

    public function mount(string $uuid): void
    {
        $this->form = Form::where('uuid', $uuid)
            ->with('questions.options')
            ->first();

        if (! $this->form || ! $this->form->is_public) {
            $this->formAvailable = false;
            return;
        }

        $this->initAnswers();
    }

    public function toggleCheckbox(int $questionId, int $optionId): void
    {
        $key = "question_{$questionId}";
        if (! isset($this->answers[$key])) {
            $this->answers[$key] = [];
        }
        $idx = array_search($optionId, $this->answers[$key]);
        if ($idx !== false) {
            unset($this->answers[$key][$idx]);
            $this->answers[$key] = array_values($this->answers[$key]);
        } else {
            $this->answers[$key][] = $optionId;
        }
    }

    public function submit(): void
    {
        if (! $this->formAvailable || ! $this->form) {
            return;
        }

        // Honeypot — if filled, silently reject (looks like success to bot)
        if (! empty($this->website)) {
            $this->successMessage = 'Formulario enviado correctamente.';
            return;
        }

        $rules = [];
        foreach ($this->form->questions as $question) {
            $key = "answers.question_{$question->id}";

            if ($question->is_required) {
                $rules[$key] = match ($question->type) {
                    'checkbox' => ['required', 'array', 'min:1'],
                    'radio', 'select' => ['required', 'integer', 'exists:options,id'],
                    default => ['required', 'string', 'max:5000'],
                };
            } else {
                $rules[$key] = match ($question->type) {
                    'checkbox' => ['nullable', 'array'],
                    'radio', 'select' => ['nullable', 'integer', 'exists:options,id'],
                    default => ['nullable', 'string', 'max:5000'],
                };
            }
        }

        $this->validate($rules);

        $response = $this->form->responses()->create();

        foreach ($this->form->questions as $question) {
            $value = $this->answers["question_{$question->id}"] ?? null;

            if (in_array($question->type, ['radio', 'select'])) {
                $response->answers()->create([
                    'question_id' => $question->id,
                    'option_id' => $value ? (int) $value : null,
                ]);
            } elseif ($question->type === 'checkbox') {
                if (is_array($value)) {
                    foreach ($value as $optionId) {
                        $response->answers()->create([
                            'question_id' => $question->id,
                            'option_id' => (int) $optionId,
                        ]);
                    }
                }
            } else {
                $response->answers()->create([
                    'question_id' => $question->id,
                    'answer_text' => $value,
                ]);
            }
        }

        $this->successMessage = 'Formulario enviado correctamente.';
        $this->reset('answers');
        $this->initAnswers();
    }

    private function initAnswers(): void
    {
        if (! $this->form) {
            return;
        }

        foreach ($this->form->questions as $question) {
            $this->answers["question_{$question->id}"] = $question->type === 'checkbox' ? [] : '';
        }
    }

    public function render()
    {
        return view('livewire.forms.public-form')
            ->layout('layouts.form');
    }
}
