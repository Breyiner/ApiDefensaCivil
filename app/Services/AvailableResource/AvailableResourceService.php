<?php

namespace App\Services\AvailableResource;

use App\Models\AvailableResource\AvailableResource;
use App\Models\Resource\Resource;

class AvailableResourceService
{
    public function __construct()
    {
        //
    }

    public static function getAll()
    {
        $resources = AvailableResource::all();

        if ($resources->isEmpty()) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay registros de recursos disponibles",
                "data" => $resources,
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Registros de recursos disponibles obtenidos exitosamente",
            "data" => $resources,
        ];
    }

    public function getById($id)
    {
        $resource = AvailableResource::find($id);

        if (!$resource) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Recurso disponible no encontrado",
            ];
        }

        $serviceResource = Resource::find($resource->resource_id);

        $resource->resource_service = $serviceResource->service;
        $resource->resource_name = $serviceResource->name;

        return [
            "error" => false,
            "code" => 200,
            "message" => "Recurso disponible obtenido exitosamente",
            "data" => $resource,
        ];
    }

    // Obtener factores de riesgo por plan familiar (paginado)
    public function getByFamilyPlan($family_plan_id)
    {
        $paginator = AvailableResource::where('family_plan_id', $family_plan_id)
            ->with('resource')
            ->paginate(10);
        
        $items = $paginator->getCollection()->transform(function ($item) {
        return [
            'id' => $item->id,
            'resource_name' => $item->resource->name,
            'location' => $item->location,
            'distance' => $item->distance,
            'phone' => $item->phone,
            'service' => $item->resource->service,
            'description' => $item->description,
        ];
        });

        return [
            "error"   => false,
            "code"    => 200,
            "message" => $items->isEmpty()
                ? "No hay recursos disponibles para este plan familiar"
                : "Recursos disponibles obtenidos exitosamente",
            "data"    => $items,
            'paginate' => [
                'current_page' => $paginator->currentPage(), //pagina actual
                'per_page' => $paginator->perPage(),    //cuantos registros se muestran por pagina
                'total' => $paginator->total(), //total de registros
                'last_page' => $paginator->lastPage(), //ultima pagina
                'from' => $paginator->firstItem(), //numero del primer registro de la pagina
                'to' => $paginator->lastItem(), //numero del ultimo registro de la pagina
            ],
        ];
    }

    public function create(array $data)
    {
        $resource = AvailableResource::create($data);

        return [
            "error" => false,
            "code" => 201,
            "message" => "Recurso disponible creado exitosamente",
            "data" => $resource,
        ];
    }

    public function update(array $data, $id)
    {
        $resource = AvailableResource::find($id);

        if (!$resource) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Recurso disponible no encontrado",
            ];
        }

        $resource->update($data);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Recurso disponible actualizado exitosamente",
            "data" => $resource,
        ];
    }

    public function partialUpdate(array $data, $id)
    {
        $resource = AvailableResource::find($id);

        if (!$resource) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Recurso disponible no encontrado",
            ];
        }

        $resource->update($data);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Recurso disponible actualizado parcialmente exitosamente",
            "data" => $resource,
        ];
    }

    public function delete($id)
    {
        $resource = AvailableResource::find($id);

        if (!$resource) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Recurso disponible no encontrado",
            ];
        }

        $resource->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Recurso disponible eliminado exitosamente",
        ];
    }
}
