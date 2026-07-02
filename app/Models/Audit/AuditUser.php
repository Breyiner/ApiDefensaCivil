<?php

namespace App\Models\Audit;

use Illuminate\Database\Eloquent\Model;

class AuditUser extends Model
{
    protected $fillable = [
        'user_name',
        'rol_name',
        'date_time',
        'action_execute',
        'status_old',
        'status_new',

        'userName_old',
        'userName_new',

        'lastName_old',
        'lastName_new',

        'userRol_old',
        'userRol_new',

        'documentType_old',
        'documentType_new',

        'numberDocument_old',
        'numberDocument_new',

        'birthDate_old',
        'birthDate_new',

        'gender_old',
        'gender_new',

        'sectional_old',
        'sectional_new',

        'organization_old',
        'organization_new',

        'historiable_id',
        'historiable_type',
    ];

    public function historiable()
    {
        return $this->morphTo();
    }
}
