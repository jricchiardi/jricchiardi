<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sale".
 *
 * @property integer $ClientId
 * @property integer $GmidId
 * @property integer $Month
 * @property integer $Amount
 * @property integer $Total
 * @property integer $CampaignId
 *
 * @property Campaign $campaign
 * @property Client $client
 * @property Gmid $gmid
 */
class Sale extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ClientId', 'GmidId', 'Month', 'CampaignId'], 'required'],
            [['ClientId', 'Month', 'Amount', 'CampaignId'], 'integer'],
            [['GmidId'], 'integer'],
            [['Total'], 'number'],            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ClientId' => Yii::t('app', 'Client ID'),
            'GmidId' => Yii::t('app', 'Gmid ID'),
            'Month' => Yii::t('app', 'Month'),
            'Amount' => Yii::t('app', 'Amount'),
            'Total' => Yii::t('app', 'Total'),
            'CampaignId' => Yii::t('app', 'Campaign ID'),
            'TradeProductId' => Yii::t('app', 'Trade Product'),
            'PerformanceCenterId' => Yii::t('app', 'Performance Center'),
            'ValueCenterId' => Yii::t('app', 'Value Center'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampaign()
    {
        return $this->hasOne(Campaign::className(), ['CampaignId' => 'CampaignId']);
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
    
    
    public static function getDashHistorySales($dashBoardFilter)
    {
        $campaigns = (new \common\models\Campaign)->getAll();    
        $sales = array();
        foreach ($campaigns as $campaign) 
        {
         $sale = Sale::find()->select(['SUM(Total) AS Total','SUM(Amount) AS Amount','CampaignId','Month'])
                                                ->innerJoin('gmid', 'gmid.GmidId = sale.GmidId')
                                                ->innerJoin('trade_product', 'trade_product.TradeProductId = gmid.TradeProductId')    
                                                ->innerJoin('performance_center', 'performance_center.PerformanceCenterId = trade_product.PerformanceCenterId')   
                                                ->innerJoin('client_seller', 'client_seller.ClientId = sale.ClientId ')
                                                ->innerJoin('user'.' seller', 'seller.UserId=client_seller.SellerId')
                                                ->innerJoin('user'.' dsm', 'dsm.UserId = seller.ParentId')
                                                ->innerJoin('user'.' rsm', 'rsm.UserId = dsm.ParentId')
                                                ->andWhere(['sale.CampaignId'=>$campaign["CampaignId"]]) 
                                                ->orderBy('Month ASC')
                                                 ;
         if($dashBoardFilter->TradeProductId)
         {   
            $sale->andWhere(['trade_product.TradeProductId'=>$dashBoardFilter->TradeProductId]);   
         }
         if($dashBoardFilter->PerformanceCenterId)
         {   
            $sale->andWhere(['performance_center.PerformanceCenterId'=>$dashBoardFilter->PerformanceCenterId]);   
         }
         if($dashBoardFilter->ValueCenterId)
         {   
            $sale->andWhere(['performance_center.ValueCenterId'=>$dashBoardFilter->ValueCenterId]);   
         }
         if($dashBoardFilter->ClientId)
         {   
            $sale->andWhere(['sale.ClientId'=>$dashBoardFilter->ClientId]);   
         }         
         if($dashBoardFilter->SellerId)
         {   
            $sale->andWhere(['seller.UserId'=>$dashBoardFilter->SellerId]);   
         }         
         if($dashBoardFilter->DsmId)
         {   
            $sale->andWhere(['dsm.UserId'=>$dashBoardFilter->DsmId]);   
         }         
         if($dashBoardFilter->RsmId)
         {   
            $sale->andWhere(['rsm.UserId'=>$dashBoardFilter->RsmId]);   
         }                   
         $items = $sale->groupBy(['sale.CampaignId','sale.Month'])->asArray()->all();
         $arrays = array();
         for ($x=1;$x<=12;$x++)
         {
             $search = false;
            foreach($items as $item)
            {
              if($item["Month"] == $x)
              {
                  $search = true;
                  $itemSelected = $item;
              }
            }
            if($search)
              $salesCampaign[$x] = $itemSelected["Total"];  
            else
             $salesCampaign[$x] = 0;     
        } 
         $sales[$campaign["Name"]] = $salesCampaign;
        }
    
        return $sales;        
    }
    
    
    public static function SaleSumUSD($dashBoardFilter)
    {
         $sale = Sale::find() 
                                         ->innerJoin('gmid','gmid.GmidId = sale.GmidId')
                                         ->innerJoin('trade_product','trade_product.TradeProductId = gmid.TradeProductId')                                                                
                                         ->innerJoin('performance_center', 'performance_center.PerformanceCenterId = trade_product.PerformanceCenterId')   
                                         ->innerJoin('client_seller', 'client_seller.ClientId = sale.ClientId ')
                                         ->innerJoin('user'.' seller', 'seller.UserId=client_seller.SellerId')
                                         ->innerJoin('user'.' dsm', 'dsm.UserId = seller.ParentId')
                                         ->innerJoin('user'.' rsm', 'rsm.UserId = dsm.ParentId')
                                         ->andWhere(['CampaignId'=>$dashBoardFilter->CampaignId]);
         
         if($dashBoardFilter->TradeProductId)
         {   
            $sale->andWhere(['trade_product.TradeProductId'=>$dashBoardFilter->TradeProductId]);   
         }
         if($dashBoardFilter->PerformanceCenterId)
         {   
            $sale->andWhere(['performance_center.PerformanceCenterId'=>$dashBoardFilter->PerformanceCenterId]);   
         }
         if($dashBoardFilter->ValueCenterId)
         {   
            $sale->andWhere(['performance_center.ValueCenterId'=>$dashBoardFilter->ValueCenterId]);   
         }
         if($dashBoardFilter->ClientId)
         {   
            $sale->andWhere(['sale.ClientId'=>$dashBoardFilter->ClientId]);   
         }         
         if($dashBoardFilter->SellerId)
         {   
            $sale->andWhere(['seller.UserId'=>$dashBoardFilter->SellerId]);   
         }         
         if($dashBoardFilter->DsmId)
         {   
            $sale->andWhere(['dsm.UserId'=>$dashBoardFilter->DsmId]);   
         }         
         if($dashBoardFilter->RsmId)
         {   
            $sale->andWhere(['rsm.UserId'=>$dashBoardFilter->RsmId]);   
         }                   
              $results = array();              
            // UNA MIERDA TODO ESTO
              $results[0] = 0;
              $results[1] = 0;
              $results[2] = 0;
              $results[3] = 0;
              $results[4] = 0;
              $results[5] = 0;
              $results[6] = 0;
              $results[7] = 0;
              $results[8] = 0;
              $results[9] = 0;
      
         foreach($sale->asArray()->all() as $item)        
         {                        
              $month = (int) $item['Month'];
             if($month<=3)
                 $results[1] = $results[1] + $item['Total'];
             if($month>=4 && $month<=6)
                 $results[3] = $results[3] + $item['Total'];
             if($month>=7 && $month<=9)
                 $results[5] = $results[5] + $item['Total'];
             if($month>=10 && $month<=12)
                 $results[7] = $results[7] + $item['Total'];
             
             $results[9] = $results[0] + $results[3] + $results[5] + $results[7];             
         }
         
        return $results;
    }
}
