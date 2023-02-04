<?php
namespace common\components\models;

class FilterDashboard extends \yii\base\Model
{
    public $ValueCenterId;
    public $PerformanceCenterId;
    public $TradeProductId;
    public $ClientId;
    public $SellerId;
    public $DsmId;
    public $RsmId;   
    public $CampaignId;
    public $QuarterId;


    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);     
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [            
            [['ValueCenterId','PerformanceCenterId','TradeProductId','ClientId','SellerId','DsmId','RsmId','CampaignId','QuarterId'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ValueCenterId' => 'Value Center',
            'PerformanceCenterId'=> 'Performance Center',
            'TradeProductId' =>'Trade Product',
            'ClientId' => 'Cliente',
            'SellerId' => 'Vendedor',
            'DsmId' => 'DSM',
            'RsmId' => 'RSM',
            'QuarterId' => 'Quarters',
        ];
    }
    
    public static function getQuarters()
    {
      return [['id'=>'0','text'=>'Seleccione'],
              ['id'=>'Q1','text'=>'Q1'],
              ['id'=>'Q2','text'=>'Q2'],
              ['id'=>'Q3','text'=>'Q3'],
              ['id'=>'Q4','text'=>'Q4'],
             ]  ;
    }
    
   
    
}