<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
/*

 */
/**
 * This is the model class for table "FcastIBP".
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
class FcastIBP extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'FCASTIBP';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ShipToCountry', 'OldProductID'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ShipToCountry' => Yii::t('app', 'ShipToCountry'),
            'Portfolio' => Yii::t('app', 'Portfolio'),
            'Ingredient' => Yii::t('app', 'Ingredien'),
            'OldProductID' => Yii::t('app', 'OldProductID'),
            'ProductDesc' => Yii::t('app', 'ProductDesc'),
            'KeyFigure' => Yii::t('app', 'KeyFigure'),
			'January' => Yii::t('app', 'January'),
            'February' => Yii::t('app', 'February'),
            'March' => Yii::t('app', 'March'),
            'April' => Yii::t('app', 'April'),
            'May' => Yii::t('app', 'May'),
            'June' => Yii::t('app', 'June'),
            'July' => Yii::t('app', 'July'),
            'August' => Yii::t('app', 'August'),
            'September' => Yii::t('app', 'September'),
            'October' => Yii::t('app', 'October'),
            'November' => Yii::t('app', 'November'),
            'December' => Yii::t('app', 'December'),
            'TotalYear' => Yii::t('app', 'TotalYear'),
			'Año' => Yii::t('app', 'Año'),
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
