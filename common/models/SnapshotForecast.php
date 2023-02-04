<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "snapshot_forecast".
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
class SnapshotForecast extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'snapshot_forecast';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ClientProductId', 'CampaignId'], 'required'],
            [['ClientProductId', 'CampaignId', 'January', 'February', 'March', 'Q1', 'April', 'May', 'June', 'Q2', 'July', 'August', 'September', 'Q3', 'October', 'November', 'December', 'Q4', 'Total'], 'integer'],
            [['CampaignId'], 'exist', 'skipOnError' => true, 'targetClass' => Campaign::className(), 'targetAttribute' => ['CampaignId' => 'CampaignId']],
            [['ClientProductId'], 'exist', 'skipOnError' => true, 'targetClass' => ClientProduct::className(), 'targetAttribute' => ['ClientProductId' => 'ClientProductId']],
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
            'January' => Yii::t('app', 'Ene'),
            'February' => Yii::t('app', 'Feb'),
            'March' => Yii::t('app', 'Mar'),            
            'April' => Yii::t('app', 'Abr'),
            'May' => Yii::t('app', ' May'),
            'June' => Yii::t('app', 'Jun'),            
            'July' => Yii::t('app', 'Jul'),
            'August' => Yii::t('app', 'Ago'),
            'September' => Yii::t('app', 'Sep'),            
            'October' => Yii::t('app', 'Oct'),
            'November' => Yii::t('app', 'Nov'),
            'December' => Yii::t('app', 'Dec'),            
            'Total' => Yii::t('app', 'Total'),
            'ClientId'=> Yii::t('app', 'Client'),
            'SellerId'=> Yii::t('app', 'Seller'),
            'DsmId'=> Yii::t('app', 'DSM'),
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
    public function getClientProduct()
    {
        return $this->hasOne(ClientProduct::className(), ['ClientProductId' => 'ClientProductId']);
    }
}
