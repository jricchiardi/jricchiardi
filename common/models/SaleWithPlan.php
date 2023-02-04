<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "SaleWithPlan".
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
 * @property integer $PlanQ1
 * @property integer $PlanQ2
 * @property integer $PlanQ3
 * @property integer $PlanQ4
 * @property integer $PlanTotal
 * @property string $PlanDescription
 * @property string $PlanPrice
 * @property string $Client
 * @property integer $Q1
 * @property integer $Q2
 * @property integer $Q3
 * @property integer $Q4
 * @property integer $Total
 */
class SaleWithPlan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SaleWithPlan';
    }

//    public function getPrimaryKey($asArray = false)
//    {
//       return ['CampaignId','ClientProductId'];
//    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [            
            [['ClientProductId', 'CampaignId', 'ClientId', 'IsForecastable', 'SellerId', 'ValueCenterId', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'PlanQ1', 'PlanQ2', 'PlanQ3', 'PlanQ4', 'PlanTotal', 'Q1', 'Q2', 'Q3', 'Q4', 'Total','GroupId'], 'integer'],
            [['SellerName', 'TradeProductId', 'TradeProduct', 'GmidId', 'GmidDescription', 'PerformanceCenterId', 'PerformanceCenter', 'ValueCenter', 'PlanDescription', 'Client'], 'string'],
            [['TradeProductPrice', 'TradeProductProfit', 'GmidPrice', 'GmidProfit', 'PlanPrice'], 'number'],
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
            'PlanQ1' => Yii::t('app', 'Plan Q1'),
            'PlanQ2' => Yii::t('app', 'Plan Q2'),
            'PlanQ3' => Yii::t('app', 'Plan Q3'),
            'PlanQ4' => Yii::t('app', 'Plan Q4'),
            'PlanTotal' => Yii::t('app', 'Plan Total'),
            'PlanDescription' => Yii::t('app', 'Plan Description'),
            'PlanPrice' => Yii::t('app', 'Plan Price'),
            'Client' => Yii::t('app', 'Client'),
            'Q1' => Yii::t('app', 'Q1'),
            'Q2' => Yii::t('app', 'Q2'),
            'Q3' => Yii::t('app', 'Q3'),
            'Q4' => Yii::t('app', 'Q4'),
            'Total' => Yii::t('app', 'Total'),
        ];
    }
}
