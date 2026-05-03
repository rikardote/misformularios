<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FormController extends Controller
{
    public function index()
    {
        $query = auth()->user()->isAdmin() 
            ? Form::query() 
            : auth()->user()->forms();

        $forms = $query->with(['user'])
            ->withCount(['questions', 'responses'])
            ->latest()
            ->paginate(10);

        return view('forms.index', compact('forms'));
    }

    public function create()
    {
        return view('forms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $form = auth()->user()->forms()->create($validated);

        return redirect()->route('forms.builder', $form);
    }

    public function show(Form $form)
    {
        Gate::authorize('view', $form);

        $form->load(['questions.options', 'responses', 'user']);
        $form->loadCount(['questions', 'responses']);

        return view('forms.show', compact('form'));
    }

    public function edit(Form $form)
    {
        Gate::authorize('update', $form);

        return view('forms.edit', compact('form'));
    }

    public function update(Request $request, Form $form)
    {
        Gate::authorize('update', $form);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_public' => ['boolean'],
        ]);

        $form->update($validated);

        return redirect()->route('forms.index')->with('status', 'Formulario actualizado.');
    }

    public function destroy(Form $form)
    {
        Gate::authorize('delete', $form);

        $form->delete();

        return redirect()->route('forms.index')->with('status', 'Formulario eliminado.');
    }

    public function export(Form $form)
    {
        Gate::authorize('view', $form);

        $questions = $form->questions;
        $responses = $form->responses()->with('answers.option')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="resultados_' . $form->uuid . '.csv"',
        ];

        $callback = function () use ($questions, $responses) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM for Excel

            $headerRow = ['ID Respuesta', 'Fecha'];
            foreach ($questions as $question) {
                $headerRow[] = $question->question_text;
            }
            fputcsv($file, $headerRow);

            foreach ($responses as $response) {
                $row = [$response->id, $response->created_at->format('Y-m-d H:i:s')];
                foreach ($questions as $question) {
                    $answers = $response->answers->where('question_id', $question->id);
                    $row[] = $answers->map(fn($a) => $a->option ? $a->option->option_text : $a->answer_text)->implode(', ');
                }
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->streamDownload($callback, 'resultados_' . $form->uuid . '.csv', $headers);
    }
}
