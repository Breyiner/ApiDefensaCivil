<?php

namespace App\Models\ActionPlan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RiskFactor\RiskFactor;
use App\Models\Member\Member;
use App\Models\ActionPlanAction\ActionPlanAction;

class ActionPlan extends Model
{
    use HasFactory;

    protected $fillable = ['member_id', 'risk_factor_id',];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function riskFactor()
    {
        return $this->belongsTo(RiskFactor::class, 'risk_factor_id');
    }

    public function actionPlanAction()
    {
        return $this->hasMany(ActionPlanAction::class, 'action_plan_id');
    }
}
