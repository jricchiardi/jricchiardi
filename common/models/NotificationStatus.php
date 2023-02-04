<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "notification_status".
 *
 * @property integer $NotificationStatusId
 * @property string $Name
 *
 * @property Notification[] $notifications
 */
class NotificationStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'NotificationStatusId' => Yii::t('app', 'Notification Status ID'),
            'Name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::className(), ['NotificationStatusId' => 'NotificationStatusId']);
    }
}
