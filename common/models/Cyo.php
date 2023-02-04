<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cyo".
 *
 * @property integer $CyoId
 * @property integer $ClientId
 * @property integer $GmidId
 * @property integer $CampaignId
 * @property string $InventoryBalance
 *
 * @property Campaign $campaign
 * @property Client $client
 * @property Gmid $gmid
 * @property Cyo $cyo
 * @property Cyo $cyo0
 */
class Cyo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cyo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ClientId', 'CampaignId'], 'integer'],
            [['GmidId'], 'integer'],
            [['InventoryBalance'], 'number'],
            [['CampaignId'], 'exist', 'skipOnError' => true, 'targetClass' => Campaign::className(), 'targetAttribute' => ['CampaignId' => 'CampaignId']],
            [['ClientId'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['ClientId' => 'ClientId']],
            [['GmidId'], 'exist', 'skipOnError' => true, 'targetClass' => Gmid::className(), 'targetAttribute' => ['GmidId' => 'GmidId']],
            [['CyoId'], 'exist', 'skipOnError' => true, 'targetClass' => Cyo::className(), 'targetAttribute' => ['CyoId' => 'CyoId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CyoId' => Yii::t('app', 'Cyo ID'),
            'ClientId' => Yii::t('app', 'Client ID'),
            'GmidId' => Yii::t('app', 'Gmid ID'),
            'CampaignId' => Yii::t('app', 'Campaign ID'),
            'InventoryBalance' => Yii::t('app', 'Inventory Balance'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCyo()
    {
        return $this->hasOne(Cyo::className(), ['CyoId' => 'CyoId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCyo0()
    {
        return $this->hasOne(Cyo::className(), ['CyoId' => 'CyoId']);
    }
}
