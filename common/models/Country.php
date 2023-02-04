<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "country".
 *
 * @property integer $CountryId
 * @property string $Abbreviation
 * @property string $Description
 * @property integer $IsActive
 *
 * @property Client[] $clients
 * @property Country $description
 * @property Country[] $countries
 * @property Gmid[] $gms
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Abbreviation', 'Description'], 'string'],
            [['Description'], 'required'],
            [['IsActive'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CountryId' => Yii::t('app', 'Country ID'),
            'Abbreviation' => Yii::t('app', 'Abbreviation'),
            'Description' => Yii::t('app', 'Country'),
            'IsActive' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClients()
    {
        return $this->hasMany(Client::className(), ['CountryId' => 'CountryId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDescription()
    {
        return $this->hasOne(Country::className(), ['Description' => 'Description']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountries()
    {
        return $this->hasMany(Country::className(), ['Description' => 'Description']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGms()
    {
        return $this->hasMany(Gmid::className(), ['CountryId' => 'CountryId']);
    }
}
