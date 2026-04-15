<?php

namespace App\Http\Controllers\API\Pet;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pet\StorePetRequest;
use App\Http\Requests\Pet\UpdatePetRequest;
use App\Http\Requests\Pet\PartialUpdatePetRequest;
use App\Models\Pet\Pet;
use App\Services\Pet\PetService;
use App\Policies\AccessPlanPolicy;
use App\Policies\AccessPetPolicy;

class PetController extends Controller
{
    protected $service;

    public function __construct(PetService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $response = $this->service->getAll();

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function show(string $id)
    {
        $Pet = Pet::find($id);

        if (!$Pet) {
            return ResponseFormatter::error("Registro no encontrado", 404);
        }

        if (!(new AccessPetPolicy())->access($Pet)) {
            return ResponseFormatter::error(
                'Usted no tiene autorización para ver esta mascota',
                403
            );
        }

       $response = $this->service->getById($id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function getPetsForPlan(string $plan_id)
    {
        // Validación de acceso al plan
        if (!(new AccessPlanPolicy())->access($plan_id)) {
            return ResponseFormatter::error(
                'Usted no tiene autorización para acceder a este plan',
                403
            );
        }

        $response = $this->service->getPetsForPlan($plan_id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? [], $response['paginate']);
    }

    public function store(StorePetRequest $request)
    {
        // Validación de acceso al plan
        if (!(new AccessPlanPolicy())->access((int)$request->family_plan_id))
        {
            return ResponseFormatter::error(
                'Usted no tiene autorización para agregar mascotas a este plan',
                403
            );
        }
        $data = $request->validated();
        $response = $this->service->create($data);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function update(UpdatePetRequest $request, string $id)
    {
        $Pet = Pet::find($id);

        if (!$Pet) {
            return ResponseFormatter::error("Registro no encontrado", 404);
        }

        if (!(new AccessPetPolicy())->access($Pet)) {
            return ResponseFormatter::error(
                'Usted no tiene autorización para modificar esta mascota',
                403
            );
        }

        $response = $this->service->update($request->validated(), $id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function partialUpdate(PartialUpdatePetRequest $request, string $id)
    {
        $Pet = Pet::find($id);

        if (!$Pet) {
            return ResponseFormatter::error("Registro no encontrado", 404);
        }

        if (!(new AccessPetPolicy())->access($Pet)) {
            return ResponseFormatter::error(
                'Usted no tiene autorización para modificar esta mascota',
                403
            );
        }

        $response = $this->service->partialUpdate($request->validated(), $id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function destroy(string $id)
    {
        $Pet = Pet::find($id);

        if (!$Pet) {
            return ResponseFormatter::error("Registro no encontrado", 404);
        }

        if (!(new AccessPetPolicy())->access($Pet)) {
            return ResponseFormatter::error(
                'Usted no tiene autorización para modificar esta mascota',
                403
            );
        }

        $response = $this->service->delete($id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
