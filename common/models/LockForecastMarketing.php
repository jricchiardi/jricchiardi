<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "lock_forecast".
 *
 * @property integer $LockId
 * @property string $DateFrom
 * @property string $DateTo
 */
class LockForecastMarketing extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lock_forecast_marketing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DateFrom', 'DateTo'], 'required'],
            [['DateFrom', 'DateTo'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'LockId' => Yii::t('app', 'Lock ID'),
            'DateFrom' => Yii::t('app', 'Date From'),
            'DateTo' => Yii::t('app', 'Date To'),
        ];
    }
}
