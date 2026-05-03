<?php

namespace App\Livewire\Forms;

use App\Models\Form;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Livewire\Component;

class Results extends Component
{
    public Form $form;

    public string $activeTab = 'summary'; // 'summary' or 'individual'

    public function mount(Form $form): void
    {
        Gate::authorize('view', $form);

        $this->form = $form->load(['questions.options', 'responses.answers.option']);
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.forms.results')
            ->layout('layouts.app');
    }
}
