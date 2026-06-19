<?php

namespace Database\Seeders\Nationality;

use Illuminate\Database\Seeder;
use App\Models\Nationality\Nationality;

class NationalitySeeder extends Seeder
{
    public function run(): void
    {
        $nationalities = [
            // América del Sur
            'Colombiana','Venezolana','Peruana','Argentina','Chilena','Ecuatoriana','Boliviana','Uruguaya','Paraguaya','Brasileña',
            // América Central
            'Panameña','Costarricense','Nicaragüense','Hondureña','Salvadoreña','Guatemalteca',
            // Caribe
            'Cubana','Dominicana','Puertorriqueña',
            // América del Norte
            'Mexicana','Estadounidense','Canadiense',
            // Resto del mundo
            'Europea','Asiática','Africana',
        ];

        foreach ($nationalities as $name) {
            $nationality = Nationality::create(['name' => $name]);

            // Crear auditoría simulando que lo hizo el sistema
            $nationality->audits()->create([
                'user_name'      => "Sistema",
                'rol_name'       => "Sistema",
                'date_time'      => now(),
                'action_execute' => 'Creado',
                'status_old'     => null,
                'status_new'     => "Activo",
                'data_old'       => null,
                'data_new'       => $nationality->name,
            ]);
        }
    }
}
