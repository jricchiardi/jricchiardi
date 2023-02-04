<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "city".
 *
 * @property integer $CityId
 * @property string $Name
 * @property integer $StateId
 * @property resource $IsActive
 *
 * @property State $state
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'StateId'], 'unique', 'targetAttribute' => ['Name', 'StateId'], 'message' => 'The combination of Name and State ID has already been taken.'],
            [['Name', 'StateId'], 'required'],
            [['Name', 'IsActive'], 'string'],
            [['StateId'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CityId' => Yii::t('app', 'City ID'),
            'Name' => Yii::t('app', 'Name'),
            'StateId' => Yii::t('app', 'State ID'),
            'IsActive' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['StateId' => 'StateId']);
    }
}
