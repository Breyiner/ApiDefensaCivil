<?php

namespace Database\Seeders\User;

use Illuminate\Database\Seeder;
use App\Models\User\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'email' => 'voluntario@gmail.com',
                'password' => 'Password.123',
                'state_user_id' => 1,
                'role' => 'Voluntario',
            ],
            [
                'email' => 'supervisor@gmail.com',
                'password' => 'Password.123',
                'state_user_id' => 1,
                'role' => 'Supervisor',
            ],
            [
                'email' => 'Administrador@gmail.com',
                'password' => 'Password.123',
                'state_user_id' => 1,
                'role' => 'Administrador',
            ],
        ];

        foreach ($users as $data) {

            $user = User::create([
                'email' => $data['email'],
                'password' => $data['password'], // el modelo la hashea
                'state_user_id' => $data['state_user_id'],
                'email_verified_at' => now(),
            ]);
            
            // Asignar rol
            $user->assignRole($data['role']);
        }
    }
}