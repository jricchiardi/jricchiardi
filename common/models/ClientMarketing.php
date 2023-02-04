<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "client_marketing".
 *
 * @property integer $ClientMarketingId
 * @property integer $ClientTypeId
 * @property integer $GroupId
 * @property integer $CountryId
 * @property string $Description
 * @property integer $IsGroup
 * @property integer $IsActive
 *
 * @property ClientType $clientType
 * @property Country $country
 * @property ClientSeller[] $clientSellers
 * @property User[] $sellers
 * @property Sale[] $sales
 */
class ClientMarketing extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client_marketing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ClientMarketingId', 'Description'], 'required'],
            [['ClientMarketingId', 'ClientTypeId', 'GroupId', 'CountryId', 'IsGroup', 'IsActive'], 'integer'],
            [['Description'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ClientMarketingId' => Yii::t('app', 'Client Marketing ID'),
            'ClientTypeId' => Yii::t('app', 'Client Type ID'),
            'GroupId' => Yii::t('app', 'Group ID'),
            'CountryId' => Yii::t('app', 'Country'),
            'Description' => Yii::t('app', 'Description'),
            'IsGroup' => Yii::t('app', 'Is Group'),
            'IsActive' => Yii::t('app', 'Is Active'),
            'Country' => Yii::t('app', 'Country'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getClientType()
    {
        return $this->hasOne(ClientType::className(), ['ClientTypeId' => 'ClientTypeId']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['CountryId' => 'CountryId']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSellers()
    {
        return $this->hasMany(User::className(), ['UserId' => 'SellerId'])->viaTable('client_seller', ['ClientId' => 'ClientId']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSales()
    {
        return $this->hasMany(Sale::className(), ['ClientId' => 'ClientId']);
    }
}
