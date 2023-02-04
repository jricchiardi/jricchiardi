<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "state".
 *
 * @property integer $StateId
 * @property string $Name
 * @property integer $CountryId
 * @property resource $IsActive
 *
 * @property City[] $cities
 */
class State extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'state';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'CountryId'], 'required'],
            [['Name', 'IsActive'], 'string'],
            [['CountryId'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'StateId' => Yii::t('app', 'State ID'),
            'Name' => Yii::t('app', 'Name'),
            'CountryId' => Yii::t('app', 'Country ID'),
            'IsActive' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(City::className(), ['StateId' => 'StateId']);
    }
}
