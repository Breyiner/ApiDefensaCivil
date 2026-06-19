<?php

namespace Database\Seeders\Resource;

use Illuminate\Database\Seeder;
use App\Models\Resource\Resource;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        $resources = [
            ['name' => 'Puesto de Salud - Clinica u Hospital', 'service' => 'Atención médica y servicios de salud'],
            ['name' => 'Estación de Bomberos', 'service' => 'Atención de incendios y emergencias'],
            ['name' => 'Estacion de Policia - CAI', 'service' => 'Seguridad ciudadana y atención policial'],
            ['name' => 'Defensa Civil', 'service' => 'Gestión de riesgo y atención de desastres'],
            ['name' => 'Cruz Roja', 'service' => 'Atención humanitaria y primeros auxilios'],
            ['name' => 'Ejercito Nacional', 'service' => 'Seguridad y apoyo en emergencias nacionales'],
            ['name' => 'Enfermeria', 'service' => 'Atención básica en salud y primeros auxilios'],
            ['name' => 'Extintores', 'service' => 'Control y prevención de incendios'],
        ];

        foreach ($resources as $data) {
            $resource = Resource::create($data);

            // Crear auditoría simulando que lo hizo el sistema
            $resource->audits()->create([
                'user_name'      => "Sistema",
                'rol_name'       => "Sistema",
                'date_time'      => now(),
                'action_execute' => 'Creado',
                'status_old'     => null,
                'status_new'     => "Activo",
                'data_old'       => null,
                'data_new'       => $resource->name,
            ]);
        }
    }
}
