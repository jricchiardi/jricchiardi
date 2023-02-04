<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "forecast_marketing".
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
class ForecastMarketingOld extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'forecast_marketing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ClientMarketingProductId', 'CampaignId'], 'required'],
            [['ClientMarketingProductId', 'CampaignId', 'January', 'February', 'March', 'Q1', 'April', 'May', 'June', 'Q2', 'July', 'August', 'September', 'Q3', 'October', 'November', 'December', 'Q4', 'Total'], 'integer']
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
     * @return ActiveQuery
     */
    public function getCampaign()
    {
        return $this->hasOne(Campaign::className(), ['CampaignId' => 'CampaignId']);
    }

    /**
     * @return ActiveQuery
     */
    public function getClientProduct()
    {
        return $this->hasOne(ClientProduct::className(), ['ClientProductId' => 'ClientProductId']);
    }

    public function calculateQuarters()
    {
        $this->Q1 = $this->January + $this->February + $this->March;
        $this->Q2 = $this->April + $this->May + $this->June;
        $this->Q3 = $this->July + $this->August + $this->September;
        $this->Q4 = $this->October + $this->November + $this->December;
        $this->Total = $this->Q1 + $this->Q2 + $this->Q3 + $this->Q4;
    }

    public function _setAttributes($values, $safeOnly = true)
    {
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
}
