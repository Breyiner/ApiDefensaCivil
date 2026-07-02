<?php

namespace Database\Seeders\Eps;

use Illuminate\Database\Seeder;
use App\Models\Eps\Eps;

class EpsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $epsList = [
            'SANITAS',
            'SURA',
            'COOMEVA',
            'COMPENSAR',
            'NUEVA EPS',
            'FAMISANAR',
            'ALIANSALUD',
            'SALUD TOTAL',
            'CAFESALUD',
            'COLSANITAS',
            'COOSALUD'
        ];

        foreach ($epsList as $name) {
            $eps = Eps::create(['name' => $name]);

            // Crear auditoría simulando que lo hizo el sistema
            $eps->audits()->create([
                'user_name'      => "Sistema",
                'rol_name'       => "Sistema",
                'date_time'      => now(),
                'action_execute' => 'Creado',
                'status_old'     => null,
                'status_new'     => "Activo",
                'data_old'       => null,
                'data_new'       => $eps->name,
            ]);
        }
    }
}
