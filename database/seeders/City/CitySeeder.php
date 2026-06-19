<?php

namespace Database\Seeders\City;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City\City;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            // 1 - Amazonas
            ['name' => 'Leticia',         'department_id' => 1],
            ['name' => 'Puerto Nariño',   'department_id' => 1],

            // 2 - Antioquia
            ['name' => 'Medellín',        'department_id' => 2],
            ['name' => 'Bello',           'department_id' => 2],
            ['name' => 'Itagüí',          'department_id' => 2],
            ['name' => 'Envigado',        'department_id' => 2],
            ['name' => 'Apartadó',        'department_id' => 2],
            ['name' => 'Turbo',           'department_id' => 2],
            ['name' => 'Rionegro',        'department_id' => 2],

            // 3 - Arauca
            ['name' => 'Arauca',          'department_id' => 3],
            ['name' => 'Saravena',        'department_id' => 3],
            ['name' => 'Tame',            'department_id' => 3],

            // 4 - Atlántico
            ['name' => 'Barranquilla',    'department_id' => 4],
            ['name' => 'Soledad',         'department_id' => 4],
            ['name' => 'Malambo',         'department_id' => 4],
            ['name' => 'Sabanalarga',     'department_id' => 4],

            // 5 - Bolívar
            ['name' => 'Cartagena',       'department_id' => 5],
            ['name' => 'Magangué',        'department_id' => 5],
            ['name' => 'El Carmen de Bolívar', 'department_id' => 5],
            ['name' => 'Mompós',          'department_id' => 5],

            // 6 - Boyacá
            ['name' => 'Tunja',           'department_id' => 6],
            ['name' => 'Duitama',         'department_id' => 6],
            ['name' => 'Sogamoso',        'department_id' => 6],
            ['name' => 'Chiquinquirá',    'department_id' => 6],

            // 7 - Caldas
            ['name' => 'Manizales',       'department_id' => 7],
            ['name' => 'La Dorada',       'department_id' => 7],
            ['name' => 'Chinchiná',       'department_id' => 7],

            // 8 - Caquetá
            ['name' => 'Florencia',       'department_id' => 8],
            ['name' => 'San Vicente del Caguán', 'department_id' => 8],
            ['name' => 'Puerto Rico',     'department_id' => 8],

            // 9 - Casanare
            ['name' => 'Yopal',           'department_id' => 9],
            ['name' => 'Aguazul',         'department_id' => 9],
            ['name' => 'Villanueva',      'department_id' => 9],

            // 10 - Cauca
            ['name' => 'Popayán',         'department_id' => 10],
            ['name' => 'Santander de Quilichao', 'department_id' => 10],
            ['name' => 'Puerto Tejada',   'department_id' => 10],

            // 11 - Cesar
            ['name' => 'Valledupar',      'department_id' => 11],
            ['name' => 'Aguachica',       'department_id' => 11],
            ['name' => 'Bosconia',        'department_id' => 11],

            // 12 - Chocó
            ['name' => 'Quibdó',          'department_id' => 12],
            ['name' => 'Istmina',         'department_id' => 12],
            ['name' => 'Tadó',            'department_id' => 12],

            // 13 - Córdoba
            ['name' => 'Montería',        'department_id' => 13],
            ['name' => 'Lorica',          'department_id' => 13],
            ['name' => 'Sahagún',         'department_id' => 13],
            ['name' => 'Montelíbano',     'department_id' => 13],

            // 14 - Cundinamarca
            ['name' => 'Bogotá',          'department_id' => 14],
            ['name' => 'Soacha',          'department_id' => 14],
            ['name' => 'Chía',            'department_id' => 14],
            ['name' => 'Fusagasugá',      'department_id' => 14],
            ['name' => 'Zipaquirá',       'department_id' => 14],
            ['name' => 'Facatativá',      'department_id' => 14],
            ['name' => 'Mosquera',        'department_id' => 14],

            // 15 - Guainía
            ['name' => 'Inírida',         'department_id' => 15],

            // 16 - Guaviare
            ['name' => 'San José del Guaviare', 'department_id' => 16],
            ['name' => 'El Retorno',      'department_id' => 16],

            // 17 - Huila
            ['name' => 'Neiva',           'department_id' => 17],
            ['name' => 'Pitalito',        'department_id' => 17],
            ['name' => 'Garzón',          'department_id' => 17],

            // 18 - La Guajira
            ['name' => 'Riohacha',        'department_id' => 18],
            ['name' => 'Maicao',          'department_id' => 18],
            ['name' => 'Uribia',          'department_id' => 18],

            // 19 - Magdalena
            ['name' => 'Santa Marta',     'department_id' => 19],
            ['name' => 'Ciénaga',         'department_id' => 19],
            ['name' => 'Fundación',       'department_id' => 19],

            // 20 - Meta
            ['name' => 'Villavicencio',   'department_id' => 20],
            ['name' => 'Acacías',         'department_id' => 20],
            ['name' => 'Granada',         'department_id' => 20],

            // 21 - Nariño
            ['name' => 'Pasto',           'department_id' => 21],
            ['name' => 'Tumaco',          'department_id' => 21],
            ['name' => 'Ipiales',         'department_id' => 21],

            // 22 - Norte de Santander
            ['name' => 'Cúcuta',          'department_id' => 22],
            ['name' => 'Ocaña',           'department_id' => 22],
            ['name' => 'Pamplona',        'department_id' => 22],
            ['name' => 'Villa del Rosario', 'department_id' => 22],

            // 23 - Putumayo
            ['name' => 'Mocoa',           'department_id' => 23],
            ['name' => 'Puerto Asís',     'department_id' => 23],
            ['name' => 'Orito',           'department_id' => 23],

            // 24 - Quindío
            ['name' => 'Armenia',         'department_id' => 24],
            ['name' => 'Calarcá',         'department_id' => 24],
            ['name' => 'Montenegro',      'department_id' => 24],

            // 25 - Risaralda
            ['name' => 'Pereira',         'department_id' => 25],
            ['name' => 'Dosquebradas',    'department_id' => 25],
            ['name' => 'Santa Rosa de Cabal', 'department_id' => 25],

            // 26 - San Andrés y Providencia
            ['name' => 'San Andrés',      'department_id' => 26],
            ['name' => 'Providencia',     'department_id' => 26],

            // 27 - Santander
            ['name' => 'Aguada',                    'department_id' => 27],
            ['name' => 'Albania',                   'department_id' => 27],
            ['name' => 'Aratoca',                   'department_id' => 27],
            ['name' => 'Barbosa',                   'department_id' => 27],
            ['name' => 'Barichara',                 'department_id' => 27],
            ['name' => 'Barrancabermeja',           'department_id' => 27],
            ['name' => 'Betulia',                   'department_id' => 27],
            ['name' => 'Bolívar',                   'department_id' => 27],
            ['name' => 'Bucaramanga',               'department_id' => 27],
            ['name' => 'Cabrera',                   'department_id' => 27],
            ['name' => 'California',                'department_id' => 27],
            ['name' => 'Capitanejo',                'department_id' => 27],
            ['name' => 'Carcasí',                   'department_id' => 27],
            ['name' => 'Cepitá',                    'department_id' => 27],
            ['name' => 'Cerrito',                   'department_id' => 27],
            ['name' => 'Charalá',                   'department_id' => 27],
            ['name' => 'Charta',                    'department_id' => 27],
            ['name' => 'Chima',                     'department_id' => 27],
            ['name' => 'Chipatá',                   'department_id' => 27],
            ['name' => 'Cimitarra',                 'department_id' => 27],
            ['name' => 'Concepción',                'department_id' => 27],
            ['name' => 'Confines',                  'department_id' => 27],
            ['name' => 'Contratación',              'department_id' => 27],
            ['name' => 'Coromoro',                  'department_id' => 27],
            ['name' => 'Curití',                    'department_id' => 27],
            ['name' => 'El Carmen de Chucurí',      'department_id' => 27],
            ['name' => 'El Guacamayo',              'department_id' => 27],
            ['name' => 'El Peñón',                  'department_id' => 27],
            ['name' => 'El Playón',                 'department_id' => 27],
            ['name' => 'Encino',                    'department_id' => 27],
            ['name' => 'Enciso',                    'department_id' => 27],
            ['name' => 'Florián',                   'department_id' => 27],
            ['name' => 'Floridablanca',             'department_id' => 27],
            ['name' => 'Galán',                     'department_id' => 27],
            ['name' => 'Gámbita',                   'department_id' => 27],
            ['name' => 'Girón',                     'department_id' => 27],
            ['name' => 'Guadalupe',                 'department_id' => 27],
            ['name' => 'Guapotá',                   'department_id' => 27],
            ['name' => 'Guavatá',                   'department_id' => 27],
            ['name' => 'Güepsa',                    'department_id' => 27],
            ['name' => 'Hato',                      'department_id' => 27],
            ['name' => 'Jesús María',               'department_id' => 27],
            ['name' => 'Jordán',                    'department_id' => 27],
            ['name' => 'La Belleza',                'department_id' => 27],
            ['name' => 'La Paz',                    'department_id' => 27],
            ['name' => 'Landázuri',                 'department_id' => 27],
            ['name' => 'Lebrija',                   'department_id' => 27],
            ['name' => 'Los Santos',                'department_id' => 27],
            ['name' => 'Macaravita',                'department_id' => 27],
            ['name' => 'Málaga',                    'department_id' => 27],
            ['name' => 'Matanza',                   'department_id' => 27],
            ['name' => 'Mogotes',                   'department_id' => 27],
            ['name' => 'Molagavita',                'department_id' => 27],
            ['name' => 'Ocamonte',                  'department_id' => 27],
            ['name' => 'Oiba',                      'department_id' => 27],
            ['name' => 'Onzaga',                    'department_id' => 27],
            ['name' => 'Palmar',                    'department_id' => 27],
            ['name' => 'Palmas del Socorro',        'department_id' => 27],
            ['name' => 'Páramo',                    'department_id' => 27],
            ['name' => 'Piedecuesta',               'department_id' => 27],
            ['name' => 'Pinchote',                  'department_id' => 27],
            ['name' => 'Puente Nacional',           'department_id' => 27],
            ['name' => 'Puerto Parra',              'department_id' => 27],
            ['name' => 'Puerto Wilches',            'department_id' => 27],
            ['name' => 'Rionegro',                  'department_id' => 27],
            ['name' => 'Sabana de Torres',          'department_id' => 27],
            ['name' => 'San Andrés',                'department_id' => 27],
            ['name' => 'San Benito',                'department_id' => 27],
            ['name' => 'San Gil',                   'department_id' => 27],
            ['name' => 'San Joaquín',               'department_id' => 27],
            ['name' => 'San José de Miranda',       'department_id' => 27],
            ['name' => 'San Miguel',                'department_id' => 27],
            ['name' => 'San Vicente de Chucurí',    'department_id' => 27],
            ['name' => 'Santa Bárbara',             'department_id' => 27],
            ['name' => 'Santa Helena del Opón',     'department_id' => 27],
            ['name' => 'Simacota',                  'department_id' => 27],
            ['name' => 'Socorro',                   'department_id' => 27],
            ['name' => 'Suaita',                    'department_id' => 27],
            ['name' => 'Sucre',                     'department_id' => 27],
            ['name' => 'Suratá',                    'department_id' => 27],
            ['name' => 'Tona',                      'department_id' => 27],
            ['name' => 'Valle de San José',         'department_id' => 27],
            ['name' => 'Vélez',                     'department_id' => 27],
            ['name' => 'Vetas',                     'department_id' => 27],
            ['name' => 'Villanueva',                'department_id' => 27],
            ['name' => 'Zapatoca',                  'department_id' => 27],

            // 28 - Sucre
            ['name' => 'Sincelejo',       'department_id' => 28],
            ['name' => 'Corozal',         'department_id' => 28],
            ['name' => 'Sampués',         'department_id' => 28],

            // 29 - Tolima
            ['name' => 'Ibagué',          'department_id' => 29],
            ['name' => 'Espinal',         'department_id' => 29],
            ['name' => 'Melgar',          'department_id' => 29],

            // 30 - Valle del Cauca
            ['name' => 'Cali',            'department_id' => 30],
            ['name' => 'Buenaventura',    'department_id' => 30],
            ['name' => 'Palmira',         'department_id' => 30],
            ['name' => 'Tuluá',           'department_id' => 30],
            ['name' => 'Buga',            'department_id' => 30],

            // 31 - Vaupés
            ['name' => 'Mitú',            'department_id' => 31],

            // 32 - Vichada
            ['name' => 'Puerto Carreño',  'department_id' => 32],
            ['name' => 'Cumaribo',        'department_id' => 32],
        ];

        foreach ($cities as $city) {
            $city = City::create($city);
            
            $city->audits()->create([
                'user_name'      => 'Sistema',
                'rol_name'       => 'Sistema',
                'date_time'      => now(),
                'action_execute' => 'Creado',
                'status_old'     => null,
                'status_new'     => null,
                'data_old'       => null,
                'data_new'       => $city->name,
                'subData_old'    => null,
                'subData_new'    => $city->department?->name,
            ]);
        }
    }
}
