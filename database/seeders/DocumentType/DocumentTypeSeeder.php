<?php

namespace Database\Seeders\DocumentType;

use Illuminate\Database\Seeder;
use App\Models\DocumentType\DocumentType;

class DocumentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $documentTypes = [
            ['name' => 'Registro Civil', 'acronym' => 'RC'],
            ['name' => 'Tarjeta de Identidad', 'acronym' => 'TI'],
            ['name' => 'Cédula de Ciudadanía', 'acronym' => 'CC'],
            ['name' => 'Cédula de Extranjería', 'acronym' => 'CE'],
            ['name' => 'Pasaporte', 'acronym' => 'PP'],
            ['name' => 'Permiso Especial de Permanencia', 'acronym' => 'PEP'],
        ];

        foreach ($documentTypes as $data) {
            $docType = DocumentType::create($data);

            // Crear auditoría simulando que lo hizo el sistema
            $docType->audits()->create([
                'user_name'      => "Sistema",
                'rol_name'       => "Sistema",
                'date_time'      => now(),
                'action_execute' => 'Creado',
                'status_old'     => null,
                'status_new'     => "Activo",
                'data_old'       => null,
                'data_new'       => $docType->name,
            ]);
        }
    }
}
