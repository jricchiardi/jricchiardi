<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "notification".
 *
 * @property integer $NotificationId
 * @property integer $FromUserId
 * @property string $Description
 * @property integer $ToUserId
 * @property string $ToProfileId
 * @property integer $ObjectId
 * @property string $CreatedAt
 * @property integer $NotificationStatusId
 *
 * @property User $fromUser
 * @property NotificationStatus $notificationStatus
 * @property AuthItem $toProfile
 * @property User $toUser
 */
class Notification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['FromUserId', 'ToUserId', 'ObjectId', 'NotificationStatusId'], 'integer'],
            [['Description', 'ToProfileId'], 'string'],
            [['CreatedAt'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'NotificationId' => Yii::t('app', 'Notification ID'),
            'FromUserId' => Yii::t('app', 'From User ID'),
            'Description' => Yii::t('app', 'Description'),
            'ToUserId' => Yii::t('app', 'To User ID'),
            'ToProfileId' => Yii::t('app', 'To Profile ID'),
            'ObjectId' => Yii::t('app', 'Object ID'),
            'CreatedAt' => Yii::t('app', 'Created At'),
            'NotificationStatusId' => Yii::t('app', 'Notification Status ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromUser()
    {
        return $this->hasOne(User::className(), ['UserId' => 'FromUserId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationStatus()
    {
        return $this->hasOne(NotificationStatus::className(), ['NotificationStatusId' => 'NotificationStatusId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToProfile()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'ToProfileId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToUser()
    {
        return $this->hasOne(User::className(), ['UserId' => 'ToUserId']);
    }
}
