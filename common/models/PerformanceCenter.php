<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "performance_center".
 *
 * @property string $PerformanceCenterId
 * @property string $Description
 * @property integer $ValueCenterId
 * @property integer $IsActive
 *
 * @property ValueCenter $valueCenter
 * @property TradeProduct[] $tradeProducts
 */
class PerformanceCenter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'performance_center';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PerformanceCenterId'], 'required'],
            [['PerformanceCenterId', 'Description'], 'string'],
            [['ValueCenterId', 'IsActive'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PerformanceCenterId' => Yii::t('app', 'Performance Center ID'),
            'Description' => Yii::t('app', 'Description'),
            'ValueCenterId' => Yii::t('app', 'Value Center ID'),
            'IsActive' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueCenter()
    {
        return $this->hasOne(ValueCenter::className(), ['ValueCenterId' => 'ValueCenterId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTradeProducts()
    {
        return $this->hasMany(TradeProduct::className(), ['PerformanceCenterId' => 'PerformanceCenterId']);
    }
}
