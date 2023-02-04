<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "client_seller".
 *
 * @property integer $ClientId
 * @property integer $SellerId
 *
 * @property Client $client
 * @property User $seller
 */
class ClientSeller extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client_seller';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ClientId', 'SellerId'], 'required'],
            [['ClientId', 'SellerId'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ClientId' => Yii::t('app', 'Client ID'),
            'SellerId' => Yii::t('app', 'Seller ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['ClientId' => 'ClientId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeller()
    {
        return $this->hasOne(User::className(), ['UserId' => 'SellerId']);
    }
}
