<?php

namespace App\Services\housingInfoType;

use App\Models\HousingInfoType\HousingInfoType;

class HousingInfoTypeService
{
    public function __construct()
    {
        //
    }

    public static function getAll()
    {
        $housingTypes = HousingInfoType::all();

        if ($housingTypes->isEmpty()) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay registros de tipos de acción",
                "data" => $housingTypes,
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Registros de tipos de acción obtenidos exitosamente",
            "data" => $housingTypes,
        ];
    }

    public function getById($id)
    {
        $housingType = HousingInfoType::find($id);

        if (!$housingType) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Tipo de acción no encontrado",
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Tipo de acción obtenido exitosamente",
            "data" => $housingType,
        ];
    }

    public function create(array $data)
    {
        $housingType = HousingInfoType::create($data);

        return [
            "error" => false,
            "code" => 201,
            "message" => "Tipo de acción creado exitosamente",
            "data" => $housingType,
        ];
    }

    public function update(array $data, $id)
    {
        $housingType = HousingInfoType::find($id);

        if (!$housingType) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Tipo de acción no encontrado",
            ];
        }

        $housingType->update($data);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Tipo de acción actualizado exitosamente",
            "data" => $housingType,
        ];
    }

    public function delete($id)
    {
        $housingType = HousingInfoType::find($id);

        if (!$housingType) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Tipo de acción no encontrado",
            ];
        }

        $housingType->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Tipo de acción eliminado exitosamente",
        ];
    }
}
