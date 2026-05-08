<?php

namespace Database\Seeders;

use App\Models\Form;
use App\Models\Question;
use App\Models\Option;
use App\Models\User;
use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        $formsData = [
            [
                'title' => 'Encuesta de Satisfacción del Cliente',
                'description' => 'Ayúdanos a mejorar nuestro servicio respondiendo estas breves preguntas.',
                'questions' => [
                    ['text' => '¿Cómo calificaría su experiencia general?', 'type' => 'radio', 'options' => ['Excelente', 'Bueno', 'Regular', 'Malo']],
                    ['text' => '¿Qué es lo que más le gustó de nuestro servicio?', 'type' => 'textarea'],
                    ['text' => '¿Nos recomendaría a un amigo?', 'type' => 'radio', 'options' => ['Sí', 'No', 'Tal vez']],
                ]
            ],
            [
                'title' => 'Registro para Evento Tecnológico 2026',
                'description' => 'Regístrate para asegurar tu lugar en la conferencia de tecnología más grande del año.',
                'questions' => [
                    ['text' => 'Nombre Completo', 'type' => 'input'],
                    ['text' => 'Correo Electrónico', 'type' => 'input'],
                    ['text' => '¿En qué área de tecnología está más interesado?', 'type' => 'checkbox', 'options' => ['IA', 'Cloud Computing', 'Ciberseguridad', 'Desarrollo Web']],
                ]
            ],
            [
                'title' => 'Evaluación de Desempeño Laboral',
                'description' => 'Formulario interno para la evaluación semestral de colaboradores.',
                'questions' => [
                    ['text' => '¿Cumplió con sus objetivos este semestre?', 'type' => 'radio', 'options' => ['Totalmente', 'Parcialmente', 'No cumplió']],
                    ['text' => 'Mencione tres fortalezas suyas', 'type' => 'text'],
                    ['text' => 'Áreas de mejora identificadas', 'type' => 'text'],
                ]
            ],
            [
                'title' => 'Encuesta Detallada de Satisfacción Laboral',
                'description' => 'Evaluación integral del clima y satisfacción en el entorno de trabajo.',
                'questions' => [
                    ['text' => 'Nombre', 'type' => 'input'],
                    ['text' => 'Edad', 'type' => 'input'],
                    ['text' => '¿Cuánto tiempo lleva trabajando en la empresa?', 'type' => 'radio', 'options' => ['Menos de 1 año', '1 a 3 años', '3 a 5 años', 'Más de 5 años']],
                    ['text' => '¿Cómo calificaría su equilibrio entre vida personal y laboral?', 'type' => 'radio', 'options' => ['1 (Muy malo)', '2', '3', '4', '5 (Excelente)']],
                    ['text' => '¿Se siente valorado por su supervisor directo?', 'type' => 'radio', 'options' => ['Sí, siempre', 'A veces', 'No, nunca']],
                    ['text' => '¿Cuenta con las herramientas y recursos necesarios para realizar su trabajo?', 'type' => 'radio', 'options' => ['Sí, totalmente', 'Parcialmente', 'No, me faltan recursos']],
                    ['text' => '¿Cómo calificaría el ambiente laboral en su equipo?', 'type' => 'radio', 'options' => ['Excelente', 'Bueno', 'Regular', 'Tenso/Malo']],
                    ['text' => '¿Siente que tiene oportunidades claras de crecimiento y promoción?', 'type' => 'radio', 'options' => ['Sí', 'No', 'No estoy seguro']],
                    ['text' => '¿Qué tan satisfecho está con su compensación (salario y beneficios)?', 'type' => 'radio', 'options' => ['Muy satisfecho', 'Satisfecho', 'Neutral', 'Insatisfecho']],
                    ['text' => '¿Recomendaría esta empresa a sus amigos como un excelente lugar para trabajar?', 'type' => 'radio', 'options' => ['Definitivamente', 'Probablemente', 'No lo creo']],
                    ['text' => '¿Cuál es su principal motivación para venir a trabajar cada día?', 'type' => 'input'],
                    ['text' => '¿Siente que sus opiniones y sugerencias son tomadas en cuenta?', 'type' => 'radio', 'options' => ['Siempre', 'Frecuentemente', 'Rara vez', 'Nunca']],
                    ['text' => '¿Qué aspecto específico de la cultura organizacional mejoraría hoy mismo?', 'type' => 'text'],
                    ['text' => '¿Cómo calificaría la transparencia y comunicación de la alta dirección?', 'type' => 'radio', 'options' => ['1 (Nula)', '2', '3', '4', '5 (Transparente)']],
                    ['text' => '¿Tiene algún otro comentario o sugerencia para mejorar su experiencia laboral?', 'type' => 'text'],
                ]
            ],
        ];

        foreach ($formsData as $index => $data) {
            try {
                $form = Form::create([
                    'user_id' => $user->id,
                    'uuid' => (string) \Illuminate\Support\Str::uuid(),
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'is_public' => true,
                ]);
            } catch (\Exception $e) {
                $this->command->error('Error creating form: ' . $e->getMessage());
                throw $e;
            }

            foreach ($data['questions'] as $qIndex => $qData) {
                // Map logical names to DB values
                $type = $qData['type'];
                if ($type === 'textarea') $type = 'text'; // 'text' in DB means long text/textarea

                $question = $form->questions()->create([
                    'question_text' => $qData['text'],
                    'type' => $type,
                    'is_required' => rand(0, 1),
                    'order' => $qIndex,
                ]);

                if (isset($qData['options'])) {
                    foreach ($qData['options'] as $oText) {
                        $question->options()->create([
                            'option_text' => $oText,
                        ]);
                    }
                }
            }
        }
    }
}
