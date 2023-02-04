<?php

namespace common\models\sap;

use yii\base\Model;
use yii\web\UploadedFile;

class ManualImport extends Model
{
    const TIPO_VENTAS = 'ventas';
    const TIPO_CYOS = 'cyos';
    const ORIGEN_DAS = 'das';
    const ORIGEN_DUPONT = 'dupont';

    public $tipo;
    public $origen;

    /** @var UploadedFile */
    public $file;

    public function rules()
    {
        return [
            [['tipo', 'origen'], 'safe'],
            [['tipo', 'origen'], 'required'],
            ['tipoImport', 'in', 'range' => [self::TIPO_VENTAS, self::TIPO_CYOS]],
            ['origen', 'in', 'range' => [self::ORIGEN_DAS, self::ORIGEN_DUPONT]],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => ['csv', 'xlsx']],
        ];
    }
}
