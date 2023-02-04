<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "forecast".
 *
 * @property integer $ClientProductId
 * @property integer $CampaignId
 * @property integer $January
 * @property integer $February
 * @property integer $March
 * @property integer $Q1
 * @property integer $April
 * @property integer $May
 * @property integer $June
 * @property integer $Q2
 * @property integer $July
 * @property integer $August
 * @property integer $September
 * @property integer $Q3
 * @property integer $October
 * @property integer $November
 * @property integer $December
 * @property integer $Q4
 * @property integer $Total
 *
 * @property Campaign $campaign
 * @property ClientProduct $clientProduct
 */
class Forecast extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'forecast';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['ClientProductId', 'CampaignId'], 'required'],
            [['ClientProductId', 'CampaignId', 'January', 'February', 'March', 'Q1', 'April', 'May', 'June', 'Q2', 'July', 'August', 'September', 'Q3', 'October', 'November', 'December', 'Q4', 'Total'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ClientProductId' => Yii::t('app', 'Client Product ID'),
            'CampaignId' => Yii::t('app', 'Campaign ID'),
            'January' => Yii::t('app', 'January'),
            'February' => Yii::t('app', 'February'),
            'March' => Yii::t('app', 'March'),
            'Q1' => Yii::t('app', 'Q1'),
            'April' => Yii::t('app', 'April'),
            'May' => Yii::t('app', 'May'),
            'June' => Yii::t('app', 'June'),
            'Q2' => Yii::t('app', 'Q2'),
            'July' => Yii::t('app', 'July'),
            'August' => Yii::t('app', 'August'),
            'September' => Yii::t('app', 'September'),
            'Q3' => Yii::t('app', 'Q3'),
            'October' => Yii::t('app', 'October'),
            'November' => Yii::t('app', 'November'),
            'December' => Yii::t('app', 'December'),
            'Q4' => Yii::t('app', 'Q4'),
            'Total' => Yii::t('app', 'Total'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampaign() {
        return $this->hasOne(Campaign::className(), ['CampaignId' => 'CampaignId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientProduct() {
        return $this->hasOne(ClientProduct::className(), ['ClientProductId' => 'ClientProductId']);
    }

    public function calculateQuarters() {
        $this->Q1 = $this->January + $this->February + $this->March;
        $this->Q2 = $this->April + $this->May + $this->June;
        $this->Q3 = $this->July + $this->August + $this->September;
        $this->Q4 = $this->October + $this->November + $this->December;
        $this->Total = $this->Q1 + $this->Q2 + $this->Q3 + $this->Q4;
    }

    public function _setAttributes($values, $safeOnly = true) {
        if (is_array($values)) {
            $attributes = array_flip($safeOnly ? $this->safeAttributes() : $this->attributes());
            foreach ($values as $name => $value) {
                if (isset($attributes[$name]) && Yii::$app->utilcomponents->isMonthActive($name)) {
                    $this->$name = $value;
                } elseif ($safeOnly) {
                    $this->onUnsafeAttribute($name, $value);
                }
            }
        }
        $this->calculateQuarters();
    }

    public function getDashTableResume($dashBoardFilter) {
        $results = array();

        /* FORECAST + VTAS  */

        $resume = TableResume::find()->select(['CampaignId',
                    'SUM(Q1ForecastMoreSaleVolume) AS Q1ForecastMoreSaleVolume',
                    'SUM(Q2ForecastMoreSaleVolume) AS Q2ForecastMoreSaleVolume',
                    'SUM(Q3ForecastMoreSaleVolume) AS Q3ForecastMoreSaleVolume',
                    'SUM(Q4ForecastMoreSaleVolume) AS Q4ForecastMoreSaleVolume',
                    'SUM(Q1ForecastMoreSaleUSD) AS Q1ForecastMoreSaleUSD',
                    'SUM(Q2ForecastMoreSaleUSD) AS Q2ForecastMoreSaleUSD',
                    'SUM(Q3ForecastMoreSaleUSD) AS Q3ForecastMoreSaleUSD',
                    'SUM(Q4ForecastMoreSaleUSD) AS Q4ForecastMoreSaleUSD',
                    'SUM(Q1PlanVolume) AS Q1PlanVolume',
                    'SUM(Q2PlanVolume) AS Q2PlanVolume',
                    'SUM(Q3PlanVolume) AS Q3PlanVolume',
                    'SUM(Q4PlanVolume) AS Q4PlanVolume',
                    'SUM(Q1PlanUSD) AS Q1PlanUSD',
                    'SUM(Q2PlanUSD) AS Q2PlanUSD',
                    'SUM(Q3PlanUSD) AS Q3PlanUSD',
                    'SUM(Q4PlanUSD) AS Q4PlanUSD',
                    'SUM(TotalPlanVolume) AS TotalPlanVolume',
                    'SUM(TotalPlanUSD) AS TotalPlanUSD',
                    'SUM(TotalCyOVolume) AS TotalCyOVolume',
                    'SUM(TotalCyOUSD) AS TotalCyOUSD ',
                    'SUM(TotalForecastMoreSaleVolume) AS TotalForecastMoreSaleVolume',
                    'SUM(TotalForecastMoreSaleUSD) AS TotalForecastMoreSaleUSD'
                ])
                ->andFilterWhere(['SellerId' => $dashBoardFilter->SellerId,
                    'RsmId' => $dashBoardFilter->RsmId,
                    'DsmId' => $dashBoardFilter->DsmId,
                    'TradeProductId' => $dashBoardFilter->TradeProductId,
                    'PerformanceCenterId' => $dashBoardFilter->PerformanceCenterId,
                    'ValueCenterId' => $dashBoardFilter->ValueCenterId,
                    'CampaignId'  => $dashBoardFilter->CampaignId,
                    'ClientId'=>$dashBoardFilter->ClientId])
                ->groupBy(['CampaignId'])
                ->asArray()
                ->all();




        return isset($resume[0]) ? $resume[0] : [];
    }
	
	public function getTempGrafico1($dashBoardFilter) {
	
		$results = array();

        /* FORECAST + VTAS  */

        $resume = TempGraficoUno::find()->select(
            ['CampaignId',
                    'SUM(Q1ForecastMoreSaleVolume) AS Q1ForecastMoreSaleVolume',
                    'SUM(Q2ForecastMoreSaleVolume) AS Q2ForecastMoreSaleVolume',
                    'SUM(Q3ForecastMoreSaleVolume) AS Q3ForecastMoreSaleVolume',
                    'SUM(Q4ForecastMoreSaleVolume) AS Q4ForecastMoreSaleVolume',
                    'SUM(Q1ForecastMoreSaleUSD) AS Q1ForecastMoreSaleUSD',
                    'SUM(Q2ForecastMoreSaleUSD) AS Q2ForecastMoreSaleUSD',
                    'SUM(Q3ForecastMoreSaleUSD) AS Q3ForecastMoreSaleUSD',
                    'SUM(Q4ForecastMoreSaleUSD) AS Q4ForecastMoreSaleUSD',
                    'SUM(Q1PlanVolume) AS Q1PlanVolume',
                    'SUM(Q2PlanVolume) AS Q2PlanVolume',
                    'SUM(Q3PlanVolume) AS Q3PlanVolume',
                    'SUM(Q4PlanVolume) AS Q4PlanVolume',
                    'SUM(Q1PlanUSD) AS Q1PlanUSD',
                    'SUM(Q2PlanUSD) AS Q2PlanUSD',
                    'SUM(Q3PlanUSD) AS Q3PlanUSD',
                    'SUM(Q4PlanUSD) AS Q4PlanUSD',
                    'SUM(TotalPlanVolume) AS TotalPlanVolume',
                    'SUM(TotalPlanUSD) AS TotalPlanUSD',
                    'SUM(TotalCyOVolume) AS TotalCyOVolume',
                    'SUM(TotalCyOUSD) AS TotalCyOUSD ',
                    'SUM(TotalForecastMoreSaleVolume) AS TotalForecastMoreSaleVolume',
                    'SUM(TotalForecastMoreSaleUSD) AS TotalForecastMoreSaleUSD'
                ]
        )
		->andFilterWhere(['SellerId' => $dashBoardFilter->SellerId,
                    'RsmId' => $dashBoardFilter->RsmId,
                    'DsmId' => $dashBoardFilter->DsmId,
                    'TradeProductId' => $dashBoardFilter->TradeProductId,
                    'PerformanceCenterId' => $dashBoardFilter->PerformanceCenterId,
                    'ValueCenterId' => $dashBoardFilter->ValueCenterId,
                    'CampaignId'  => $dashBoardFilter->CampaignId,
                    'ClientId'=>$dashBoardFilter->ClientId])
        ->groupBy(['CampaignId'])
		->asArray()
        ->all();
		return isset($resume[0]) ? $resume[0] : [];
    }

    /* Report distribution */

    public function getDashDistribution($dashBoardFilter) {
        $results = array();

        // <--- inicio agregado--->
        $query1 = new \yii\db\Query();
        $sale = $query1->select(['SUM(totalSaleGraf)'])
                ->from('TEMP_GRAFICO_2')
        // <-- fin agregado --> 

        // $sale = Sale::find()->select(['SUM(Total)'])
                // ->innerJoin('gmid', 'gmid.GmidId = sale.GmidId')
                // ->innerJoin('trade_product', 'trade_product.TradeProductId = gmid.TradeProductId')
                // ->innerJoin('performance_center', 'performance_center.PerformanceCenterId = trade_product.PerformanceCenterId')
                // ->innerJoin('client_seller', 'client_seller.ClientId = sale.ClientId ')
                // ->innerJoin('user' . ' seller', 'seller.UserId=client_seller.SellerId')
                // ->innerJoin('user' . ' dsm', 'dsm.UserId = seller.ParentId')
                // ->innerJoin('user' . ' rsm', 'rsm.UserId = dsm.ParentId')

                ->andWhere(['CampaignId' => $dashBoardFilter->CampaignId]);


        // <--- inicio agregado--->

        $query2 = new \yii\db\Query();
        $forecast = $query2->select(['
            SUM(Q1) as Q1,
            SUM(Q2) AS Q2,
            SUM(Q3) AS Q3,
            SUM(Q4) AS Q4,
            CampaignId
        '])
        ->from('TEMP_GRAFICO_3')
        // <-- fin agregado --> 



        // $query = new \yii\db\Query();
        // $forecast = $query->select(['             
        //                             SUM(isnull(JanuarySaleForecastUSD,0))+ SUM(isnull(FebruarySaleForecastUSD,0)) + SUM(isnull(MarchSaleForecastUSD,0)) AS Q1,
        //                             SUM(isnull(AprilSaleForecastUSD,0))+ SUM(isnull(MaySaleForecastUSD,0)) + SUM(isnull(JuneSaleForecastUSD,0)) AS Q2,
        //                             SUM(isnull(JulySaleForecastUSD,0))+ SUM(isnull(AugustSaleForecastUSD,0)) + SUM(isnull(SeptemberSaleForecastUSD,0)) AS Q3,
        //                             SUM(isnull(OctoberSaleForecastUSD,0))+ SUM(isnull(NovemberSaleForecastUSD,0)) + SUM(isnull(DecemberSaleForecastUSD,0)) AS Q4
        //                             '])
        //         ->from('SaleWithForecast')
        //         ->innerJoin('trade_product', 'trade_product.TradeProductId = SaleWithForecast.TradeProductId')
        //         ->innerJoin('performance_center', 'performance_center.PerformanceCenterId = trade_product.PerformanceCenterId')
        //         ->innerJoin('client_seller', 'client_seller.ClientId = SaleWithForecast.ClientId ')
        //         ->innerJoin('user' . ' seller', 'seller.UserId=client_seller.SellerId')
        //         ->innerJoin('user' . ' dsm', 'dsm.UserId = seller.ParentId')
        //         ->innerJoin('user' . ' rsm', 'rsm.UserId = dsm.ParentId')


                ->andWhere(['CampaignId' => $dashBoardFilter->CampaignId]);


        // <--- inicio agregado--->
        $query3 = new \yii\db\Query();
        $cyo = $query3->select(['
            isnull(SUM(totalCyOGraf),0),
            CampaignId
        '])
        ->from('TEMP_GRAFICO_4')

        // <-- fin agregado --> 


        // $cyo = Cyo::find()->select(['isnull(SUM(cyo.InventoryBalance*gmid.Price),0)'])
        //         ->innerJoin('gmid', 'gmid.GmidId = cyo.GmidId')
        //         ->innerJoin('trade_product', 'trade_product.TradeProductId = gmid.TradeProductId')
        //         ->innerJoin('performance_center', 'performance_center.PerformanceCenterId = trade_product.PerformanceCenterId')
        //         ->innerJoin('client_seller', 'client_seller.ClientId = cyo.ClientId ')
        //         ->innerJoin('user' . ' seller', 'seller.UserId=client_seller.SellerId')
        //         ->innerJoin('user' . ' dsm', 'dsm.UserId = seller.ParentId')
        //         ->innerJoin('user' . ' rsm', 'rsm.UserId = dsm.ParentId')


                ->andWhere(['CampaignId' => $dashBoardFilter->CampaignId])
        ;
       

        if ($dashBoardFilter->TradeProductId) {
            $sale->andWhere(['TradeProductId' => $dashBoardFilter->TradeProductId]);
            $forecast->andWhere(['TradeProductId' => $dashBoardFilter->TradeProductId]);
            $cyo->andWhere(['TradeProductId' => $dashBoardFilter->TradeProductId]);
        }
        if ($dashBoardFilter->PerformanceCenterId) {
            $sale->andWhere(['PerformanceCenterId' => $dashBoardFilter->PerformanceCenterId]);
            $forecast->andWhere(['PerformanceCenterId' => $dashBoardFilter->PerformanceCenterId]);
            $cyo->andWhere(['PerformanceCenterId' => $dashBoardFilter->PerformanceCenterId]);
        }
        if ($dashBoardFilter->ValueCenterId) {
            $sale->andWhere(['ValueCenterId' => $dashBoardFilter->ValueCenterId]);
            $forecast->andWhere(['ValueCenterId' => $dashBoardFilter->ValueCenterId]);
            $cyo->andWhere(['ValueCenterId' => $dashBoardFilter->ValueCenterId]);
        }
        if ($dashBoardFilter->ClientId) {
            $sale->andWhere(['ClientId' => $dashBoardFilter->ClientId]);
            $forecast->andWhere(['ClientId' => $dashBoardFilter->ClientId]);
            $cyo->andWhere(['ClientId' => $dashBoardFilter->ClientId]);
        }
        if ($dashBoardFilter->SellerId) {
            $sale->andWhere(['UserIdSeller' => $dashBoardFilter->SellerId]);
            $forecast->andWhere(['UserIdSeller' => $dashBoardFilter->SellerId]);
            $cyo->andWhere(['UserIdSeller' => $dashBoardFilter->SellerId]);
        }
        if ($dashBoardFilter->DsmId) {
            $sale->andWhere(['UserIdDSM' => $dashBoardFilter->DsmId]);
            $forecast->andWhere(['UserIdDSM' => $dashBoardFilter->DsmId]);
            $cyo->andWhere(['UserIdDSM' => $dashBoardFilter->DsmId]);
        }
        if ($dashBoardFilter->RsmId) {
            $sale->andWhere(['UserIdRSM' => $dashBoardFilter->RsmId]);
            $forecast->andWhere(['UserIdRSM' => $dashBoardFilter->RsmId]);
            $cyo->andWhere(['UserIdRSM' => $dashBoardFilter->RsmId]);
        }
        
         // ESTAS SON LAS PIJAS QUE PIDEN A APURADAS
        
      
        
        $results['forecast'] = $forecast->groupBy(['CampaignId'])->one();        
      
      
        $valueForecast = $results['forecast']['Q1']+$results['forecast']['Q2']+$results['forecast']['Q3']+$results['forecast']['Q4'];
    
        if ($dashBoardFilter->QuarterId == 'Q1') {         
            $sale->andWhere(['in', '[Month]', [1, 2, 3]]);
            $valueForecast =    $results['forecast']['Q1'];
        }
        if ($dashBoardFilter->QuarterId == 'Q2') {
            $sale->andWhere(['in', 'Month', [4, 5, 6]]);
            $valueForecast =    $results['forecast']['Q2'];
        }
        if ($dashBoardFilter->QuarterId == 'Q3') {
            $sale->andWhere(['in', 'Month', [7, 8, 9]]);
            $valueForecast =    $results['forecast']['Q3'];
        }
        if ($dashBoardFilter->QuarterId == 'Q4') {
            $sale->andWhere(['in', 'Month', [10, 11, 12]]);
            $valueForecast =    $results['forecast']['Q4'];
        }
      
        $results['cyo'] = $cyo->groupBy(['CampaignId'])->scalar();
        $results['cyo'] = ($results['cyo']) ? $results['cyo'] : 0;
        
        $finalValue = $valueForecast - $results['cyo'];
        
        if($finalValue>=0)
        {
           $results['forecast'] = $finalValue;
        }
        else
        {
            $results['cyo'] = $valueForecast;
            $results['forecast'] = 0;
        }
 
        
        $results['sale'] = $sale->groupBy(['CampaignId'])->scalar();
        $results['sale'] = ($results['sale']) ? $results['sale'] : 0;
        
        // formating
        $results['sale'] = floatval($results['sale']);
        $results['forecast'] = floatval ($results['forecast']) - floatval($results['sale']);
        $results['cyo'] = floatval ($results['cyo']);
  
        
        return $results;
    }

    public function getDashProfit($dashBoardFilter) {

        $profit = Sale::find()->select(['SUM(gmid.Profit * sale.Total) / SUM(sale.Total)'])
                ->innerJoin('client', 'client.ClientId = sale.ClientId')
                ->innerJoin('client_seller', 'client_seller.ClientId = sale.ClientId')
                ->innerJoin('gmid', 'gmid.GmidId = sale.GmidId')
                ->innerJoin('user' . ' seller', 'seller.UserId = client_seller.SellerId')
                ->innerJoin('user' . ' dsm', 'seller.ParentId = dsm.UserId')
                ->innerJoin('user' . ' rsm', 'rsm.UserId = dsm.ParentId')
                ->andWhere(['CampaignId' => $dashBoardFilter->CampaignId])
        ;
        if ($dashBoardFilter->SellerId) {
            $profit->andWhere(['seller.UserId' => $dashBoardFilter->SellerId]);
        }

        if ($dashBoardFilter->DsmId) {
            $profit->andWhere(['dsm.UserId' => $dashBoardFilter->DsmId]);
        }

        if ($dashBoardFilter->RsmId) {
            $profit->andWhere(['rsm.UserId' => $dashBoardFilter->RsmId]);
        }

        return ($profit->scalar() * 100);
    }

}
