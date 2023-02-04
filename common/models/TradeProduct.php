<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "trade_product".
 *
 * @property integer $TradeProductId
 * @property string $Description
 * @property string $PerformanceCenterId
 * @property string $Price
 * @property string $Profit
 * @property integer $IsForecastable
 * @property integer $IsActive
 *
 * @property ClientProduct[] $clientProducts
 * @property Gmid[] $gms
 * @property PerformanceCenter $performanceCenter
 */
class TradeProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'trade_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TradeProductId'], 'required'],
            [['Description', 'PerformanceCenterId'], 'string'],
            [['Price', 'Profit'], 'number'],
            [['TradeProductId','IsForecastable', 'IsActive','SendMail'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'TradeProductId' => Yii::t('app', 'Trade Product ID'),
            'Description' => Yii::t('app', 'Description'),
            'PerformanceCenterId' => Yii::t('app', 'Performance Center ID'),
            'Price' => Yii::t('app', 'Price'),
            'Profit' => Yii::t('app', 'Profit'),
            'IsForecastable' => Yii::t('app', 'Is Forecastable'),
            'IsActive' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientProducts()
    {
        return $this->hasMany(ClientProduct::className(), ['TradeProductId' => 'TradeProductId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGmids()
    {
        return $this->hasMany(Gmid::className(), ['TradeProductId' => 'TradeProductId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerformanceCenter()
    {
        return $this->hasOne(PerformanceCenter::className(), ['PerformanceCenterId' => 'PerformanceCenterId']);
    }
}
