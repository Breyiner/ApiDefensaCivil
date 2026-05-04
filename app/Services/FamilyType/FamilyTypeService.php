<?php

namespace App\Services\FamilyType;

use App\Models\familyType\familyType;

class FamilyTypeService
{
    public function __construct()
    {
        //
    }

    public static function getAll()
    {
        $familyTypes = familyType::all();

        if ($familyTypes->isEmpty()) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay registros de tipos de familia",
                "data" => $familyTypes,
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Registros de tipos de familia obtenidos exitosamente",
            "data" => $familyTypes,
        ];
    }

    public function getById($id)
    {
        $familyType = familyType::find($id);

        if (!$familyType) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Tipo de familia no encontrado",
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Tipo de familia obtenido exitosamente",
            "data" => $familyType,
        ];
    }

    public function create(array $data)
    {
        $familyType = familyType::create($data);

        return [
            "error" => false,
            "code" => 201,
            "message" => "Tipo de familia creado exitosamente",
            "data" => $familyType,
        ];
    }

    public function update(array $data, $id)
    {
        $familyType = familyType::find($id);

        if (!$familyType) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Tipo de familia no encontrado",
            ];
        }

        $familyType->update($data);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Tipo de familia actualizado exitosamente",
            "data" => $familyType,
        ];
    }

    public function delete($id)
    {
        $familyType = familyType::find($id);

        if (!$familyType) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Tipo de familia no encontrado",
            ];
        }

        $familyType->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Tipo de familia eliminado exitosamente",
        ];
    }
}
