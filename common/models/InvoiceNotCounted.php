<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
/*

 */
/**
 * This is the model class for table "FCNOCONT".
 *
 * @property string $SalesOrg
 * @property string $BillingNo
 * @property string $BillingType
 * @property string $SoldToPartyNumber
 * @property string $SoldToPartyName
 * @property string $Item
 * @property string $MaterialCode
 * @property string $MaterialDescript
 * @property string $BilledQ
 * @property string $BaseUoM
 * @property string $BillingDate
 */
class InvoiceNotCounted extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'FCNOCONT';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SalesOrg', 'Item'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'SalesOrg' => Yii::t('app', 'SalesOrg'),
            'BillingNo' => Yii::t('app', 'BillingNo'),
            'BillingType' => Yii::t('app', 'BillingType'),
            'SoldToPartyNumber' => Yii::t('app', 'SoldToPartyNumber'),
            'SoldToPartyName' => Yii::t('app', 'SoldToPartyName'),
            'Item' => Yii::t('app', 'Item'),
            'MaterialCode' => Yii::t('app', 'MaterialCode'),
            'MaterialDescript' => Yii::t('app', 'MaterialDescript'),
            'BilledQ' => Yii::t('app', 'BilledQ'),
            'BaseUoM' => Yii::t('app', 'BaseUoM'),
            'BillingDate' => Yii::t('app', 'BillingDate'),
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
