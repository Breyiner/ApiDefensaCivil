<?php

namespace App\Http\Requests\Audit;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valida operaciones masivas que solo requieren IDs de auditoría.
 *
 * 🔹 Reutilizable para: bulkDestroy (Eliminación masiva de historial)
 */
class BulkAuditIdsRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Cambiado a true para permitir el flujo de la validación
        return true;
    }

    public function rules(): array
    {
        return [
            'audit_ids'   => 'required|array|min:1',
            'audit_ids.*' => 'exists:audits,id',
        ];
    }

    public function messages(): array
    {
        return [
            'audit_ids.required' => 'Debe enviar al menos un registro de actividad.',
            'audit_ids.array'    => 'Los registros de actividad deben ser un arreglo.',
            'audit_ids.*.exists' => 'Uno de los registros de actividad seleccionados no es válido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'audit_ids' => 'registros de actividad',
        ];
    }
}