<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "audit".
 *
 * @property integer $AuditId
 * @property integer $TypeAuditId
 * @property integer $CampaignId
 * @property integer $UserId
 * @property integer $ClientId
 * @property string $Description
 * @property string $Date
 *
 * @property TypeAudit $typeAudit
 * @property User $user
 * @property Campaign $campaign
 * @property Client $client
 */
class Audit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'audit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TypeAuditId', 'CampaignId', 'UserId', 'ClientId'], 'integer'],
            [['Description'], 'string'],
            [['Date'], 'safe'],
            [['TypeAuditId'], 'exist', 'skipOnError' => true, 'targetClass' => TypeAudit::className(), 'targetAttribute' => ['TypeAuditId' => 'TypeAuditId']],
            [['UserId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['UserId' => 'UserId']],
            [['CampaignId'], 'exist', 'skipOnError' => true, 'targetClass' => Campaign::className(), 'targetAttribute' => ['CampaignId' => 'CampaignId']],
            [['ClientId'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['ClientId' => 'ClientId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'AuditId' => Yii::t('app', 'Audit ID'),
            'TypeAuditId' => Yii::t('app', 'Type Audit ID'),
            'CampaignId' => Yii::t('app', 'Campaign ID'),
            'UserId' => Yii::t('app', 'Seller'),
            'DsmId' => Yii::t('app', 'DSM'),
            'RsmId' => Yii::t('app', 'RSM'),
            'ClientId' => Yii::t('app', 'Client ID'),
            'Description' => Yii::t('app', 'Description'),
            'Date' => Yii::t('app', 'Date'),
            'dateFrom' => Yii::t('app', 'Date From'),
            'dateTo' => Yii::t('app', 'Date To'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypeAudit()
    {
        return $this->hasOne(TypeAudit::className(), ['TypeAuditId' => 'TypeAuditId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['UserId' => 'UserId']);
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
}
