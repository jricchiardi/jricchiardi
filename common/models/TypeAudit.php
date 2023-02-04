<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "type_audit".
 *
 * @property integer $TypeAuditId
 * @property string $Name
 * @property string $PublicName
 *
 * @property Audit[] $audits
 */
class TypeAudit extends \yii\db\ActiveRecord
{
    const TYPE_LOGIN = 1;
    const TYPE_SAVE_FORECAST = 2;
    const TYPE_SAVE_PLAN = 3;
    const TYPE_IMPORT_PLAN_OFFLINE = 4;
    const TYPE_IMPORT_FORECAST_OFFLINE = 5;
    const TYPE_EXPORT_PLAN_OFFLINE = 6;
    const TYPE_EXPORT_FORECAST_OFFLINE = 7;
    const TYPE_IMPORT_MARKETING_FORECAST_OFFLINE = 8;
    const TYPE_SAVE_MARKETING_FORECAST = 9;
    const TYPE_EXPORT_FORECAST_MARKETING_OFFLINE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'type_audit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'PublicName'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'TypeAuditId' => Yii::t('app', 'Type Audit ID'),
            'Name' => Yii::t('app', 'Name'),
            'PublicName' => Yii::t('app', 'Public Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAudits()
    {
        return $this->hasMany(Audit::className(), ['TypeAuditId' => 'TypeAuditId']);
    }
}
