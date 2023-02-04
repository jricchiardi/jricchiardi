<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "client_type".
 *
 * @property integer $ClientTypeId
 * @property string $Description
 * @property integer $IsActive
 *
 * @property Client[] $clients
 */
class ClientType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ClientTypeId'], 'required'],
            [['ClientTypeId', 'IsActive'], 'integer'],
            [['Description'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ClientTypeId' => Yii::t('app', 'Client Type ID'),
            'Description' => Yii::t('app', 'Client Type ID'),
            'IsActive' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClients()
    {
        return $this->hasMany(Client::className(), ['ClientTypeId' => 'ClientTypeId']);
    }
}
