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
class Ingredient extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vw_gmid_ingredient_country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Ingredient' => Yii::t('app', 'Ingredient'),
            'GmidId' => Yii::t('app', 'GmidId'),
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGmids()
    {
        return $this->hasMany(Gmid::className(), ['GmidId' => 'GmidId']);
    }
}
