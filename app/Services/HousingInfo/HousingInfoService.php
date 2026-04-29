<?php

namespace App\Services\HousingInfo;

use App\Models\HousingInfo\HousingInfo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

/**
 * Servicio para la gestión de imágenes e información de vivienda.
 * Maneja la carga y eliminación física de archivos en el disco 'public'.
 */
class HousingInfoService
{
    /**
     * Obtiene todas las imágenes de vivienda registradas.
     */
    public function getAll()
    {
        $images = HousingInfo::all();

        return [
            "error" => false,
            "code" => 200,
            "message" => $images->isEmpty()
                ? "No hay imágenes registradas"
                : "Imágenes obtenidas exitosamente",
            "data" => $images,
        ];
    }

    /**
     * Obtiene las imágenes asociadas a un Plan Familiar específico.
     * @param int $familyPlanId
     */
    public function getById(int $familyPlanId)
    {
        $images = HousingInfo::where('family_plan_id', $familyPlanId)->get();

        return [
            "error" => false,
            "code" => 200,
            "message" => $images->isEmpty()
                ? "No hay imágenes para este plan familiar"
                : "Imágenes obtenidas exitosamente",
            "data" => $images,
        ];
    }

    /**
     * Sube una imagen al storage y crea el registro en la base de datos.
     * @param array $data Debe contener 'path' (instancia de UploadedFile) y 'family_plan_id'.
     */
    public function create(array $data)
    {
        /** @var UploadedFile $file */
        $file = $data['path'];

        // Almacena el archivo en 'storage/app/public/imagenes'
        $filePath = $file->store('images/around', 'public');

        $image = HousingInfo::create([
            'family_plan_id' => $data['family_plan_id'],
            'path' => $filePath, // Guardamos la ruta relativa
            'housing_info_type_id' => $data['housing_info_type_id'],
        ]);

        return [
            "error" => false,
            "code" => 201,
            "message" => "Imagen creada exitosamente",
            "data" => $image,
        ];
    }

    /**
     * Elimina el registro de la base de datos y borra el archivo físico del disco.
     * @param int $id ID del Plan Familiar (busca la primera imagen asociada).
     */
    public function delete(int $id)
    {
        $image = HousingInfo::where('family_plan_id', $id)->first();

        if (!$image) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "No hay imagen para este plan familiar",
            ];
        }

        // Eliminación física del archivo para liberar espacio en disco
        if ($image->path && Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }

        $image->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Imagen eliminada exitosamente",
        ];
    }

    public function getByType($familyPlanId, $typeId)
    {
        $housingInfo = HousingInfo::where('family_plan_id', $familyPlanId)
            ->where('housing_info_type_id', $typeId)
            ->first();

        if (!$housingInfo) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "No existe registro para este tipo",
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Registro obtenido exitosamente",
            "data" => $housingInfo,
        ];
    }

    public function updateByType($familyPlanId, $typeId, $file)
    {
        $housingInfo = HousingInfo::where('family_plan_id', $familyPlanId)
            ->where('housing_info_type_id', $typeId)
            ->first();

        if (!$housingInfo) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Registro no encontrado",
            ];
        }

        // Borra el archivo viejo
        if ($housingInfo->path && Storage::disk('public')->exists($housingInfo->path)) {
            Storage::disk('public')->delete($housingInfo->path);
        }

        // Sube el nuevo
        $newPath = $file->store('images/around', 'public');
        $housingInfo->update(['path' => $newPath]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Imagen actualizada exitosamente",
            "data" => $housingInfo,
        ];
    }

    public function deleteByType($familyPlanId, $typeId)
    {
        $housingInfo = HousingInfo::where('family_plan_id', $familyPlanId)
            ->where('housing_info_type_id', $typeId)
            ->first();

        if (!$housingInfo) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Registro no encontrado",
            ];
        }

        Storage::delete($housingInfo->path);
        $housingInfo->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Registro eliminado exitosamente",
        ];
    }
}
