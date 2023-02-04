<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "SaleWithForecast".
 *
 * @property integer $ClientProductId
 * @property integer $CampaignId
 * @property integer $ClientId
 * @property integer $IsForecastable
 * @property integer $SellerId
 * @property string $SellerName
 * @property integer $TradeProductId
 * @property string $TradeProduct
 * @property string $TradeProductPrice
 * @property string $TradeProductProfit
 * @property integer $GmidId
 * @property string $GmidDescription
 * @property string $GmidPrice
 * @property string $GmidProfit
 * @property string $PerformanceCenterId
 * @property string $PerformanceCenter
 * @property integer $ValueCenterId
 * @property string $ValueCenter
 * @property integer $ForecastJanuary
 * @property integer $ForecastFebruary
 * @property integer $ForecastMarch
 * @property integer $ForecastApril
 * @property integer $ForecastMay
 * @property integer $ForecastJune
 * @property integer $ForecastJuly
 * @property integer $ForecastAugust
 * @property integer $ForecastSeptember
 * @property integer $ForecastOctober
 * @property integer $ForecastNovember
 * @property integer $ForecastDecember
 * @property integer $ForecastQ1
 * @property integer $ForecastQ2
 * @property integer $ForecastQ3
 * @property integer $ForecastQ4
 * @property integer $ForecastTotal
 * @property integer $SaleJanuary
 * @property integer $SaleFebruary
 * @property integer $SaleMarch
 * @property integer $SaleApril
 * @property integer $SaleMay
 * @property integer $SaleJune
 * @property integer $SaleJuly
 * @property integer $SaleAugust
 * @property integer $SaleSeptember
 * @property integer $SaleOctober
 * @property integer $SaleNovember
 * @property integer $SaleDecember
 * @property integer $January
 * @property integer $February
 * @property integer $March
 * @property integer $April
 * @property integer $May
 * @property integer $June
 * @property integer $July
 * @property integer $August
 * @property integer $September
 * @property integer $October
 * @property integer $November
 * @property integer $December
 * @property string $ForecastDescription
 * @property string $ForecastPrice
 * @property string $Client
 * @property integer $Q1
 * @property integer $Q2
 * @property integer $Q3
 * @property integer $Q4
 * @property integer $Total
 */
