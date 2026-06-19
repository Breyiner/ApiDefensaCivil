<?php

namespace Database\Seeders\HousingQuality;

use Illuminate\Database\Seeder;
use App\Models\HousingQuality\HousingQuality;

class HousingQualitySeeder extends Seeder
{
    public function run(): void
    {
        $qualities = [
            ['name' => 'Propio'],
            ['name' => 'Arrendado'],
            ['name' => 'Familiar'],
        ];

        foreach ($qualities as $data) {
            $quality = HousingQuality::create($data);

            // Crear auditoría simulando que lo hizo el sistema
            $quality->audits()->create([
                'user_name'      => "Sistema",
                'rol_name'       => "Sistema",
                'date_time'      => now(),
                'action_execute' => 'Creado',
                'status_old'     => null,
                'status_new'     => "Activo",
                'data_old'       => null,
                'data_new'       => $quality->name,
            ]);
        }
    }
}
