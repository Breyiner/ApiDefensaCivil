<?php

namespace App\Http\Controllers\API\Species;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Species\PartialUpdateSpeciesRequest;
use App\Http\Requests\Species\StoreSpeciesRequest;
use App\Http\Requests\Species\UpdateSpeciesRequest;
use App\Http\Requests\Species\ChangeStateSpeciesRequest;
use App\Models\Species\Species;
use App\Services\Species\SpecieServices;
use Illuminate\Http\Request;

class SpeciesController extends Controller
{
    protected $service;

    public function __construct(SpecieServices $service)
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
        $species = Species::find($id);
        if (!$species) {
            return ResponseFormatter::error("Registro no encontrado", 404);
        }

        return ResponseFormatter::success("Registro obtenido exitosamente", 200, $species);
    }

    public function store(StoreSpeciesRequest $request)
    {
        $data = $request->validated();

        $response = $this->service->create($data);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

    public function update(UpdateSpeciesRequest $request, string $id)
    {
        $species = species::find($id);
        if (!$species) {
            return ResponseFormatter::error("Registro no encontrado", 404);
        }

        $data = $request->validated();
        $response = $this->service->update($data, $id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function partialUpdate(PartialUpdateSpeciesRequest $request, string $id)
    {
        $species = Species::find($id);
        if (!$species) {
            return ResponseFormatter::error("Registro no encontrado", 404);
        }

        $data = $request->validated();
        $response = $this->service->partialUpdate($data, $id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function changeStatus(ChangeStateSpeciesRequest $request, string $id)
    {
        $data = $request->validated();
        $response = $this->service->changeStatus($data, $id);

        if ($response['error'])
        {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []); 
    }

    public function destroy(string $id)
    {
        $species = Species::find($id);
        if (!$species) {
            return ResponseFormatter::error("Registro no encontrado", 404);
        }

        $response = $this->service->delete($id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function history(Request $request, string $id)
    {
        $perPage = $request->input('per_page', 10);

        $response = $this->service->history($id, $perPage);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? [], $response['paginate'] ?? []);
    }
}
