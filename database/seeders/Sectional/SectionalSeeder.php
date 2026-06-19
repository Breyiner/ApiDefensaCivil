<?php

namespace Database\Seeders\Sectional;

use Illuminate\Database\Seeder;
use App\Models\Sectional\Sectional;

class SectionalSeeder extends Seeder
{
    public function run(): void
    {
        $sectionals = [
            // 26 Direcciones Seccionales oficiales
            ['name' => 'Antioquia'],
            ['name' => 'Atlántico'],
            ['name' => 'Bogotá D.C.'],
            ['name' => 'Bolívar'],
            ['name' => 'Boyacá'],
            ['name' => 'Caldas'],
            ['name' => 'Caquetá'],
            ['name' => 'Casanare'],
            ['name' => 'Cauca'],
            ['name' => 'Cesar'],
            ['name' => 'Chocó'],
            ['name' => 'Córdoba'],
            ['name' => 'Cundinamarca'],
            ['name' => 'La Guajira'],
            ['name' => 'Huila'],
            ['name' => 'Magdalena'],
            ['name' => 'Meta'],
            ['name' => 'Nariño'],
            ['name' => 'Norte de Santander'],
            ['name' => 'Quindío'],
            ['name' => 'Risaralda'],
            ['name' => 'Santander'],
            ['name' => 'Sucre'],
            ['name' => 'Tolima'],
            ['name' => 'Valle del Cauca'],

            // 4 Oficinas regionales
            ['name' => 'Amazonas'],
            ['name' => 'Arauca'],
            ['name' => 'Putumayo'],
            ['name' => 'San Andrés, Providencia y Santa Catalina'],
        ];

        foreach ($sectionals as $sectionalData) {
            $sectional = Sectional::create($sectionalData);

            $sectional->audits()->create([
                'user_name'      => 'Sistema',
                'rol_name'       => 'Sistema',
                'date_time'      => now(),
                'action_execute' => 'Creado',
                'status_old'     => null,
                'status_new'     => 'Activo',
                'data_old'       => null,
                'data_new'       => $sectional->name,
            ]);
        }
    }
}
