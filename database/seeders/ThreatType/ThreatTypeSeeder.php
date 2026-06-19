<?php

namespace Database\Seeders\ThreatType;

use Illuminate\Database\Seeder;
use App\Models\ThreatType\ThreatType;

class ThreatTypeSeeder extends Seeder
{
    public function run(): void
    {
        $threats = [
            'Inundación',
            'Deslizamiento',
            'Creciente Súbita',
            'Caída Colapso Estructural',
            'Contaminación Plagas',
            'Caída de árboles rocas',
            'Colapso Estructural Traumas Quemaduras',
            'Riesgo de Accidentes',
            'Otro',
        ];

        foreach ($threats as $name) {
            $threat = ThreatType::create(['name' => $name]);

            // Crear auditoría simulando que lo hizo el sistema
            $threat->audits()->create([
                'user_name'      => "Sistema",
                'rol_name'       => "Sistema",
                'date_time'      => now(),
                'action_execute' => 'Creado',
                'status_old'     => null,
                'status_new'     => "Activo",
                'data_old'       => null,
                'data_new'       => $threat->name,
            ]);
        }
    }
}
