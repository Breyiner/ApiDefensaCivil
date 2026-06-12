<?php

namespace Database\Seeders\Organization;

use Illuminate\Database\Seeder;
use App\Models\Organization\Organization;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $organizations = [
            ['name' => 'Arenal',                    'sectional_id' => 22],
            ['name' => 'Barbosa',                   'sectional_id' => 22],
            ['name' => 'Barrancabermeja',           'sectional_id' => 22],
            ['name' => 'California',                'sectional_id' => 22],
            ['name' => 'Capitanejo',                'sectional_id' => 22],
            ['name' => 'Cepitá',                    'sectional_id' => 22],
            ['name' => 'Cerrito',                   'sectional_id' => 22],
            ['name' => 'Charalá',                   'sectional_id' => 22],
            ['name' => 'Charta',                    'sectional_id' => 22],
            ['name' => 'Concepción',                'sectional_id' => 22],
            ['name' => 'Contratación',              'sectional_id' => 22],
            ['name' => 'Curití',                    'sectional_id' => 22],
            ['name' => 'El Carmen de Chucurí',      'sectional_id' => 22],
            ['name' => 'El Centro',                 'sectional_id' => 22],
            ['name' => 'El Playón',                 'sectional_id' => 22],
            ['name' => 'Escuela Palonegro',         'sectional_id' => 22],
            ['name' => 'Gámbita',                   'sectional_id' => 22],
            ['name' => 'Guaca',                     'sectional_id' => 22],
            ['name' => 'Guacamayo',                 'sectional_id' => 22],
            ['name' => 'Guadalupe',                 'sectional_id' => 22],
            ['name' => 'La Suiza',                  'sectional_id' => 22],
            ['name' => 'Lebrija',                   'sectional_id' => 22],
            ['name' => 'Matanza',                   'sectional_id' => 22],
            ['name' => 'Puente Nacional',           'sectional_id' => 22],
            ['name' => 'San Gil',                   'sectional_id' => 22],
            ['name' => 'San Joaquín',               'sectional_id' => 22],
            ['name' => 'Santa Bárbara',             'sectional_id' => 22],
            ['name' => 'Santander',                 'sectional_id' => 22],
            ['name' => 'Suratá',                    'sectional_id' => 22],
            ['name' => 'Vélez',                     'sectional_id' => 22],
            ['name' => 'Barrio La Joya',            'sectional_id' => 22],
            ['name' => 'Cimitarra',                 'sectional_id' => 22],
            ['name' => 'Ciudad Bonita',             'sectional_id' => 22],
            ['name' => 'Gaitán',                    'sectional_id' => 22],
            ['name' => 'Girón',                     'sectional_id' => 22],
            ['name' => 'Jesús María',               'sectional_id' => 22],
            ['name' => 'Las Granjas',               'sectional_id' => 22],
            ['name' => 'Málaga',                    'sectional_id' => 22],
            ['name' => 'Oiba',                      'sectional_id' => 22],
            ['name' => 'Piedecuesta',               'sectional_id' => 22],
            ['name' => 'Puerto Wilches',            'sectional_id' => 22],
            ['name' => 'Sabana de Torres',          'sectional_id' => 22],
            ['name' => 'San Vicente de Chucurí',    'sectional_id' => 22],
            ['name' => 'Socorro',                   'sectional_id' => 22],
            ['name' => 'Suaita',                    'sectional_id' => 22],
            ['name' => 'Vijagual',                  'sectional_id' => 22],
            ['name' => 'Villaluz',                  'sectional_id' => 22],
            ['name' => 'Zapatoca',                  'sectional_id' => 22],
        ];

        foreach ($organizations as $organizationData) {
            $organization = Organization::create($organizationData);
            $organization->load('sectional');

            // Crear auditoría
            $organization->fresh()->audits()->create([
                'user_name'      => "Sistema",
                'rol_name'       => "Sistema",
                'date_time'      => now(),
                'action_execute' => 'Creado',
                'status_old'     => null,
                'status_new'     => "Activo",
                'data_old'       => null,
                'data_new'       => $organization->name,
                'subData_old'    => null,
                'subData_new'    => $organization->sectional?->name,
            ]);
        }
    }
}
