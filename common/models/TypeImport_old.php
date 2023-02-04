<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "type_import".
 *
 * @property integer $TypeImportId
 * @property string $Name
 *
 * @property Import[] $imports
 */
class TypeImport extends ActiveRecord
{
    const PRODUCT = 1;
    const CLIENT = 2;
    const SALE = 3;
    const OFFLINE = 4;
    const SETTING = 5;
    const CyO = 6;
    const PLAN = 7;
    const OPPORTUNITY = 9;
    const SELLING_OUT = 10;
    const AUTOMATIC_DAS_SALE = 11;
    const AUTOMATIC_DAS_CYO = 12;
    const AUTOMATIC_DUPONT_SALE = 13;
    const AUTOMATIC_DUPONT_CYO = 14;
    const UNIFICACION_CLIENTE = 15;
    const FORECAST_MARKETING = 16;
    const ASSOCIATION_PM_PRODUCT = 17;
    const CLIENT_MARKETING = 18;
    const CLIENT_PIONEER = 19;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'type_import';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name'], 'required'],
            [['Name'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'TypeImportId' => Yii::t('app', 'Type Import ID'),
            'Name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getImports()
    {
        return $this->hasMany(Import::className(), ['TypeImportId' => 'TypeImportId']);
    }

    /**
     * @return array
     */
    public static function getValidTypeImportsForSapAutomaticImport()
    {
        return [
            self::AUTOMATIC_DAS_SALE => "DAS Sales",
            self::AUTOMATIC_DAS_CYO => "DAS CyOs",
            self::AUTOMATIC_DUPONT_SALE => "DUPONT Sales",
            self::AUTOMATIC_DUPONT_CYO => "DUPONT CyOs",
        ];
    }
}
