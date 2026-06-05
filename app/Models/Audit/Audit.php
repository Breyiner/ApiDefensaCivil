<?php

namespace App\Models\Audit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'rol_name',
        'date_time',
        'action_execute',
        'status_old',
        'status_new',
        'data_old',
        'data_new',
        'subData_old',
        'subData_new',
        'historiable_id',
        'historiable_type',
    ];

    public function historiable()
    {
        return $this->morphTo();
    }
}
