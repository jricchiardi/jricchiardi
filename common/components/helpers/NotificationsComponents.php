<?php

namespace common\components\helpers;

use Yii;
use yii\base\Component;
use common\models\Notification;

class NotificationsComponents extends Component implements INotification {

    public function getNotifications($options = NULL, &$count = NULL) {

        $allNotifications = '';
        if (!is_null(\Yii::$app->user->identity)) {
            $notifications = Notification::find();

            $notifications->andWhere(['NotificationStatusId' => \Yii::$app->params['notification.status.pending']]);
            $notifications->andWhere('DATEDIFF(d,CreatedAt,getdate())<=' . \Yii::$app->params['days.refresh.notifications']);
            $notifications->andWhere(['OR', ['ToUserId' => \Yii::$app->user->identity->UserId], ['ToProfileId' => \Yii::$app->user->identity->authAssignment->item_name]]);
            $notifications->orderBy('NotificationId DESC');
//        var_dump($notifications);                             
            $notifications = $notifications->all();

            $count = count($notifications);

            foreach ($notifications as $notification) {
                $allNotifications = $allNotifications . '<p>' . $notification->Description . '</p>';
            }
            if ($count <= 0)
                $allNotifications = '<p>No notifications available for your user</p>';
        }
        return $allNotifications;
    }

    public function createNotification($options = NULL) {
        $notification = new Notification();

        $description = $options["Description"];

        $notification->save();

        if (isset($options['Url']))
            $description = $description . '<a href="' . $options['Url'] . '&notificationId=' . $notification->NotificationId . '"> Enter </a>';

        $notification->Description = $description;
        $notification->NotificationStatusId = \Yii::$app->params['notification.status.pending'];
        $notification->CreatedAt = date('Y-m-d H:i:s');

        if (isset($options["FromUserId"]))
            $notification->FromUserId = $options["FromUserId"];
        else
            $notification->FromUserId = \Yii::$app->user->identity->UserId;

        if (isset($options["ToUserId"]))
            $notification->ToUserId = $options["ToUserId"];
        if (isset($options["ToProfileId"]))
            $notification->ToProfileId = $options["ToProfileId"];
        if (isset($options["ObjectId"]))
            $notification->ObjectId = $options["ObjectId"];

   
        // Send notification mail if it set to, and its configured in common/config/params_local.php
        if (isset($options["WithEmail"])) {
            
            
            if (isset($options['UrlEmail']))
                $description = $options["Description"] . ' <a href="' . $options['UrlEmail'] . '&notificationId=' . $notification->NotificationId . '"> Ingrese </a>';
            else
                $description = $options["Description"];
                
            // Hack to solve Timeout when sending Emails
            set_time_limit(0);

            if (isset($notification->ToUserId)) {
                $user = \common\models\User::findOne(['UserId' => $notification->ToUserId]);
                Yii::$app->mailer->compose()
                        ->setTo($user->Email)
                        ->setFrom([Yii::$app->user->identity->Email => Yii::$app->user->identity->Fullname])
                        ->setSubject($options['Subject'])
                        ->setHtmlBody($description)
                        ->send();
            } else {
                $users = \common\models\AuthAssignment::findAll(['item_name' => $options["ToProfileId"]]);
                
                $emailAddresses = [];
                
                foreach ($users as $us){
                    $emailAddresses[] = $us->user->Email;
                }
                
                Yii::$app->mailer->compose()
                    ->setTo($emailAddresses)
                    ->setFrom([Yii::$app->params['adminEmail'] => 'PODIUM FC'])
                    ->setSubject($options['Subject'])
                    ->setHtmlBody($description)
                    ->send();
            }
        }
        if (isset($options['ObjectId'])) {
            $notification->ObjectId = $options['ObjectId'];
        }

        return $notification->save();
    }

}
