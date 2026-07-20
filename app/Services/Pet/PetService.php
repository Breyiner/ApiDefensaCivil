<?php

namespace App\Services\Pet;

use App\Models\Pet\Pet;
use App\Models\PetVaccine\PetVaccine;

class PetService
{
    public function __construct()
    {
        //
    }

    public static function getAll()
    {
        $Pet = Pet::all();

        if ($Pet->isEmpty()) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay registros de mascotas",
                "data" => $Pet,
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Registros de mascotas obtenidos exitosamente",
            "data" => $Pet,
        ];
    }

    public function getById($id)
    {
        $pet = Pet::with(['species','animalGender'])->find($id);
        
        $conditionPet = $pet->petVaccine;

        if (!$pet) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Mascota no encontrada",
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Mascota obtenida exitosamente",
            "data" => $pet,$conditionPet,
        ];
    }
    
    public function getPetsForPlan($family_plan_id)
    {
        $paginator = Pet::where('family_plan_id', $family_plan_id)
            ->with([
                'Species',
                'AnimalGender'
            ])->paginate(10);
        
        $items = $paginator->getCollection()->transform(function ($item) {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'breed' => $item->breed,
            'age' => $item->age,
            'animal_gender_id' => $item->animal_gender_id,
            'animal_gender_name' => $item->animalGender->name,
            'species_id' => $item->species_id,
            'species_name' => $item->species->name,
        ];
        });

        return [
            "error"   => false,
            "code"    => 200,
            "message" => $items->isEmpty()
                ? "No hay mascotas registradas para este plan familiar"
                : "Mascotas del plan familiar obtenidas exitosamente",
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
        $Pet = Pet::create($data);

        return [
            "error" => false,
            "code" => 201,
            "message" => "Mascota creada exitosamente",
            "data" => $Pet,
        ];
    }

    public function update(array $data, $id)
    {
        $Pet = Pet::find($id);

        if (!$Pet) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Mascota no encontrada",
            ];
        }

        $Pet->update($data);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Mascota actualizada exitosamente",
            "data" => $Pet,
        ];
    }

    public function partialUpdate(array $data, $id)
    {
        $Pet = Pet::find($id);

        if (!$Pet) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Mascota no encontrada",
            ];
        }

        $Pet->update($data);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Mascota actualizada parcialmente exitosamente",
            "data" => $Pet,
        ];
    }
    // metodo delete para eliminar una mascota y sus vacunas relacionadas
    public function delete($id)
    {
        try{
            $Pet = Pet::find($id);

            if (!$Pet) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "Mascota no encontrada",
                ];
            }
            return \DB::transaction(function () use ($Pet) {

                PetVaccine::where('pet_id', $Pet->id)->delete();
                $Pet->delete();

                return [
                    "error" => false,
                    "code" => 200,
                    "message" => "Mascota y sus vacunas relacionadas fueron eliminadas exitosamente",
                ];
            });
        } catch (\Exception $e) {
            return [
                "error" => true,
                "code" => 500,
                "message" => "Error al eliminar la mascota: " . $e->getMessage(),
            ];

        }
    }
}

