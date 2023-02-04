<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_status".
 *
 * @property integer $OrderStatusId
 * @property string $Name
 * @property string $Color
 *
 * @property Order[] $orders
 */
class OrderStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name'], 'string', 'max' => 50],
            [['Color'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'OrderStatusId' => Yii::t('app', 'Order Status ID'),
            'Name' => Yii::t('app', 'Name'),
            'Color' => Yii::t('app', 'Color'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['OrderStatusId' => 'OrderStatusId']);
    }
}
