<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Resultados - {{ $form->title }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #6366f1;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #1e1b4b;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 14px;
        }
        .summary {
            margin-bottom: 30px;
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
        }
        .summary-item {
            margin-bottom: 5px;
            font-size: 14px;
        }
        .summary-label {
            font-weight: bold;
            color: #4b5563;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 11px;
            table-layout: fixed; /* Better for large tables */
        }
        thead {
            display: table-header-group; /* Repeat headers on each page */
        }
        th {
            background-color: #6366f1;
            color: white;
            text-align: left;
            padding: 8px;
            border: 1px solid #e5e7eb;
            word-wrap: break-word;
        }
        td {
            padding: 6px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
            word-wrap: break-word; /* Prevent text overflow */
        }
        tr {
            page-break-inside: avoid; /* Don't split rows across pages */
        }
        tr:nth-child(even) {
            background-color: #f3f4f6;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            padding: 10px 0;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Resultados</h1>
        <p>{{ $form->title }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <span class="summary-label">Fecha del Reporte:</span> {{ now()->format('d/m/Y H:i') }}
        </div>
        <div class="summary-item">
            <span class="summary-label">Total de Preguntas:</span> {{ $form->questions_count }}
        </div>
        <div class="summary-item">
            <span class="summary-label">Total de Respuestas:</span> {{ $form->responses_count }}
        </div>
        @if($form->description)
        <div class="summary-item">
            <span class="summary-label">Descripción:</span> {{ $form->description }}
        </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">Fecha</th>
                @foreach($form->questions as $question)
                    <th>{{ $question->question_text }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($form->responses as $response)
                <tr>
                    <td>{{ $response->created_at->format('d/m/Y H:i') }}</td>
                    @foreach($form->questions as $question)
                        <td>
                            @php
                                $answers = $response->answers->where('question_id', $question->id);
                                $text = $answers->map(fn($a) => $a->option ? $a->option->option_text : $a->answer_text)->implode(', ');
                            @endphp
                            {{ $text ?: '-' }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generado automáticamente por el Sistema de Formularios - ISSSTE Baja California
    </div>
</body>
</html>
