<?php

namespace App\Services\Profile;

use App\Models\Profile\Profile;
use App\Models\User\User;
use Illuminate\support\Arr;

/**
 * Servicio para gestionar la información de perfil de los usuarios.
 * Maneja datos extendidos como organización, género y detalles personales.
 */
class ProfileService
{
    /**
     * Obtiene todos los perfiles registrados en el sistema.
     */
    public static function getAll()
    {
        $profile = Profile::all();

        if ($profile->isEmpty()){
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay estados de perfiles registrados",
                "data" => $profile,
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Perfiles obtenidos exitosamente",
            "data" => $profile,
        ];
    }

    /**
     * Obtiene un perfil específico por su ID.
     */
    public function getById($id)
    {
        $profile = Profile::find($id);

        if (!$profile){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Perfil no encontrado",
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Perfil obtenido exitosamente",
            "data" => $profile,
        ];
    }

    /**
     * Crea un perfil para un usuario.
     * Generalmente se llama durante el proceso de registro.
     */
    public function create(array $data)
    {
        $profile = Profile::create($data);

        return [
            "error" => false,
            "code" => 201,
            "message" => "Perfil creado exitosamente",
            "data" => $profile,
        ];
    }

    /**
     * Actualización total de los datos del perfil.
     */
    public function update(array $data, $id)
    {
        $profile = Profile::find($id);

        if (!$profile){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Perfil no encontrado",
            ];
        }

        $oldNames = $profile->names;
        $oldLastNames = $profile->last_names;
        $oldBirthDate = $profile->birth_date;
        $oldDocumentType = $profile->documentType?->name;
        $oldDocumentNumber = $profile->document_number;
        $oldGender = $profile->gender?->name;
        $oldOrganization = $profile->organization?->name;
        $oldSectional = $profile->organization?->sectional?->name;
        $oldUserRol = $profile->user?->roles->first()?->name;

        $profile->update($data);
        $profile->refresh()->load(['documentType', 'gender', 'organization.sectional', 'user.roles']);

        $authUser = auth()->user();

        $fullName = $authUser?->profile? $authUser->profile->names . ' ' . $authUser->profile->last_names: 'Sistema';

        $role = $authUser?->getRoleNames()?->first() ?? 'Sistema';

        $profile->user?->auditUsers()->create([

            'user_name'          => $fullName,
            'rol_name'           => $role,
            'date_time'          => now(),
            'action_execute'     => 'Actualización de Perfil',
            'status_old'         => null,
            'status_new'         => null,

            'userName_old'       => $oldNames,
            'userName_new'       => $profile->names,

            'lastName_old'       => $oldLastNames,
            'lastName_new'       => $profile->last_names,

            'documentType_old'   => $oldDocumentType,
            'documentType_new'   => $profile->documentType?->name,

            'numberDocument_old' => $oldDocumentNumber,
            'numberDocument_new' => $profile->document_number,

            'birthDate_old'      => $oldBirthDate,
            'birthDate_new'      => $profile->birth_date,

            'gender_old'         => $oldGender,
            'gender_new'         => $profile->gender?->name,

            'sectional_old'      => $oldSectional,
            'sectional_new'      => $profile->organization?->sectional?->name,

            'organization_old'   => $oldOrganization,
            'organization_new'   => $profile->organization?->name,

            'userRol_old'        => $oldUserRol,
            'userRol_new'        => $profile->user?->roles->first()?->name,

            
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Perfil actualizado exitosamente",
            "data" => $profile,
        ];
    }

    /**
     * Actualización parcial del perfil.
     * Útil para actualizar solo un campo (como el teléfono o la dirección).
     */
    public function partialUpdate(array $data,$id)
    {
        $profile = Profile::find($id);

        if (!$profile){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Perfil no encontrado", // Corregido: decía 'Organización'
            ];
        }

