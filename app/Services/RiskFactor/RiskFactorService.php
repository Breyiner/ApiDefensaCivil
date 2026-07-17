<?php

namespace App\Services\RiskFactor;

use App\Models\RiskFactor\RiskFactor;

class RiskFactorService
{
    public function __construct()
    {
        //
    }

    // Obtener todos los factores de riesgo con relaciones
    public static function getAll()
    {
        $riskFactors = RiskFactor::with(['threatType', 'familyPlan'])->get();

        if ($riskFactors->isEmpty()) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay registros de factores de riesgo",
                "data" => $riskFactors,
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Factores de riesgo obtenidos exitosamente",
            "data" => $riskFactors,
        ];
    }

    // Obtener factor de riesgo por ID con relaciones y datos relacionados
    public function getById($id)
    {
        $riskFactor = RiskFactor::with('threatType')->find($id);

        if (!$riskFactor) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Factor de riesgo no encontrado",
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Factor de riesgo obtenido exitosamente",
            "data" => $riskFactor,
        ];
    }

    // Obtener factores de riesgo por plan familiar (paginado)
    public function getByFamilyPlan($family_plan_id)
    {
        $paginator = RiskFactor::where('family_plan_id', $family_plan_id)
            ->with('threatType')
            ->paginate(10);

        $items = $paginator->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'threat_type_id' => $item->threat_type_id,
                'threat_type_name' => $item->threatType->name,
                'description' => $item->description,
                'ubication' => $item->ubication,
                'distance' => $item->distance,
            ];
        });

        return [
            "error"   => false,
            "code"    => 200,
            "message" => $items->isEmpty()
                ? "Este plan familiar no tiene factores de riesgo registrados"
                : "Factores de riesgo del plan familiar obtenidos exitosamente",
            "data"    => $items,
            'paginate' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ];
    }

    public function getRiskFactorSelect($family_plan_id)
    {
        $collection = RiskFactor::where('family_plan_id', $family_plan_id)->with('threatType')->get();

        $items = $collection->transform(function ($item) {
            return [
                'id' => $item->id,
                'threat_type_name' => $item->threatType->name,
                'description' => $item->description,
            ];
        });

        return [
            "error"   => false,
            "code"    => 200,
            "message" => "Factores de riesgo del plan familiar obtenidos exitosamente",
            "data"    => $items,
        ];
    }

    // Crear nuevo factor de riesgo
    public function create(array $data)
    {
        $riskFactor = RiskFactor::create($data);

        return [
            "error" => false,
            "code" => 201,
            "message" => "Factor de riesgo creado exitosamente",
            "data" => $riskFactor,
        ];
    }

    // Actualización completa
    public function update(array $data, $id)
    {
        $riskFactor = RiskFactor::find($id);

        if (!$riskFactor) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Factor de riesgo no encontrado",
            ];
        }

        $riskFactor->update($data);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Factor de riesgo actualizado exitosamente",
            "data" => $riskFactor,
        ];
    }

    // Actualización parcial
    public function partialUpdate(array $data, $id)
    {
        $riskFactor = RiskFactor::find($id);

        if (!$riskFactor) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Factor de riesgo no encontrado",
            ];
        }

        $riskFactor->update($data);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Factor de riesgo actualizado parcialmente exitosamente",
            "data" => $riskFactor,
        ];
    }

    // Eliminar factor de riesgo
    public function delete($id)
    {
        $riskFactor = RiskFactor::find($id);

        if (!$riskFactor) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Factor de riesgo no encontrado",
            ];
        }

        if ($riskFactor->actionPlan()->exists()) {
            return [
                "error" => true,
                "code" => 409,
                "message" => "No se puede eliminar este factor de riesgo porque ya tiene un Plan de Acción asociado.",
            ];
        }

        $riskFactor->riskReductionActions()->delete();
        $riskFactor->vulnerabilityFactors()->delete();
        $riskFactor->delete();


        return [
            "error" => false,
            "code" => 200,
            "message" => "Factor de riesgo eliminado exitosamente",
        ];
    }

    
}