class SaleWithForecast extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SaleWithForecast';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ClientProductId', 'CampaignId', 'SellerId', 'TradeProductId', 'PerformanceCenterId', 'ValueCenterId', 'ValueCenter', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'Client'], 'required'],
            [['ClientProductId', 'CampaignId', 'ClientId', 'IsForecastable', 'SellerId', 'ValueCenterId', 'ForecastJanuary', 'ForecastFebruary', 'ForecastMarch', 'ForecastApril', 'ForecastMay', 'ForecastJune', 'ForecastJuly', 'ForecastAugust', 'ForecastSeptember', 'ForecastOctober', 'ForecastNovember', 'ForecastDecember', 'ForecastQ1', 'ForecastQ2', 'ForecastQ3', 'ForecastQ4', 'ForecastTotal', 'SaleJanuary', 'SaleFebruary', 'SaleMarch', 'SaleApril', 'SaleMay', 'SaleJune', 'SaleJuly', 'SaleAugust', 'SaleSeptember', 'SaleOctober', 'SaleNovember', 'SaleDecember', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'Q1', 'Q2', 'Q3', 'Q4', 'Total'], 'integer'],
            [['SellerName', 'TradeProductId', 'TradeProduct', 'GmidId', 'GmidDescription', 'PerformanceCenterId', 'PerformanceCenter', 'ValueCenter', 'ForecastDescription', 'Client'], 'string'],
            [['TradeProductPrice', 'TradeProductProfit', 'GmidPrice', 'GmidProfit', 'ForecastPrice'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ClientProductId' => Yii::t('app', 'Client Product ID'),
            'CampaignId' => Yii::t('app', 'Campaign ID'),
            'ClientId' => Yii::t('app', 'Client ID'),
            'IsForecastable' => Yii::t('app', 'Is Forecastable'),
            'SellerId' => Yii::t('app', 'Seller ID'),
            'SellerName' => Yii::t('app', 'Seller Name'),
            'TradeProductId' => Yii::t('app', 'Trade Product ID'),
            'TradeProduct' => Yii::t('app', 'Trade Product'),
            'TradeProductPrice' => Yii::t('app', 'Trade Product Price'),
            'TradeProductProfit' => Yii::t('app', 'Trade Product Profit'),
            'GmidId' => Yii::t('app', 'Gmid ID'),
            'GmidDescription' => Yii::t('app', 'Gmid Description'),
            'GmidPrice' => Yii::t('app', 'Gmid Price'),
            'GmidProfit' => Yii::t('app', 'Gmid Profit'),
            'PerformanceCenterId' => Yii::t('app', 'Performance Center ID'),
            'PerformanceCenter' => Yii::t('app', 'Performance Center'),
            'ValueCenterId' => Yii::t('app', 'Value Center ID'),
            'ValueCenter' => Yii::t('app', 'Value Center'),
            'ForecastJanuary' => Yii::t('app', 'Forecast January'),
            'ForecastFebruary' => Yii::t('app', 'Forecast February'),
            'ForecastMarch' => Yii::t('app', 'Forecast March'),
            'ForecastApril' => Yii::t('app', 'Forecast April'),
            'ForecastMay' => Yii::t('app', 'Forecast May'),
            'ForecastJune' => Yii::t('app', 'Forecast June'),
            'ForecastJuly' => Yii::t('app', 'Forecast July'),
            'ForecastAugust' => Yii::t('app', 'Forecast August'),
            'ForecastSeptember' => Yii::t('app', 'Forecast September'),
            'ForecastOctober' => Yii::t('app', 'Forecast October'),
            'ForecastNovember' => Yii::t('app', 'Forecast November'),
            'ForecastDecember' => Yii::t('app', 'Forecast December'),
            'ForecastQ1' => Yii::t('app', 'Forecast Q1'),
            'ForecastQ2' => Yii::t('app', 'Forecast Q2'),
            'ForecastQ3' => Yii::t('app', 'Forecast Q3'),
            'ForecastQ4' => Yii::t('app', 'Forecast Q4'),
            'ForecastTotal' => Yii::t('app', 'Forecast Total'),
            'SaleJanuary' => Yii::t('app', 'Sale January'),
            'SaleFebruary' => Yii::t('app', 'Sale February'),
            'SaleMarch' => Yii::t('app', 'Sale March'),
            'SaleApril' => Yii::t('app', 'Sale April'),
            'SaleMay' => Yii::t('app', 'Sale May'),
            'SaleJune' => Yii::t('app', 'Sale June'),
            'SaleJuly' => Yii::t('app', 'Sale July'),
            'SaleAugust' => Yii::t('app', 'Sale August'),
            'SaleSeptember' => Yii::t('app', 'Sale September'),
            'SaleOctober' => Yii::t('app', 'Sale October'),
            'SaleNovember' => Yii::t('app', 'Sale November'),
            'SaleDecember' => Yii::t('app', 'Sale December'),
            'January' => Yii::t('app', 'January'),
            'February' => Yii::t('app', 'February'),
            'March' => Yii::t('app', 'March'),
            'April' => Yii::t('app', 'April'),
            'May' => Yii::t('app', 'May'),
            'June' => Yii::t('app', 'June'),
            'July' => Yii::t('app', 'July'),
            'August' => Yii::t('app', 'August'),
            'September' => Yii::t('app', 'September'),
            'October' => Yii::t('app', 'October'),
            'November' => Yii::t('app', 'November'),
            'December' => Yii::t('app', 'December'),
            'ForecastDescription' => Yii::t('app', 'Forecast Description'),
            'ForecastPrice' => Yii::t('app', 'Forecast Price'),
            'Client' => Yii::t('app', 'Client'),
            'Q1' => Yii::t('app', 'Q1'),
            'Q2' => Yii::t('app', 'Q2'),
            'Q3' => Yii::t('app', 'Q3'),
            'Q4' => Yii::t('app', 'Q4'),
            'Total' => Yii::t('app', 'Total'),
        ];
    }
}
