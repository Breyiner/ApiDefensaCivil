<?php

namespace App\Services\City;

use App\Models\City\City;
use App\Models\Department\Department;
use Illuminate\Support\Arr;

/**
 * Servicio para la gestión de ciudades y sus relaciones con departamentos y planes familiares.
 */
class CityService
{
    /**
     * Obtiene el listado completo de ciudades registradas.
     * @return array Respuesta estructurada con la colección de ciudades.
     */
    public static function getAll()
    {
        $city = City::all();

        if ($city->isEmpty()) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay ciudades registradas",
                "data" => $city,
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Ciudades obtenidas exitosamente",
            "data" => $city,
        ];
    }

    /**
     * Busca una ciudad específica por su identificador único.
     * @param int|string $id ID de la ciudad.
     */
    public function getById($id)
    {
        $city = City::find($id);

        if (!$city) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Ciudad no encontrada",
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Ciudad obtenida exitosamente",
            "data" => $city,
        ];
    }

    /**
     * Registra una nueva ciudad en el sistema.
     * @param array $data Datos de la ciudad (nombre, department_id, etc).
     */
    public function create(array $data)
    {
        $city = City::create($data);

        $city->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Creado',
            'status_old'     => null,
            'status_new'     => null,
        ]);

        return [
            "error" => false,
            "code" => 201,
            "message" => "Ciudad creada exitosamente",
            "data" => $city,
        ];
    }

    /**
     * Actualiza todos los campos de una ciudad existente.
     */
    public function update(array $data, $id)
    {
        $city = City::find($id);

        if (!$city) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Ciudad no encontrada",
            ];
        }

        $city->update($data);

        $city->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Actualizado',
            'status_old'     => null,
            'status_new'     => null,
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Ciudad actualizada exitosamente",
            "data" => $city,
        ];
    }

    /**
     * Actualiza parcialmente los datos de una ciudad (PATCH).
     */
    public function partialUpdate(array $data, $id)
    {
        $city = City::find($id);

        if (!$city) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Ciudad no encontrada",
            ];
        }

        $city->update($data);

        $city->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Actualizado parcialmente',
            'status_old'     => null,
            'status_new'     => null,
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Ciudad actualizada parcialmente exitosamente",
            "data" => $city,
        ];
    }

    /**
     * Elimina una ciudad si no tiene planes familiares asociados.
     * @return array Error 409 si existen registros relacionados.
     */
    public function delete($id)
    {
        $city = City::find($id);

        if (!$city) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Ciudad no encontrada",
            ];
        }

        // Verificación de integridad referencial con el Plan Familiar
        if ($city->familyPlan->count()) {
            return [
                "error" => true,
                "code" => 409,
                "message" => "No se puede eliminar la ciudad porque tiene registros relacionados",
            ];
        }

        // Guardamos auditoría antes de eliminar
        $city->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Eliminado',
            'status_old'     => null,
            'status_new'     => null,
        ]);

        $city->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Ciudad eliminada exitosamente",
        ];
    }

    /**
     * Obtiene el historial de auditoría de una ciudad.
     * @param int|string $id ID de la ciudad.
     * @return array Historial de cambios o error 404.
     */
    public function history($id)
    {
        $city = City::find($id);

        if (!$city) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Ciudad no encontrada",
            ];
        }

        $history = $city->audits()
            ->orderBy('date_time', 'desc')
            ->get()
            ->map(function ($audit) {
                return [
                    'date_time'      => $audit->date_time,
                    'user_name'      => $audit->user_name,
                    'rol'            => $audit->rol_name,
                    'action_execute' => $audit->action_execute,
                    'status_old'     => $audit->status_old,
                    'status_new'     => $audit->status_new,
                ];
            });

        return [
            "error" => false,
            "code" => 200,
            "message" => "Historial obtenido exitosamente",
            "data" => $history,
        ];
    }

    /**
     * Obtiene todas las ciudades que pertenecen a un departamento específico.
     * @param int|string $departmentId ID del departamento.
     */
    public static function getAllByDepartment($departmentId)
    {
        $department = Department::find($departmentId);

        if (!$department) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No existe este departamento",
                "data" => $department,
            ];
        }

        $city = $department->city;

        if (!$city) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No existen ciudades relacionadas al departamento",
                "data" => $city,
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Ciudades obtenidas exitosamente",
            "data" => $city,
        ];
    }
}
