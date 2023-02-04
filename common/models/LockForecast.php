<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lock_forecast".
 *
 * @property integer $LockId
 * @property string $DateFrom
 * @property string $DateTo
 */
class LockForecast extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lock_forecast';
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
