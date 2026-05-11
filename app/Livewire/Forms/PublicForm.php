<?php

namespace App\Livewire\Forms;

use App\Models\Form;
use Livewire\Component;

class PublicForm extends Component
{
    public ?Form $form = null;

    public array $answers = [];

    public string $successMessage = '';

    public string $verificationCode = '';

    public bool $formAvailable = true;

    /** Honeypot field — bots fill this, humans don't */
    public string $website = '';

    public function mount($slug): void
    {
        $this->form = Form::where('slug', $slug)
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

        $this->validateOnly("answers.{$key}", $this->getRules());
    }

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName, $this->getRules());
    }

    public function submit(): void
    {
        if (! $this->formAvailable || ! $this->form) {
            return;
        }

        // Rate limiting: 5 submissions per minute per IP
        $throttleKey = 'submit-form:' . request()->ip();
        if (cache()->has($throttleKey) && cache()->get($throttleKey) >= 5) {
            $this->addError('submit', 'Has enviado demasiadas respuestas. Por favor, espera un minuto.');
            return;
        }

        // Honeypot — if filled, silently reject (looks like success to bot)
        if (! empty($this->website)) {
            $this->successMessage = 'Formulario enviado correctamente.';
            return;
        }

        $this->validate($this->getRules());

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
                // Sanitize input to remove HTML tags
                $sanitizedValue = is_string($value) ? strip_tags($value) : $value;
                
                if ($question->type === 'email') {
                    $sanitizedValue = mb_strtolower($sanitizedValue);
                } else {
                    $sanitizedValue = is_string($sanitizedValue) ? mb_strtoupper($sanitizedValue) : $sanitizedValue;
                }

                $response->answers()->create([
                    'question_id' => $question->id,
                    'answer_text' => $sanitizedValue,
                ]);
            }
        }

        // Increment throttle
        $count = cache()->get($throttleKey, 0);
        cache()->put($throttleKey, $count + 1, now()->addMinute());

        $this->successMessage = 'Formulario enviado correctamente.';
        $this->verificationCode = $response->verification_code;
        $this->reset('answers');
        $this->initAnswers();
    }

    public function getRules(): array
    {
        if (! $this->form) {
            return [];
        }

        $rules = [];
        foreach ($this->form->questions as $question) {
            $key = "answers.question_{$question->id}";

            if ($question->is_required) {
                $rules[$key] = match ($question->type) {
                    'checkbox' => ['required', 'array', 'min:1'],
                    'radio', 'select' => ['required', 'integer', 'exists:options,id'],
                    'input' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'email', 'max:255'],
                    default => ['required', 'string', 'max:2000'],
                };
            } else {
                $rules[$key] = match ($question->type) {
                    'checkbox' => ['nullable', 'array'],
                    'radio', 'select' => ['nullable', 'integer', 'exists:options,id'],
                    'input' => ['nullable', 'string', 'max:255'],
                    'email' => ['nullable', 'email', 'max:255'],
                    default => ['nullable', 'string', 'max:2000'],
                };
            }
        }
        return $rules;
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
