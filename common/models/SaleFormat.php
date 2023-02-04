<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "SaleFormat".
 *
 * @property integer $ClientProductId
 * @property integer $ClientId
 * @property integer $GmidId
 * @property integer $TradeProductId
 * @property integer $IsForecastable
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
 * @property integer $JanuaryUSD
 * @property integer $FebruaryUSD
 * @property integer $MarchUSD
 * @property integer $Q1USD
 * @property integer $AprilUSD
 * @property integer $MayUSD
 * @property integer $JuneUSD
 * @property integer $Q2USD
 * @property integer $JulyUSD
 * @property integer $AugustUSD
 * @property integer $SeptemberUSD
 * @property integer $Q3USD
 * @property integer $OctoberUSD
 * @property integer $NovemberUSD
 * @property integer $DecemberUSD
 * @property integer $Q4USD
 */
class SaleFormat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SaleFormat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ClientProductId', 'CampaignId'], 'required'],
            [['ClientProductId', 'ClientId', 'GmidId', 'TradeProductId', 'IsForecastable', 'CampaignId', 'January', 'February', 'March', 'Q1', 'April', 'May', 'June', 'Q2', 'July', 'August', 'September', 'Q3', 'October', 'November', 'December', 'Q4', 'JanuaryUSD', 'FebruaryUSD', 'MarchUSD', 'Q1USD', 'AprilUSD', 'MayUSD', 'JuneUSD', 'Q2USD', 'JulyUSD', 'AugustUSD', 'SeptemberUSD', 'Q3USD', 'OctoberUSD', 'NovemberUSD', 'DecemberUSD', 'Q4USD'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ClientProductId' => Yii::t('app', 'Client Product ID'),
            'ClientId' => Yii::t('app', 'Client ID'),
            'GmidId' => Yii::t('app', 'Gmid ID'),
            'TradeProductId' => Yii::t('app', 'Trade Product ID'),
            'IsForecastable' => Yii::t('app', 'Is Forecastable'),
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
            'JanuaryUSD' => Yii::t('app', 'January Usd'),
            'FebruaryUSD' => Yii::t('app', 'February Usd'),
            'MarchUSD' => Yii::t('app', 'March Usd'),
            'Q1USD' => Yii::t('app', 'Q1 Usd'),
            'AprilUSD' => Yii::t('app', 'April Usd'),
            'MayUSD' => Yii::t('app', 'May Usd'),
            'JuneUSD' => Yii::t('app', 'June Usd'),
            'Q2USD' => Yii::t('app', 'Q2 Usd'),
            'JulyUSD' => Yii::t('app', 'July Usd'),
            'AugustUSD' => Yii::t('app', 'August Usd'),
            'SeptemberUSD' => Yii::t('app', 'September Usd'),
            'Q3USD' => Yii::t('app', 'Q3 Usd'),
            'OctoberUSD' => Yii::t('app', 'October Usd'),
            'NovemberUSD' => Yii::t('app', 'November Usd'),
            'DecemberUSD' => Yii::t('app', 'December Usd'),
            'Q4USD' => Yii::t('app', 'Q4 Usd'),
        ];
    }
}
