<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "InverseSale".
 *
 * @property integer $ClientId
 * @property integer $GmidId
 * @property integer $CampaignId
 * @property integer $Total
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
 */
class InverseSale extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'InverseSale';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ClientId', 'GmidId', 'CampaignId'], 'required'],
            [['ClientId', 'CampaignId', 'Total', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'], 'integer'],
            [['GmidId'], 'integer']
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
            'CampaignId' => Yii::t('app', 'Campaign ID'),
            'Total' => Yii::t('app', 'Total'),
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
        ];
    }
}
