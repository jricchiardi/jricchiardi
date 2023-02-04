<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
/*

 */
/**
 * This is the model class for table "OPENORDERS".
 *
 * @property string $SalesOrg
 * @property string $Item
 * @property string $OrderNo
 * @property string $DelivNo
 * @property string $CredBlock
 * @property string $OrderType
 * @property string $SoldToCustNumber
 * @property string $SoldToCustName
 * @property string $MaterialCode
 * @property string $MaterialDescript
 * @property string $PlantCode
 * @property string $OpenQConfirmedQ
 * @property string $OrderQ
 * @property string $SalesUoM
 * @property string $ConfirmedDelvDate
 * @property string $ShipToCustNumber
 * @property string $ShipToCustName
 * @property string $CustPurchaseOrdNo
 * @property string $ConfirmedShipDate
 */
class OpenOrders extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'OPENORDERS';
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
            'Item' => Yii::t('app', 'Item'),
            'OrderNo' => Yii::t('app', 'OrderNo'),
            'DelivNo' => Yii::t('app', 'DelivNo'),
            'CredBlock' => Yii::t('app', 'CredBlock'),
            'OrderType' => Yii::t('app', 'OrderType'),
            'SoldToCustNumber' => Yii::t('app', 'SoldToCustNumber'),
            'SoldToCustName' => Yii::t('app', 'SoldToCustName'),
            'MaterialCode' => Yii::t('app', 'MaterialCode'),
            'MaterialDescript' => Yii::t('app', 'MaterialDescript'),
            'PlantCode' => Yii::t('app', 'PlantCode'),
            'OpenQConfirmedQ' => Yii::t('app', 'OpenQConfirmedQ'),
            'OrderQ' => Yii::t('app', 'OrderQ'),
            'SalesUoM' => Yii::t('app', 'SalesUoM'),
            'ConfirmedDelvDate' => Yii::t('app', 'ConfirmedDelvDate'),
            'ShipToCustNumber' => Yii::t('app', 'ShipToCustNumber'),
            'ShipToCustName' => Yii::t('app', 'ShipToCustName'),
            'CustPurchaseOrdNo' => Yii::t('app', 'CustPurchaseOrdNo'),
            'ConfirmedShipDate' => Yii::t('app', 'ConfirmedShipDate'),
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
