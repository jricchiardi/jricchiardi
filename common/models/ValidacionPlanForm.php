<?php

namespace common\models;

use yii\base\Model;

class ValidacionPlanForm extends Model
{
    const TIPO_PLAN_ACTUAL = 'actual';
    const TIPO_PLAN_FUTURO = 'futuro';

    public $plan;

    public function rules()
    {
        return [
            [['plan'], 'safe'],
            [['plan'], 'required'],
            ['plan', 'in', 'range' => [self::TIPO_PLAN_ACTUAL, self::TIPO_PLAN_FUTURO]],
        ];
    }
}