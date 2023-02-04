<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "client_product".
 *
 * @property integer $ClientProductId
 * @property integer $GmidId
 * @property integer $TradeProductId
 * @property integer $ClientId
 * @property integer $IsForecastable
 *
 * @property Client $client
 * @property Gmid $gmid
 * @property TradeProduct $tradeProduct
 * @property Forecast[] $forecasts
 * @property Campaign[] $campaigns
 * @property Plan[] $plans
 */
class ClientProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['GmidId', 'TradeProductId'], 'integer'],
            [['ClientId', 'IsForecastable'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ClientProductId' => Yii::t('app', 'Client Product ID'),
            'GmidId' => Yii::t('app', 'Gmid ID'),
            'TradeProductId' => Yii::t('app', 'Trade Product ID'),
            'ClientId' => Yii::t('app', 'Client ID'),
            'IsForecastable' => Yii::t('app', 'Is Forecastable'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['ClientId' => 'ClientId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGmid()
    {
        return $this->hasOne(Gmid::className(), ['GmidId' => 'GmidId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTradeProduct()
    {
        return $this->hasOne(TradeProduct::className(), ['TradeProductId' => 'TradeProductId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForecasts()
    {
        return $this->hasMany(Forecast::className(), ['ClientProductId' => 'ClientProductId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampaigns()
    {
        return $this->hasMany(Campaign::className(), ['CampaignId' => 'CampaignId'])->viaTable('plan', ['ClientProductId' => 'ClientProductId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlans()
    {
        return $this->hasMany(Plan::className(), ['ClientProductId' => 'ClientProductId']);
    }
}
