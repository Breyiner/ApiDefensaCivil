<?php

namespace App\Services\PetVaccine;

use App\Models\PetVaccine\PetVaccine;
use App\Models\Pet\Pet;
use Carbon\Carbon;

class PetVaccineService
{
    public function __construct()
    {
        //
    }

    public static function getAll()
    {
        $vaccines = PetVaccine::all();

        if ($vaccines->isEmpty()) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay registros de vacunas",
                "data" => $vaccines,
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Registros de vacunas obtenidos exitosamente",
            "data" => $vaccines,
        ];
    }

    public function getById($id)
    {
        $vaccine = PetVaccine::find($id);

        if (!$vaccine) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Vacuna no encontrada",
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Vacuna obtenida exitosamente",
            "data" => $vaccine,
        ];
    }

    public function getByPet($pet_id)
    {
        $pet = PetVaccine::with('pet')->where('pet_id', $pet_id)->get();

        if ($pet->isEmpty()) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "Esta mascota no tiene registros de vacunas",
                "data" => $pet,
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Registros de vacunas de la mascota obtenidos exitosamente",
            "data" => $pet,
        ];
    }

    public function create(array $data)
    {
        
        $pet = Pet::find($data['pet_id']);

        if (!$pet) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Mascota no encontrada",
            ];
        }

        if (Carbon::parse($data['date'])->lt(Carbon::parse($pet->birth_date))) {
            return [
                "error" => true,
                "code" => 400,
                "message" => "La fecha de la vacuna no puede ser anterior a la fecha de nacimiento de la mascota",
            ];
        }

        $vaccine = PetVaccine::create($data);

        return [
            "error" => false,
            "code" => 201,
            "message" => "Vacuna creada exitosamente",
            "data" => $vaccine,
        ];
    }

    public function update(array $data, $id)
    {
        $vaccine = PetVaccine::find($id);

        if (!$vaccine) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Vacuna no encontrada",
            ];
        }

        $petId = $data['pet_id'] ?? $vaccine->pet_id;
        $date = $data['date'] ?? $vaccine->date;

        $pet = Pet::find($petId);

        if (!$pet) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Mascota no encontrada",
            ];
        }

        if (Carbon::parse($date)->lt(Carbon::parse($pet->birth_date))) {
            return [
                "error" => true,
                "code" => 400,
                "message" => "La fecha de la vacuna no puede ser anterior a la fecha de nacimiento de la mascota",
            ];
        }

        $vaccine->update($data);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Vacuna actualizada exitosamente",
            "data" => $vaccine,
        ];
    }

    public function partialUpdate(array $data, $id)
    {
        $vaccine = PetVaccine::find($id);

        if (!$vaccine) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Vacuna no encontrada",
            ];
        }

        $petId = $data['pet_id'] ?? $vaccine->pet_id;
        $date = $data['date'] ?? $vaccine->date;

        $pet = Pet::find($petId);

        if (!$pet) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Mascota no encontrada",
            ];
        }

        if (Carbon::parse($date)->lt(Carbon::parse($pet->birth_date))) {
            return [
                "error" => true,
                "code" => 400,
                "message" => "La fecha de la vacuna no puede ser anterior a la fecha de nacimiento de la mascota",
            ];
        }

        $vaccine->update($data);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Vacuna actualizada parcialmente exitosamente",
            "data" => $vaccine,
        ];
    }

    public function delete($id)
    {
        $vaccine = PetVaccine::find($id);

        if (!$vaccine) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Vacuna no encontrada",
            ];
        }

        $vaccine->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Vacuna eliminada exitosamente",
        ];
    }
}
