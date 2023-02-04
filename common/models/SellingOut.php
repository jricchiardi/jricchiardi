<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "selling_out".
 *
 * @property integer $ClientProductId
 * @property integer $CampaignId
 * @property integer $Amount
 *
 * @property Campaign $campaign
 * @property ClientProduct $clientProduct
 */
class SellingOut extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'selling_out';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ClientProductId', 'CampaignId'], 'required'],
            [['ClientProductId', 'CampaignId', 'Amount'], 'integer'],
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
            'Amount' => 'Cantidad',
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

    public function _setAttributes($values, $safeOnly = true)
    {
        if (is_array($values))
        {
            $attributes = array_flip($safeOnly ? $this->safeAttributes() : $this->attributes());
            foreach ($values as $name => $value) {
                if (isset($attributes[$name]))
                {
                    $this->$name = $value;
                } elseif ($safeOnly) {
                    $this->onUnsafeAttribute($name, $value);
                }
            }
        }
    }
}
