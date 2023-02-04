<?php

namespace common\models;

use Exception;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * @property string $code
 * @property string $value
 */
class SisMetadata extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sis_report_metadata';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'TypeImportId'], 'required'],
            [['TypeImportId'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ImportId' => Yii::t('app', 'Import ID'),
            'CreatedAt' => Yii::t('app', 'Created At'),
            'TypeImportId' => Yii::t('app', 'Type Import ID'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['CreatedAt'],
                ],
                'value' => new Expression('GETDATE()'),
            ],
        ];
    }

}
