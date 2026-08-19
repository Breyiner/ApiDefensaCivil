<?php

namespace App\Services\Member;

use App\Models\Member\Member;
use App\Models\FamilyMember\FamilyMember;
use App\Models\ConditionMember\ConditionMember;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MemberService
{
    public function getAll()
    {
        $members = Member::all();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Miembros obtenidos exitosamente",
            "data" => $members,
        ];
    }

    public function getById($id)
    {
        $member = Member::with(['bloodGroup', 'documentType', 'kinship', 'gender', 'nationality'])->find($id);
        $conditionMember = $member->conditionMember;

        if (!$member) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Miembro no encontrado",
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Miembro obtenido exitosamente",
            "data" => $member,
            $conditionMember,
        ];
    }

    public function getMembersForPlan($family_plan_id)
    {
        $paginator = FamilyMember::where('family_plan_id', $family_plan_id)
            ->with(['member.bloodGroup', 'member.documentType', 'member.kinship', 'member.eps'])
            ->paginate(10);

        // Transformar aunque esté vacío (no rompe)
        $items = $paginator->getCollection()->transform(function ($item) {
            return [
                'id'               => $item->member->id,
                'full_name'        => $item->member->names . ' ' . $item->member->last_names,
                'birth_date'       => $item->member->birth_date,
                'blood_group'      => $item->member->bloodGroup->name,
                'document_acronym' => $item->member->documentType->acronym,
                'document_number'  => $item->member->document_number,
                'gender_id'        => $item->member->gender_id,
                'gender'           => $item->member->gender->name,
                'kinship'          => $item->member->kinship->name,
                'phone'            => $item->member->phone,
                'nationality'      => $item->member->nationality->name,
                'eps'              => $item->member->eps->name
            ];
        });

        return [
            "error"   => false,
            "code"    => 200,
            "message" => $items->isEmpty()
                ? "Este plan familiar no tiene miembros registrados"
                : "Miembros del plan familiar obtenidos exitosamente",
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

    public function getMembersSelect($family_plan_id)
    {
        $collection = FamilyMember::where('family_plan_id', $family_plan_id)->with(['member.kinship'])->get();

        $items = $collection->transform(function ($item) {
            return [
                'id'              => $item->member->id,
                'full_name'       => $item->member->names . ' ' . $item->member->last_names,
                'document_number' => $item->member->document_number,
                'kinship'         => $item->member->kinship->name,
            ];
        });

        return [
            "error"   => false,
            "code"    => 200,
            "message" => "Miembros del plan familiar obtenidos exitosamente",
            "data"    => $items,
        ];
    }

    public function create(array $data, $plan_id)
    {
        DB::beginTransaction();

        try {
            // Validar cabeza de familia
            $existingHeads = FamilyMember::where('family_plan_id', $plan_id)
                ->whereHas('member', fn($q) => $q->where('kinship_id', 1))
                ->count();

            if ($existingHeads === 0 && ($data['kinship_id'] ?? 0) != 1) {
                DB::rollBack();
                return [
                    "error" => true,
                    "code" => 400,
                    "message" => "El primer integrante del plan debe ser cabeza de familia",
                ];
            }

            if ($existingHeads > 0 && ($data['kinship_id'] ?? 0) == 1) {
                DB::rollBack();
                return [
                    "error" => true,
                    "code" => 400,
                    "message" => "Ya existe un miembro con rol cabeza de familia, no se puede duplicar",
                ];
            }

            // 🔥 Validar que el cabeza de familia sea mayor de edad
            if (($data['kinship_id'] ?? 0) == 1) {

                if (empty($data['birth_date'])) {
                    DB::rollBack();
                    return [
                        "error" => true,
                        "code" => 400,
                        "message" => "La fecha de nacimiento es obligatoria para el cabeza de familia",
                    ];
                }

                $age = Carbon::parse($data['birth_date'])->age;

                if ($age < 18) {
                    DB::rollBack();
                    return [
                        "error" => true,
                        "code" => 400,
                        "message" => "El cabeza de familia debe ser mayor de edad",
                    ];
                }
            }

            $member = Member::create($data);

            FamilyMember::create([
                'member_id'      => $member->id,
                'family_plan_id' => $plan_id,
            ]);

            DB::commit();

            return [
                "error" => false,
                "code" => 201,
                "message" => "Miembro creado y asociado al plan familiar exitosamente",
                "data" => $member,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            return [
                "error" => true,
                "code" => 500,
                "message" => "Error al crear el miembro",
                "details" => $e->getMessage(),
            ];
        }
    }

    public function update(array $data, $id)
    {
        // 1️⃣ Buscar relación del plan familiar
        $familyMember = FamilyMember::where('member_id', $id)->first();

        if (!$familyMember) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Plan familiar no asociado al miembro"
            ];
        }

        // 2️⃣ Buscar miembro
        $member = Member::find($id);

        if (!$member) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Miembro no encontrado"
            ];
        }

        // Obtener valores nuevos o actuales
        $newKinship   = $data['kinship_id'] ?? $member->kinship_id;
        $newBirthDate = $data['birth_date'] ?? $member->birth_date;

        // 🔥 Validar mayoría de edad si será cabeza de familia
        if ($newKinship == 1) {

            if (empty($newBirthDate)) {
                return [
                    "error" => true,
                    "code" => 400,
                    "message" => "La fecha de nacimiento es obligatoria para el cabeza de familia"
                ];
            }

            $age = Carbon::parse($newBirthDate)->age;

            if ($age < 18) {
                return [
                    "error" => true,
                    "code" => 400,
                    "message" => "El cabeza de familia debe ser mayor de edad"
                ];
            }
        }

        // 🔥 Si se intenta asignar como cabeza, modificar al anterior
        if ($newKinship == 1 && $member->kinship_id != 1) {

            $existingHead = FamilyMember::where('family_plan_id', $familyMember->family_plan_id)
                ->whereHas('member', fn($q) => $q->where('kinship_id', 1))
                ->where('member_id', '!=', $id)
                ->first();

            if ($existingHead) {
                $prevHead = Member::find($existingHead->member_id);
                $prevHead->update(['kinship_id' => 17]);
            }
        }

        // 🔥 Evitar quitar cabeza sin reasignar
        if (
            $member->kinship_id == 1 &&
            isset($data['kinship_id']) &&
            $data['kinship_id'] != 1
        ) {
            return [
                "error" => true,
                "code" => 400,
                "message" => "No se puede quitar el rol de cabeza sin asignarlo a otro miembro"
            ];
        }

        // 4️⃣ Actualizar
        $member->update($data);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Miembro actualizado exitosamente",
            "data" => $member,
        ];
    }


    public function partialUpdate(array $data, $id)
    {
        return $this->update($data, $id); // misma lógica que update
    }

    public function delete($member_id)
    {
        try {
            // 1️⃣ Buscar miembro con relaciones
            $member = Member::with([
                'familyMember',
                'conditionMember',
                'actionPlan',
                'riskReductionActions'
            ])->find($member_id);

            if (!$member) {
                return [
                    "error"   => true,
                    "code"    => 404,
                    "message" => "Miembro no encontrado"
                ];
            }

            // 2️⃣ Buscar FamilyMember relacionado
            $familyMember = $member->familyMember()->first();

            if (!$familyMember) {
                return [
                    "error"   => true,
                    "code"    => 404,
                    "message" => "Miembro del plan familiar no encontrado"
                ];
            }

            // 3️⃣ Ejecutar validaciones
            $validationError = $this->validateMemberDeletion($member, $familyMember);
            if ($validationError) {
                return $validationError;
            }

            // 4️⃣ Eliminar en transacción
            DB::transaction(function () use ($familyMember, $member) {
                $familyMember->delete();
                $member->delete();
            });

            return [
                "error"   => false,
                "code"    => 200,
                "message" => "Miembro eliminado exitosamente"
            ];
        } catch (\Exception $e) {
            return [
                "error"   => true,
                "code"    => 500,
                "message" => "Error al procesar la eliminación del miembro"
            ];
        }
    }

    /**
     * Valida si el miembro puede ser eliminado.
     */
    private function validateMemberDeletion($member, $familyMember)
    {
        // Validación 1: Cabeza de familia
        $totalMembers = FamilyMember::where('family_plan_id', $familyMember->family_plan_id)->count();
        if ($member->kinship_id == 1 && $totalMembers > 1) {
            return [
                "error"   => true,
                "code"    => 400,
                "message" => "No se puede eliminar al cabeza de familia mientras existan otros integrantes. Debe asignar esta posición a otro miembro primero."
            ];
        }

        // Validación 2: Condiciones/afecciones
        if ($member->conditionMember()->exists()) {
            return [
                "error"   => true,
                "code"    => 400,
                "message" => "No se puede eliminar porque el miembro tiene condiciones o afecciones registradas."
            ];
        }

        // Validación 3: Acciones del plan
        if ($member->actionPlan()->exists()) {
            return [
                "error"   => true,
                "code"    => 400,
                "message" => "No se puede eliminar porque el miembro está relacionado con acciones del plan de acción."
            ];
        }

        // Validación 4: Acciones de reducción de riesgo
        if ($member->riskReductionActions()->exists()) {
            return [
                "error"   => true,
                "code"    => 400,
                "message" => "No se puede eliminar porque el miembro está relacionado con acciones de reducción de riesgo."
            ];
        }

        return null; // Sin errores
    }
}
