<?php

namespace Database\Seeders\Audit;

use Illuminate\Database\Seeder;
use App\Models\User\User;
use App\Models\Audit\AuditUser;

class AuditUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Crea un registro de auditoría simulando la creación inicial
     * de cada usuario ya sembrado (ver ProfileSeeder).
     */
    public function run(): void
    {
        $users = User::with('profile')->get();

        foreach ($users as $user) {

            if (!$user->profile) {
                continue;
            }

            $roleName = $user->getRoleNames()->first() ?? 'Sistema';

            AuditUser::create([
                'user_name'      => "Sistema",
                'rol_name'       => "Sistema",
                'date_time'      => now(),
                'action_execute' => 'Creado',
                'status_old'     => null,
                'status_new'     => 'Activo',

                'userName_old'   => null,
                'userName_new'   => $user->profile->names,

                'lastName_old'   => null,
                'lastName_new'   => $user->profile->last_names,

                'userRol_old'    => null,
                'userRol_new'    => $roleName,

                'documentType_old'   => null,
                'documentType_new'   => $user->profile->documentType?->name,

                'numberDocument_old' => null,
                'numberDocument_new' => $user->profile->document_number,

                'birthDate_old'  => null,
                'birthDate_new'  => $user->profile->birth_date,

                'gender_old'     => null,
                'gender_new'     => $user->profile->gender?->name,

                'sectional_old'  => null,
                'sectional_new'  => $user->profile->organization?->sectional?->name,

                'organization_old' => null,
                'organization_new' => $user->profile->organization?->name,

                'historiable_id'   => $user->id,
                'historiable_type' => User::class,
            ]);
        }
    }
}