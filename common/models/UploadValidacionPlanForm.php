<?php

namespace common\models;

use yii\web\UploadedFile;

class UploadValidacionPlanForm extends ValidacionPlanForm
{
    /** @var UploadedFile */
    public $file;

    public function rules()
    {
        $rules = parent::rules();
        return array_merge($rules, [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls,xlsx'],
        ]);
    }
}
