<?php

namespace App\Services\HousingGraphic;

use App\Models\HousingGraphic\HousingGraphic;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

/**
 * Servicio para la gestión de imágenes e información de vivienda.
 * Maneja la carga y eliminación física de archivos en el disco 'public'.
 */
class HousingGraphicService
{
    /**
     * Obtiene todas las imágenes de vivienda registradas.
     */
    public function getAll()
    {
        $images = HousingGraphic::all();

        return [
            "error" => false,
            "code" => 200,
            "message" => $images->isEmpty()
                ? "No hay graficos de vivienda en el sistema"
                : "Graficos de vivienda obtenidos exitosamente",
            "data" => $images,
        ];
    }

    /**
     * Obtiene las imágenes asociadas a un Plan Familiar específico.
     * @param int $familyPlanId
     */
    public function getById($id)
    {
        $housingGraphic = HousingGraphic::find($id);


        if (!$housingGraphic) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Grafico de vivienda no encontrado",
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Grafico de vivienda obtenido exitosamente",
            "data" => $housingGraphic,
        ];
    }

    public function getByFamilyPlan(int $familyPlanId)
    {
        $paginator = HousingGraphic::where('family_plan_id', $familyPlanId)
            ->paginate(10);

        $items = $paginator->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'family_plan_id' => $item->family_plan_id,
                'description' => $item->description,
                'path' => $item->path,
                'url' => $item->path,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        });

        return [
            "error"   => false,
            "code"    => 200,
            "message" => $items->isEmpty()
                ? "No hay graficos de vivienda para este plan familiar"
                : "Graficos de vivienda obtenidos exitosamente",
            "data"    => $items,
            'paginate' => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
                'from'         => $paginator->firstItem(),
                'to'           => $paginator->lastItem(),
            ],
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
        $filePath = $file->store('images/graphics', 'public');

        $image = HousingGraphic::create([
            'family_plan_id' => $data['family_plan_id'],
            'path' => $filePath, // Guardamos la ruta relativa
            'description' => $data['description'],
        ]);

        return [
            "error" => false,
            "code" => 201,
            "message" => "El grafico de vivienda creado exitosamente",
            "data" => $image,
        ];
    }

    /**
     * Elimina el registro de la base de datos y borra el archivo físico del disco.
     * @param int $id ID del Plan Familiar (busca la primera imagen asociada).
     */
    public function partialUpdate(array $data, $id)
    {
        $housingGraphic = HousingGraphic::find($id);

        if (!$housingGraphic) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Grafico de vivienda no encontrada",
            ];
        }

        $housingGraphic->update($data);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Grafico de vivienda descripcion actualizada exitosamente",
            "data" => $housingGraphic,
        ];
    }

    public function delete(int $id)
    {
        $image = HousingGraphic::find($id);

        if (!$image) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "El grafico de vivienda no existe",
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
            "message" => "El grafico de vivienda a sido eliminado exitosamente",
        ];
    }
}
