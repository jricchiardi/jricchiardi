<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "setting".
 *
 * @property integer $SettingId
 * @property string $Name
 * @property string $DisplayName
 * @property string $Value
 */
class Setting extends \yii\db\ActiveRecord
{
    const FORECAST_ENABLE_FROM = 5;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'DisplayName', 'Value'], 'string']
        ];
    }

    public static function getValue($id)
    {
        return Setting::findOne(['SettingId'=>$id])->Value;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'SettingId' => Yii::t('app', 'Setting ID'),
            'Name' => Yii::t('app', 'Name'),
            'DisplayName' => Yii::t('app', 'Display Name'),
            'Value' => Yii::t('app', 'Value'),
        ];
    }
}
