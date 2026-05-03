<?php

namespace App\Livewire\Forms;

use App\Models\Form;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Builder extends Component
{
    public Form $form;

    public string $title = '';

    public string $description = '';

    public bool $isPublic = true;

    public array $questions = [];

    public string $newOptionText = '';

    public function mount(Form $form): void
    {
        Gate::authorize('update', $form);

        $this->form = $form;
        $this->title = $form->title;
        $this->description = $form->description ?? '';
        $this->isPublic = $form->is_public;

        $this->questions = $form->questions()->with('options')->get()
            ->map(fn ($q) => [
                'id' => $q->id,
                'type' => $q->type,
                'question_text' => $q->question_text,
                'is_required' => $q->is_required,
                'order' => $q->order,
                'options' => $q->options->map(fn ($o) => [
                    'id' => $o->id,
                    'option_text' => $o->option_text,
                ])->toArray(),
            ])->toArray();
    }

    public function addQuestion(): void
    {
        $this->questions[] = [
            'id' => null,
            'type' => 'text',
            'question_text' => '',
            'is_required' => false,
            'order' => count($this->questions),
            'options' => [],
        ];
    }

    public function removeQuestion(int $index): void
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
    }

    public function addOption(int $questionIndex): void
    {
        $this->questions[$questionIndex]['options'][] = [
            'id' => null,
            'option_text' => '',
        ];
    }

    public function removeOption(int $questionIndex, int $optionIndex): void
    {
        unset($this->questions[$questionIndex]['options'][$optionIndex]);
        $this->questions[$questionIndex]['options'] = array_values($this->questions[$questionIndex]['options']);
    }

    public function save(): void
    {
        $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.question_text' => ['required', 'string', 'max:500'],
            'questions.*.type' => ['required', 'in:text,radio,checkbox,select'],
            'questions.*.is_required' => ['boolean'],
        ], [
            'title.required' => 'El título es obligatorio.',
            'questions.required' => 'Agrega al menos una pregunta.',
            'questions.min' => 'Agrega al menos una pregunta.',
            'questions.*.question_text.required' => 'El texto de la pregunta es obligatorio.',
        ]);

        // Validate that radio/checkbox/select questions have at least one option
        foreach ($this->questions as $index => $q) {
            if (in_array($q['type'], ['radio', 'checkbox', 'select'])) {
                $nonEmptyOptions = collect($q['options'])->filter(fn ($o) => ! empty(trim($o['option_text'] ?? '')));
                if ($nonEmptyOptions->isEmpty()) {
                    $this->addError("questions.{$index}.options", 'Agrega al menos una opción para esta pregunta.');
                    return;
                }
            }
        }

        $this->form->update([
            'title' => $this->title,
            'description' => $this->description ?: null,
            'is_public' => $this->isPublic,
        ]);

        $existingIds = collect($this->questions)->pluck('id')->filter()->toArray();

        $this->form->questions()->whereNotIn('id', $existingIds)->delete();

        foreach ($this->questions as $index => $q) {
            $question = $this->form->questions()->updateOrCreate(
                ['id' => $q['id']],
                [
                    'type' => $q['type'],
                    'question_text' => $q['question_text'],
                    'is_required' => $q['is_required'],
                    'order' => $index,
                ]
            );

            if (in_array($q['type'], ['radio', 'checkbox', 'select'])) {
                $existingOptionIds = collect($q['options'])->pluck('id')->filter()->toArray();
                $question->options()->whereNotIn('id', $existingOptionIds)->delete();

                foreach ($q['options'] as $opt) {
                    if (! empty(trim($opt['option_text'] ?? ''))) {
                        $question->options()->updateOrCreate(
                            ['id' => $opt['id']],
                            ['option_text' => $opt['option_text']]
                        );
                    }
                }
            } else {
                $question->options()->delete();
            }
        }

        session()->flash('status', 'Formulario guardado correctamente.');
    }

    public function render()
    {
        return view('livewire.forms.builder')
            ->layout('layouts.app');
    }
}
