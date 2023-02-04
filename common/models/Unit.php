<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "unit".
 *
 * @property integer $UnitId
 * @property string $Name
 */
class Unit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'UnitId' => Yii::t('app', 'Unit ID'),
            'Name' => Yii::t('app', 'Name'),
        ];
    }
}
