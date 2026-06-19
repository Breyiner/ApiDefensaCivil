<?php

namespace Database\Seeders\Department;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            'Amazonas',
            'Antioquia',
            'Arauca',
            'Atlántico',
            'Bolívar',
            'Boyacá',
            'Caldas',
            'Caquetá',
            'Casanare',
            'Cauca',
            'Cesar',
            'Chocó',
            'Córdoba',
            'Cundinamarca',
            'Guainía',
            'Guaviare',
            'Huila',
            'La Guajira',
            'Magdalena',
            'Meta',
            'Nariño',
            'Norte de Santander',
            'Putumayo',
            'Quindío',
            'Risaralda',
            'San Andrés y Providencia',
            'Santander',
            'Sucre',
            'Tolima',
            'Valle del Cauca',
            'Vaupés',
            'Vichada'
        ];

        foreach ($departments as $name) {
            $department = Department::create(['name' => $name]);

            $department->audits()->create([
                'user_name'      => 'Sistema',
                'rol_name'       => 'Sistema',
                'date_time'      => now(),
                'action_execute' => 'Creado',
                'status_old'     => null,
                'status_new'     => null,
                'data_old'       => null,
                'data_new'       => $department->name,
            ]);
        }
    }
}