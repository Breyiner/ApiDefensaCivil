<?php

namespace Database\Seeders\StatusPlan;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StatusPlan\StatusPlan;

class StatusPlanSeeder extends Seeder
{
    public function run(): void
    {
        StatusPlan::create([
            'name' => 'Creado'
        ]);
        StatusPlan::create([
            'name' => 'No Aplica'  //No existe 
        ]);
        StatusPlan::create([
            'name' => 'En Proceso'
        ]);
        StatusPlan::create([
            'name' => 'En Revisión' //pasa a supervisor
        ]);
        StatusPlan::create([
            'name' => 'Devuelto con Observaciones'
        ]);
        StatusPlan::create([
            'name' => 'Rechazado'
        ]);
        StatusPlan::create([
            'name' => 'Aprobado'
        ]);   
    }
}
