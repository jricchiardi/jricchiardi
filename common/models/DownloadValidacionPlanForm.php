<?php

namespace common\models;

class DownloadValidacionPlanForm extends ValidacionPlanForm
{
    public $pais;
    public $incluirConVolumenCero;

    public function rules()
    {
        $rules = parent::rules();
        return array_merge($rules, [
            [['pais', 'incluirConVolumenCero'], 'safe'],
            [['pais', 'incluirConVolumenCero'], 'required'],
        ]);
    }
}
