<?php

namespace Database\Seeders\Sector;

use Illuminate\Database\Seeder;
use App\Models\Sector\Sector;

class SectorSeeder extends Seeder
{
    public function run(): void
    {
        $sectors = [
            ['name' => 'Barrio'],
            ['name' => 'Comuna'],
            ['name' => 'Localidad'],
        ];

        foreach ($sectors as $data) {
            $sector = Sector::create($data);

            // Crear auditoría simulando que lo hizo el sistema
            $sector->audits()->create([
                'user_name'      => "Sistema",
                'rol_name'       => "Sistema",
                'date_time'      => now(),
                'action_execute' => 'Creado',
                'status_old'     => null,
                'status_new'     => "Activo",
                'data_old'       => null,
                'data_new'       => $sector->name,
            ]);
        }
    }
}
