<?php

namespace common\models\sap;

use yii\base\Model;
use yii\web\UploadedFile;

class ManualImport extends Model
{
    const TIPO_VENTAS = 'ventas';
    const TIPO_CYOS = 'cyos';
    const TIPO_OPEN_ORDERS = 'open orders';
	const TIPO_FC_NOCONT = 'fc nocont';
    const TIPO_DESP_NOFC = 'desp nofc';	
	const TIPO_FCASTIBP = 'Forecast IBP';
    const ORIGEN_DAS = 'das';
    const ORIGEN_DUPONT = 'dupont';
	const ORIGEN_DELIVORDS = 'delivords';
	const ORIGEN_DUPONT_SHORT = 'dupont short';
	const ORIGEN_DAS_SHORT = 'das short';
	const ORIGEN_ORDERS_CRED = 'orders cred';
	const ORIGEN_FCASTIBP = 'Forecast IBP';

    public $tipo;
    public $origen;

    /** @var UploadedFile */
    public $file;

    public function rules()
    {
        return [
            [['tipo', 'origen'], 'safe'],
            [['tipo', 'origen'], 'required'],
            ['tipoImport', 'in', 'range' => [self::TIPO_VENTAS, self::TIPO_CYOS, self::TIPO_OPEN_ORDERS, self::TIPO_FC_NOCONT, self::TIPO_DESP_NOFC, self::TIPO_FCASTIBP]],
            ['origen', 'in', 'range' => [self::ORIGEN_DAS, self::ORIGEN_DUPONT, self::ORIGEN_DELIVORDS, self::ORIGEN_ORDERS_CRED, self::ORIGEN_DUPONT_SHORT, self::ORIGEN_DAS_SHORT, self::ORIGEN_FCASTIBP]],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => ['csv', 'xlsx']],
        ];
    }
}
