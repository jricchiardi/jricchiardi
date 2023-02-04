<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "client".
 *
 * @property integer $ClientId
 * @property integer $ClientTypeId
 * @property integer $GroupId
 * @property integer $CountryId
 * @property string $Description
 * @property integer $IsGroup
 * @property integer $IsActive
 *
 * @property Client $client
 * @property ClientType $clientType
 * @property Country $country
 * @property ClientProduct[] $clientProducts
 * @property ClientSeller[] $clientSellers
 * @property User[] $sellers
 * @property Sale[] $sales
 */
class Client extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ClientId', 'Description'], 'required'],
            [['ClientId', 'ClientTypeId', 'GroupId', 'CountryId', 'IsGroup', 'IsActive'], 'integer'],
            [['Description'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ClientId' => Yii::t('app', 'Client ID'),
            'ClientTypeId' => Yii::t('app', 'Client Type ID'),
            'GroupId' => Yii::t('app', 'Group ID'),
            'CountryId' => Yii::t('app', 'Country'),
            'Description' => Yii::t('app', 'Description'),
            'IsGroup' => Yii::t('app', 'Is Group'),
            'IsActive' => Yii::t('app', 'Is Active'),
            'Country'=>Yii::t('app', 'Country'),
        ];
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
    public function getClientType()
    {
        return $this->hasOne(ClientType::className(), ['ClientTypeId' => 'ClientTypeId']);
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
    public function getClientProducts()
    {
        return $this->hasMany(ClientProduct::className(), ['ClientId' => 'ClientId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientSellers()
    {
        return $this->hasMany(ClientSeller::className(), ['ClientId' => 'ClientId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSellers()
    {
        return $this->hasMany(User::className(), ['UserId' => 'SellerId'])->viaTable('client_seller', ['ClientId' => 'ClientId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasMany(Sale::className(), ['ClientId' => 'ClientId']);
    }
}
