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
class Sis extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'DESPNOFC'; // TODO: replace with view
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
            'SalesDoc' => Yii::t('app', 'SalesDoc'),
            'SalesItem' => Yii::t('app', 'SalesItem'),
            'SalesDocType' => Yii::t('app', 'SalesDocType'),
            'SoldToCustNumber' => Yii::t('app', 'SoldToCustNumber'),
            'SoldToCustName' => Yii::t('app', 'SoldToCustName'),
            'MaterialCode' => Yii::t('app', 'MaterialCode'),
            'MaterialDescript' => Yii::t('app', 'MaterialDescript'),
            'DeliveryQ' => Yii::t('app', 'DeliveryQ'),
            'SalesUoM' => Yii::t('app', 'SalesUoM'),
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
