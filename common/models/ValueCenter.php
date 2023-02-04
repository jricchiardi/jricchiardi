<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "value_center".
 *
 * @property integer $ValueCenterId
 * @property string $Description
 * @property string $Abbreviation
 * @property integer $IsActive
 *
 * @property PerformanceCenter[] $performanceCenters
 */
class ValueCenter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'value_center';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ValueCenterId', 'Description'], 'required'],
            [['ValueCenterId', 'IsActive'], 'integer'],
            [['Description', 'Abbreviation'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ValueCenterId' => Yii::t('app', 'Value Center ID'),
            'Description' => Yii::t('app', 'Description'),
            'Abbreviation' => Yii::t('app', 'Abbreviation'),
            'IsActive' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerformanceCenters()
    {
        return $this->hasMany(PerformanceCenter::className(), ['ValueCenterId' => 'ValueCenterId']);
    }
}
