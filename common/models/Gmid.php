<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gmid".
 *
 * @property integer $GmidId
 * @property string $Description
 * @property integer $TradeProductId
 * @property string $Price
 * @property string $Profit
 * @property integer $CountryId
 * @property integer $IsForecastable
 * @property integer $IsActive
 *
 * @property Sale[] $sales
 * @property ClientProduct[] $clientProducts
 * @property Country $country
 * @property TradeProduct $tradeProduct
 */
class Gmid extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gmid';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['GmidId'], 'required'],
            [['Description'], 'string'],
            [['Price', 'Profit'], 'number'],
            [['GmidId','TradeProductId','CountryId', 'IsForecastable', 'IsActive'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'GmidId' => Yii::t('app', 'Gmid ID'),
            'Description' => Yii::t('app', 'Description'),
            'TradeProductId' => Yii::t('app', 'Trade Product ID'),
            'Price' => Yii::t('app', 'Price'),
            'Profit' => Yii::t('app', 'Profit'),
            'CountryId' => Yii::t('app', 'Country ID'),
            'IsForecastable' => Yii::t('app', 'Is Forecastable'),
            'IsActive' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasMany(Sale::className(), ['GmidId' => 'GmidId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientProducts()
    {
        return $this->hasMany(ClientProduct::className(), ['GmidId' => 'GmidId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['CountryId' => 'CountryId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTradeProduct()
    {
        return $this->hasOne(TradeProduct::className(), ['TradeProductId' => 'TradeProductId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIngredient()
    {
        return $this->hasOne(Ingredient::className(), ['GmidId' => 'GmidId']);
    }
}
