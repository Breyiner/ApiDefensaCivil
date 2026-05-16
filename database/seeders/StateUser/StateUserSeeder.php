<?php

namespace Database\Seeders\StateUser;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StateUser\StateUser;

class StateUserSeeder extends Seeder
{
    public function run(): void
    {
        StateUser::create([
            'name' => 'Activo'
        ]);
        StateUser::create([
            'name' => 'Inactivo'
        ]);
        StateUser::create([
            'name' => 'Pendiente'
        ]);
    }
}
