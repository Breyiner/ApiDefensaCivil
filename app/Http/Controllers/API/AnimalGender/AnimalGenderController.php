<?php

namespace App\Http\Controllers\API\AnimalGender;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnimalGender\StoreAnimalGenderRequest;
use App\Http\Requests\AnimalGender\UpdateAnimalGenderRequest;
use App\Http\Requests\AnimalGender\PartialUpdateAnimalGenderRequest;
use App\Models\Pet\Pet;
use App\Services\AnimalGender\AnimalGenderService;

/**
 * Controlador para la gestión de Géneros de Animales.
 * Maneja el catálogo de géneros (Macho / Hembra).
 */
class AnimalGenderController extends Controller
{
    protected $service;

    /**
     * Inyección del servicio de lógica.
     */
    public function __construct(AnimalGenderService $service)
    {
        $this->service = $service;
    }

    /**
     * Lista todos los géneros de animales.
     */
    public function index()
    {
        $response = $this->service->getAll();

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Muestra un género por ID.
     */
    public function show(string $id)
    {
        $response = $this->service->getById($id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function getByPet(string $pet_id){
                // 1. Buscar el Member por ID
        $pet = Pet::find($pet_id);
        if (!$pet) {
            return ResponseFormatter::error('Mascota no encontrada', 404);
        }

        $response = $this->service->getById($pet->animal_gender_id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

    /**
     * Registra un nuevo género.
     */
    public function store(StoreAnimalGenderRequest $request)
    {
        $data = $request->validated();
        $response = $this->service->create($data);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Actualización completa (PUT).
     */
    public function update(UpdateAnimalGenderRequest $request, string $id)
    {
        $data = $request->validated();
        $response = $this->service->update($data, $id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Elimina un registro.
     */
    public function destroy(string $id)
    {
        $response = $this->service->delete($id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