        $oldNames = $profile->names;
        $oldLastNames = $profile->last_names;
        $oldBirthDate = $profile->birth_date;
        $oldDocumentType = $profile->documentType?->name;
        $oldDocumentNumber = $profile->document_number;
        $oldGender = $profile->gender?->name;
        $oldOrganization = $profile->organization?->name;
        $oldSectional = $profile->organization?->sectional?->name;
        $oldUserRol = $profile->user?->roles->first()?->name;

        $profile->update($data);
        $profile->refresh()->load(['documentType', 'gender', 'organization.sectional', 'user.roles']);

        $authUser = auth()->user();

        $fullName = $authUser?->profile? $authUser->profile->names . ' ' . $authUser->profile->last_names: 'Sistema';

        $role = $authUser?->getRoleNames()?->first() ?? 'Sistema';

        $profile->user?->auditUsers()->create([
            'user_name'          => $fullName,
            'rol_name'           => $role,
            'date_time'          => now(),
            'action_execute'     => 'Actualización Parcial de Perfil',
            'status_old'         => null,
            'status_new'         => null,

            'userName_old'       => $oldNames,
            'userName_new'       => $profile->names,

            'lastName_old'       => $oldLastNames,
            'lastName_new'       => $profile->last_names,

            'documentType_old'   => $oldDocumentType,
            'documentType_new'   => $profile->documentType?->name,

            'numberDocument_old' => $oldDocumentNumber,
            'numberDocument_new' => $profile->document_number,

            'birthDate_old'      => $oldBirthDate,
            'birthDate_new'      => $profile->birth_date,

            'gender_old'         => $oldGender,
            'gender_new'         => $profile->gender?->name,

            'sectional_old'      => $oldSectional,
            'sectional_new'      => $profile->organization?->sectional?->name,

            'organization_old'   => $oldOrganization,
            'organization_new'   => $profile->organization?->name,

            'userRol_old'        => $oldUserRol,
            'userRol_new'        => $profile->user?->roles->first()?->name,
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Perfil actualizado parcialmente exitosamente",
            "data" => $profile,
        ];
    }

    /**
     * Elimina un perfil del sistema.
     */
    public function delete($id)
    {
        $profile = Profile::find($id);

        if (!$profile){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Perfil no encontrado",
            ];
        }
        
        $profile->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Perfil eliminado exitosamente",
        ];
    }

    public function history($userId, $perPage = 10)
    {
        $user = User::find($userId);

        if (!$user) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Usuario no encontrado",
            ];
        }

        $data = $user->auditUsers()
            ->orderBy('date_time', 'desc')
            ->paginate($perPage);

        $history = $data->map(function ($audit) {
            return [
                'date_time'          => $audit->date_time,
                'user_name'          => $audit->user_name,
                'rol'                => $audit->rol_name,
                'action_execute'     => $audit->action_execute,
                'status_old'         => $audit->status_old,
                'status_new'         => $audit->status_new,
                'userName_old'       => $audit->userName_old,
                'userName_new'       => $audit->userName_new,
                'lastName_old'       => $audit->lastName_old,
                'lastName_new'       => $audit->lastName_new,
                'userRol_old'        => $audit->userRol_old,
                'userRol_new'        => $audit->userRol_new,
                'documentType_old'   => $audit->documentType_old,
                'documentType_new'   => $audit->documentType_new,
                'numberDocument_old' => $audit->numberDocument_old,
                'numberDocument_new' => $audit->numberDocument_new,
                'birthDate_old'      => $audit->birthDate_old,
                'birthDate_new'      => $audit->birthDate_new,
                'gender_old'         => $audit->gender_old,
                'gender_new'         => $audit->gender_new,
                'sectional_old'      => $audit->sectional_old,
                'sectional_new'      => $audit->sectional_new,
                'organization_old'   => $audit->organization_old,
                'organization_new'   => $audit->organization_new,
                'id'                 => $audit->id
            ];
        });

        return [
            "error" => false,
            "code" => 200,
            "message" => "Historial de auditoría obtenido exitosamente",
            "data" => $history,
            "paginate" => [
                'current_page' => $data->currentPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ]
        ];
    }
}