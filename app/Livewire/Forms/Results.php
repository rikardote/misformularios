<?php

namespace App\Livewire\Forms;

use App\Models\Form;
use App\Models\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class Results extends Component
{
    use WithPagination;

    public Form $form;

    public string $activeTab = 'summary'; // 'summary', 'analysis' or 'individual'

    public function mount(Form $form): void
    {
        Gate::authorize('view', $form);

        $this->form = $form->load([
            'questions.options', 
            'questions.answers.option'
        ]);
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage(); // Reset pagination when switching tabs
    }

    public function render()
    {
        $paginatedResponses = [];
        
        if ($this->activeTab === 'individual') {
            $paginatedResponses = Response::where('form_id', $this->form->id)
                ->with('answers.option')
                ->latest()
                ->paginate(10);
        }

        return view('livewire.forms.results', [
            'paginatedResponses' => $paginatedResponses
        ])->layout('layouts.app');
    }
}
