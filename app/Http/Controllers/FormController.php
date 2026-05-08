<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function exportXls(Form $form)
    {
        Gate::authorize('view', $form);

        $questions = $form->questions;
        $responses = $form->responses()->with('answers.option')->get();

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="resultados_' . $form->uuid . '.xls"',
        ];

        $callback = function () use ($questions, $responses, $form) {
            echo "<?xml version=\"1.0\"?>\n";
            echo "<?mso-application progid=\"Excel.Sheet\"?>\n";
            echo "<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\"\n";
            echo " xmlns:o=\"urn:schemas-microsoft-com:office:office\"\n";
            echo " xmlns:x=\"urn:schemas-microsoft-com:office:excel\"\n";
            echo " xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\"\n";
            echo " xmlns:html=\"http://www.w3.org/TR/REC-html40\">\n";
            
            echo " <Styles>\n";
            echo "  <Style ss:ID=\"Default\" ss:Name=\"Normal\">\n";
            echo "   <Alignment ss:Vertical=\"Bottom\"/>\n";
            echo "   <Borders/>\n";
            echo "   <Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#000000\"/>\n";
            echo "   <Interior/>\n";
            echo "   <NumberFormat/>\n";
            echo "   <Protection/>\n";
            echo "  </Style>\n";
            echo "  <Style ss:ID=\"sHeader\">\n";
            echo "   <Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\" ss:WrapText=\"1\"/>\n";
            echo "   <Borders>\n";
            echo "    <Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
            echo "    <Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
            echo "    <Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
            echo "    <Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
            echo "   </Borders>\n";
            echo "   <Font ss:FontName=\"Calibri\" x:Family=\"Swiss\" ss:Size=\"11\" ss:Color=\"#FFFFFF\" ss:Bold=\"1\"/>\n";
            echo "   <Interior ss:Color=\"#4F46E5\" ss:Pattern=\"Solid\"/>\n";
            echo "  </Style>\n";
            echo "  <Style ss:ID=\"sData\">\n";
            echo "   <Alignment ss:Vertical=\"Center\"/>\n";
            echo "   <Borders>\n";
            echo "    <Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
            echo "    <Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
            echo "    <Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
            echo "    <Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
            echo "   </Borders>\n";
            echo "  </Style>\n";
            echo " </Styles>\n";

            echo " <Worksheet ss:Name=\"Resultados\">\n";
            echo "  <Table ss:ExpandedColumnCount=\"" . ($questions->count() + 3) . "\" ss:ExpandedRowCount=\"" . ($responses->count() + 1) . "\" x:FullColumns=\"1\" x:FullRows=\"1\" ss:DefaultColumnWidth=\"100\">\n";
            
            // Column widths
            echo "   <Column ss:AutoFitWidth=\"0\" ss:Width=\"80\"/>\n"; // ID
            echo "   <Column ss:AutoFitWidth=\"0\" ss:Width=\"120\"/>\n"; // Verification Code
            echo "   <Column ss:AutoFitWidth=\"0\" ss:Width=\"120\"/>\n"; // Date
            foreach ($questions as $q) {
                echo "   <Column ss:AutoFitWidth=\"0\" ss:Width=\"200\"/>\n";
            }

            // Header
            echo "   <Row ss:AutoFitHeight=\"0\" ss:Height=\"35\">\n";
            echo "    <Cell ss:StyleID=\"sHeader\"><Data ss:Type=\"String\">ID RESPUESTA</Data></Cell>\n";
            echo "    <Cell ss:StyleID=\"sHeader\"><Data ss:Type=\"String\">CODIGO VERIFICACION</Data></Cell>\n";
            echo "    <Cell ss:StyleID=\"sHeader\"><Data ss:Type=\"String\">FECHA ENVIO</Data></Cell>\n";
            foreach ($questions as $question) {
                echo "    <Cell ss:StyleID=\"sHeader\"><Data ss:Type=\"String\">" . htmlspecialchars(mb_strtoupper($question->question_text)) . "</Data></Cell>\n";
            }
            echo "   </Row>\n";

            // Data
            foreach ($responses as $response) {
                echo "   <Row ss:AutoFitHeight=\"1\">\n";
                echo "    <Cell ss:StyleID=\"sData\"><Data ss:Type=\"Number\">" . $response->id . "</Data></Cell>\n";
                echo "    <Cell ss:StyleID=\"sData\"><Data ss:Type=\"String\">" . $response->verification_code . "</Data></Cell>\n";
                echo "    <Cell ss:StyleID=\"sData\"><Data ss:Type=\"String\">" . $response->created_at->format('d/m/Y H:i:s') . "</Data></Cell>\n";
                foreach ($questions as $question) {
                    $answers = $response->answers->where('question_id', $question->id);
                    $text = $answers->map(fn($a) => $a->option ? $a->option->option_text : $a->answer_text)->implode(', ');
                    echo "    <Cell ss:StyleID=\"sData\"><Data ss:Type=\"String\">" . htmlspecialchars(mb_strtoupper($text)) . "</Data></Cell>\n";
                }
                echo "   </Row>\n";
            }

            echo "  </Table>\n";
            echo " </Worksheet>\n";
            echo "</Workbook>\n";
        };

        return response()->streamDownload($callback, 'resultados_' . $form->uuid . '.xls', $headers);
    }

}
