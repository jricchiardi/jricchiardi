<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "TableResume".
 *
 * @property integer $CampaignId
 * @property string $SellerName
 * @property integer $DsmId
 * @property integer $RsmId
 * @property integer $TradeProductId
 * @property string $PerformanceCenterId
 * @property integer $ValueCenterId
 * @property integer $Q1ForecastMoreSaleVolume
 * @property integer $Q2ForecastMoreSaleVolume
 * @property integer $Q3ForecastMoreSaleVolume
 * @property integer $Q4ForecastMoreSaleVolume
 * @property string $Q1ForecastMoreSaleUSD
 * @property string $Q2ForecastMoreSaleUSD
 * @property string $Q3ForecastMoreSaleUSD
 * @property string $Q4ForecastMoreSaleUSD
 * @property integer $Q1PlanVolume
 * @property integer $Q2PlanVolume
 * @property integer $Q3PlanVolume
 * @property integer $Q4PlanVolume
 * @property string $Q1PlanUSD
 * @property string $Q2PlanUSD
 * @property string $Q3PlanUSD
 * @property string $Q4PlanUSD
 * @property integer $TotalPlanVolume
 * @property string $TotalPlanUSD
 * @property string $TotalCyOVolume
 * @property string $TotalCyOUSD
 * @property integer $TotalForecastMoreSaleVolume
 * @property string $TotalForecastMoreSaleUSD
 * @property integer $Profit
 */
class TableResume extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'TableResume';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CampaignId', 'DsmId', 'RsmId', 'TradeProductId', 'ValueCenterId', 'Q1ForecastMoreSaleVolume', 'Q2ForecastMoreSaleVolume', 'Q3ForecastMoreSaleVolume', 'Q4ForecastMoreSaleVolume', 'Q1PlanVolume', 'Q2PlanVolume', 'Q3PlanVolume', 'Q4PlanVolume', 'TotalPlanVolume', 'TotalForecastMoreSaleVolume', 'Profit'], 'integer'],
            [['SellerName', 'PerformanceCenterId'], 'string'],
            [['DsmId', 'RsmId', 'TradeProductId', 'PerformanceCenterId'], 'required'],
            [['Q1ForecastMoreSaleUSD', 'Q2ForecastMoreSaleUSD', 'Q3ForecastMoreSaleUSD', 'Q4ForecastMoreSaleUSD', 'Q1PlanUSD', 'Q2PlanUSD', 'Q3PlanUSD', 'Q4PlanUSD', 'TotalPlanUSD', 'TotalCyOVolume', 'TotalCyOUSD', 'TotalForecastMoreSaleUSD'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CampaignId' => Yii::t('app', 'Campaign ID'),
            'SellerName' => Yii::t('app', 'Seller Name'),
            'DsmId' => Yii::t('app', 'Dsm ID'),
            'RsmId' => Yii::t('app', 'Rsm ID'),
            'TradeProductId' => Yii::t('app', 'Trade Product ID'),
            'PerformanceCenterId' => Yii::t('app', 'Performance Center ID'),
            'ValueCenterId' => Yii::t('app', 'Value Center ID'),
            'Q1ForecastMoreSaleVolume' => Yii::t('app', 'Q1 Forecast More Sale Volume'),
            'Q2ForecastMoreSaleVolume' => Yii::t('app', 'Q2 Forecast More Sale Volume'),
            'Q3ForecastMoreSaleVolume' => Yii::t('app', 'Q3 Forecast More Sale Volume'),
            'Q4ForecastMoreSaleVolume' => Yii::t('app', 'Q4 Forecast More Sale Volume'),
            'Q1ForecastMoreSaleUSD' => Yii::t('app', 'Q1 Forecast More Sale Usd'),
            'Q2ForecastMoreSaleUSD' => Yii::t('app', 'Q2 Forecast More Sale Usd'),
            'Q3ForecastMoreSaleUSD' => Yii::t('app', 'Q3 Forecast More Sale Usd'),
            'Q4ForecastMoreSaleUSD' => Yii::t('app', 'Q4 Forecast More Sale Usd'),
            'Q1PlanVolume' => Yii::t('app', 'Q1 Plan Volume'),
            'Q2PlanVolume' => Yii::t('app', 'Q2 Plan Volume'),
            'Q3PlanVolume' => Yii::t('app', 'Q3 Plan Volume'),
            'Q4PlanVolume' => Yii::t('app', 'Q4 Plan Volume'),
            'Q1PlanUSD' => Yii::t('app', 'Q1 Plan Usd'),
            'Q2PlanUSD' => Yii::t('app', 'Q2 Plan Usd'),
            'Q3PlanUSD' => Yii::t('app', 'Q3 Plan Usd'),
            'Q4PlanUSD' => Yii::t('app', 'Q4 Plan Usd'),
            'TotalPlanVolume' => Yii::t('app', 'Total Plan Volume'),
            'TotalPlanUSD' => Yii::t('app', 'Total Plan Usd'),
            'TotalCyOVolume' => Yii::t('app', 'Total Cy Ovolume'),
            'TotalCyOUSD' => Yii::t('app', 'Total Cy Ousd'),
            'TotalForecastMoreSaleVolume' => Yii::t('app', 'Total Forecast More Sale Volume'),
            'TotalForecastMoreSaleUSD' => Yii::t('app', 'Total Forecast More Sale Usd'),
            'Profit' => Yii::t('app', 'Profit'),
        ];
    }
}
