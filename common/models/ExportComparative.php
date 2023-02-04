<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ExportComparative".
 *
 * @property string $Country
 * @property string $DSM
 * @property string $NameDSM
 * @property string $Seller
 * @property string $NameSeller
 * @property integer $ClientId
 * @property string $Client
 * @property integer $TradeProductId
 * @property string $TradeProduct
 * @property string $PerformanceCenterId
 * @property string $PerformanceCenter
 * @property integer $GmidId
 * @property string $Gmid
 * @property integer $ClientProductId
 * @property integer $CampaignId
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
 * @property integer $Total
 */
class ExportComparative extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ExportComparative';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Country', 'ClientId', 'Client', 'TradeProductId', 'PerformanceCenterId', 'ClientProductId', 'CampaignId'], 'required'],
            [['Country', 'DSM', 'NameDSM', 'Seller', 'NameSeller', 'Client', 'TradeProduct', 'PerformanceCenterId', 'PerformanceCenter', 'Gmid'], 'string'],
            [['DsmId','RsmId','ClientId', 'ClientProductId', 'CampaignId', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'Total','TradeProductId','GmidId'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Country' => Yii::t('app', 'Country'),
            'DSM' => Yii::t('app', 'Dsm'),
            'NameDSM' => Yii::t('app', 'Name Dsm'),
            'Seller' => Yii::t('app', 'Seller'),
            'NameSeller' => Yii::t('app', 'Name Seller'),
            'ClientId' => Yii::t('app', 'Client ID'),
            'Client' => Yii::t('app', 'Client'),
            'TradeProductId' => Yii::t('app', 'Trade Product ID'),
            'TradeProduct' => Yii::t('app', 'Trade Product'),
            'PerformanceCenterId' => Yii::t('app', 'Performance Center ID'),
            'PerformanceCenter' => Yii::t('app', 'Performance Center'),
            'GmidId' => Yii::t('app', 'Gmid ID'),
            'Gmid' => Yii::t('app', 'Gmid'),
            'ClientProductId' => Yii::t('app', 'Client Product ID'),
            'CampaignId' => Yii::t('app', 'Campaign ID'),
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
            'Total' => Yii::t('app', 'Total'),
        ];
    }
    
    
    public function getReport($params)
    {
        $this->load($params);
        $rows = ExportComparative::find()->andFilterWhere(['CampaignId' => $this->CampaignId,
                                                'RsmId' => $this->RsmId,
                                                'DsmId' => $this->DsmId,    
                        ])
                        ->orderBy('Country,NameDSM,NameSeller,Client,TradeProduct,PerformanceCenter,Gmid ASC')->asArray()->all();
        return $rows;
       
        
    }
}
