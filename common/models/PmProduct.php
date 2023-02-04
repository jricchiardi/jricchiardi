<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * This is the model class for table "pm_product".
 *
 * @property integer $PmProductId
 * @property integer $TradeProductId
 * @property integer $GmidId
 * @property integer $UserId
 *
 * @property TradeProduct $tradeProduct
 * @property Gmid $gmid
 * @property User $user
 */
class PmProduct extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pm_product';
    }

//    /**
//     * @inheritdoc
//     */
//    public function rules()
//    {
//        return [
//            [['ClientProductId', 'CampaignId'], 'required'],
//            [['ClientProductId', 'CampaignId', 'Amount'], 'integer'],
//        ];
//    }

//    /**
//     * @inheritdoc
//     */
//    public function attributeLabels()
//    {
//        return [
//            'ClientProductId' => Yii::t('app', 'Client Product ID'),
//            'CampaignId' => Yii::t('app', 'Campaign ID'),
//            'Amount' => 'Cantidad',
//        ];
//    }

    /**
     * @return ActiveQuery
     */
    public function getTradeProduct()
    {
        return $this->hasOne(TradeProduct::className(), ['TradeProductId' => 'TradeProductId']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGmid()
    {
        return $this->hasOne(Gmid::className(), ['GmidId' => 'GmidId']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['UserId' => 'UserId']);
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function getArrayForExcelExport()
    {
        $connection = Yii::$app->db;

        return $connection->createCommand("
            SELECT c.Description AS 'Country',
                tp.TradeProductId AS 'Trade Product',
                tp.Description AS 'Trade Product Description',
                g.GmidId AS 'Gmid',
                g.Description AS 'Gmid Description',
                u.Username as 'Product Manager Username'
            FROM pm_product
                INNER JOIN [user] u on pm_product.UserId = u.UserId
                INNER JOIN trade_product tp on pm_product.TradeProductId = tp.TradeProductId
                LEFT JOIN gmid g on pm_product.GmidId = g.GmidId
                LEFT JOIN country c on g.CountryId = c.CountryId
        ")->queryAll();
    }

//    public function _setAttributes($values, $safeOnly = true)
//    {
//        if (is_array($values))
//        {
//            $attributes = array_flip($safeOnly ? $this->safeAttributes() : $this->attributes());
//            foreach ($values as $name => $value) {
//                if (isset($attributes[$name]))
//                {
//                    $this->$name = $value;
//                } elseif ($safeOnly) {
//                    $this->onUnsafeAttribute($name, $value);
//                }
//            }
//        }
//    }
}
