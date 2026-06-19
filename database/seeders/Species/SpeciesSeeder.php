<?php

namespace Database\Seeders\Species;

use Illuminate\Database\Seeder;
use App\Models\Species\Species;

class SpeciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $speciesList = [
            'Perro',
            'Gato',
            'Conejo',
            'Ruedor',
            'Ave',
            'Insecto',
            'Pez',
            'Rana',
            'Serpiente',
            'Tortuga'
        ];

        foreach ($speciesList as $name) {
            $species = Species::create(['name' => $name]);

            // Crear auditoría simulando que lo hizo el sistema
            $species->audits()->create([
                'user_name'      => "Sistema",
                'rol_name'       => "Sistema",
                'date_time'      => now(),
                'action_execute' => 'Creado',
                'status_old'     => null,
                'status_new'     => "Activo",
                'data_old'       => null,
                'data_new'       => $species->name,
            ]);
        }
    }
}
