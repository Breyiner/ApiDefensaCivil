<?php

namespace Database\Seeders\VulnerableQuestion;

use Illuminate\Database\Seeder;
use App\Models\VulnerableQuestion\VulnerableQuestion;

class VulnerableQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            // 🔵 Preguntas normales
            ['description' => '¿Tiene su vivienda elementos estructurales (tejas, lámparas, cuadros, paredes, marcos, etc.) que puedan desprenderse y caer en caso de temblor, vendavales o deterioro?', 'question_caution' => false],
            ['description' => '¿Presenta su vivienda daños estructurales visibles, como grietas en muros o vigas, humedad excesiva o paredes inclinadas o deterioradas?', 'question_caution' => false],
            ['description' => '¿Los pisos de su vivienda presentan signos de deterioro, como hundimientos, grietas, fisuras o desniveles peligrosos?', 'question_caution' => false],
            ['description' => '¿El techo de su vivienda presenta filtraciones, inestabilidad, deformaciones, está suelto o su estructura se ve debilitada?', 'question_caution' => false],
            ['description' => '¿Las canales y bajantes del techo de su vivienda tienen soportes sueltos, se encuentran bloqueadas, presentan filtraciones o acumulación de agua?', 'question_caution' => false],
            ['description' => '¿Existen conexiones eléctricas en mal estado, cables expuestos o instalaciones eléctricas improvisadas que puedan generar cortocircuitos?', 'question_caution' => false],
            ['description' => '¿Existen muebles, estanterías, repisas o elementos mal fijados que puedan caer y causar accidentes?', 'question_caution' => false],
            ['description' => '¿Hay ventanas, puertas, marquesinas u otras estructuras con vidrio que puedan desprenderse y caer en caso de deterioro, mal aseguramiento, sismo, vendaval o vibración excesiva?', 'question_caution' => false],
            ['description' => '¿Hay en su vivienda escaleras sin barandas o sin estabilidad o sin soportes, que puedan presentar riesgo de caída?', 'question_caution' => false],
            ['description' => '¿Están realizando actualmente modificaciones estructurales, locativas o decorativas en su vivienda?', 'question_caution' => false],
            ['description' => '¿Se desarrolla alguna obra o construcción en inmediaciones de su casa, que puedan afectar de alguna manera su vivienda?', 'question_caution' => false],
            ['description' => '¿El tanque de agua u otros elementos pesados carecen de amarres o anclajes a las placas o vigas, para evitar que se caigan o dañen la estructura en caso de sismo, vendaval o vibración excesiva?', 'question_caution' => false],

            // 🟡 Preguntas de precaución
            ['description' => '¿Su vivienda cuenta con un botiquín, extintor, maletín familiar o elementos para atender una emergencia?', 'question_caution' => true],
            ['description' => '¿Los integrantes de la familia saben dónde están ubicados los registros de agua, gas y los interruptores de luz y saben cómo cerrarlos?', 'question_caution' => true],
            ['description' => '¿Los integrantes de la familia conocen los números de emergencia y saben a quién llamar en caso de un accidente, emergencia o situación de desastre (Bomberos, Policía, Defensa Civil, Cruz Roja, etc.)?', 'question_caution' => true],
        ];

        foreach ($questions as $data) {
            $question = VulnerableQuestion::create($data);

            // Crear auditoría simulando que lo hizo el sistema
            $question->audits()->create([
                'user_name'      => "Sistema",
                'rol_name'       => "Sistema",
                'date_time'      => now(),
                'action_execute' => 'Creado',
                'status_old'     => null,
                'status_new'     => "Activo",
                'data_old'       => null,
                'data_new'       => $question->description,
                'subData_old'    => null,
                'subData_new'    => $question->question_caution ? 'Sí' : 'No',
            ]);
        }
    }
}
