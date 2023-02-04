<?php

namespace console\controllers;

use yii\console\Controller;
use Yii;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JobController
 *
 * @author theseedgruru_5
 */
class JobController extends Controller {

    // The command "yii job/set-campaign-actual" executed all days
    public function actionSetCampaignActual() {

        $connection = \Yii::$app->db;
        $campaign = \common\models\Campaign::getActualCampaign();
        $beginCampaign = new \DateTime($campaign->DateBeginCampaign);
        $beginCampaignDay = (int) $beginCampaign->format('d');
        $beginCampaignMonth = (int) $beginCampaign->format('m');
        $beginCampaignYear = (int) $beginCampaign->format('Y');
        $hoy = getdate();
        if ($hoy["mon"] == $beginCampaignMonth && $hoy["mday"] == $beginCampaignDay && $beginCampaignYear == $hoy["year"]) {

            // copy values from plan to forecast
            $connection->createCommand("UPDATE forecast
SET              
       [January] = p.[January]
      ,[February] = p.[February]
      ,[March] = p.[March]
      ,[Q1] = p.[Q1]
      ,[April] = p.[April]
      ,[May] = p.[May]
      ,[June] = p.[June]
      ,[Q2] = p.[Q2]
      ,[July] = p.[July]
      ,[August] = p.[August]
      ,[September] = p.[September]
      ,[Q3] = p.[Q3]
      ,[October] = p.[October]
      ,[November] = p.[November]
      ,[December] = p.[December]
      ,[Q4] = p.[Q4]
      ,[Total] = p.[Total]
FROM forecast f 
INNER JOIN [plan] p 
ON p.ClientProductId = f.ClientProductId
WHERE f.CampaignId = (SELECT TOP 1 CampaignId FROM campaign WHERE IsFuture = 1) AND 
	  p.CampaignId = (SELECT TOP 1 CampaignId FROM campaign WHERE IsFuture= 1) 

  ")->execute();

            \common\models\Campaign::updateAll(['IsActual' => false], ['IsActual' => true]);
            \common\models\Campaign::updateAll(['IsActual' => true, 'IsFuture' => false], ['IsFuture' => true]);
            $message = "La campaña se cambio corrrectamente";
        } else
            $message = "No es el dia o mes o anio para cambiar de campania";
        return $this->stdout($message);
    }

    // The command "yii job/create-snapshot-forecast" executed last day of month
    public function actionCreateSnapshotForecast() {
        $message = "";

        if (\Yii::$app->utilcomponents->isLock()) {
            $connection = \Yii::$app->db;
            $connection->createCommand("EXEC CreateSnapshotForecast")->execute();
            $message = "Se creo una nueva version de Snapshot";
        } else
            $message = "Error al crear el snapshot";
        return $this->stdout($message);
    }


    // The command "yii job/create-sis-report" executed every hour
    public function actionCreateSisReport() {
        $this->stdout("Iniciando ejecucion\n");
        try{
            $connection = \Yii::$app->db;
            $connection->createCommand("EXEC SP_Run_Sis_Report")->execute();
            $this->stdout("Se creo el reporte SIS\n");

            return self::EXIT_CODE_NORMAL;
        } catch (Exception $e) {
            $this->stdout("Ocurrió un error inesperado durante la ejecución\n", Console::FG_YELLOW, Console::ITALIC);
            $this->stdout($e->getMessage());
            return self::EXIT_CODE_ERROR;
		}
			
        return $this->stdout($message);
    }

    // The command "yii job/notify-lock-forecast" executed all days
    public function actionNotifyLockForecast() {
        $message = "";

        $tommorow = new \DateTime("now");
        $tommorow->add(new \DateInterval('P7D'));
        $tommorow->setTime(0, 0, 0);

        $locks = \common\models\LockForecast::find()->asArray()->all();
        $isLock = false;
        foreach ($locks as $lock) {
            $from = new \DateTime($lock["DateFrom"]);
            $to = new \DateTime($lock["DateTo"]);
            $from->setTime(0, 0, 0);
            $to->setTime(0, 0, 0);

            if ($from <= $tommorow && $to >= $tommorow) {
                $isLock = true;
            }
        }
        // si mañana se bloquea el forecast then
        if ($isLock) {
            \common\models\Notification::deleteAll("Description like '%El forecast se bloquea el%' ");
            $options = ['Description' => 'El forecast se bloquea el : ' . $tommorow->format('d/m/Y'),
                'FromUserId' => \common\models\User::find()->where(['Username' => 'admin'])->one()->UserId,
                'ToProfileId' => \common\models\AuthItem::ROLE_SELLER,
                'WithEmail' => true,
                'send_notification_mail' => true,
                'Subject' => 'PODIUM - Forecast'
            ];

            Yii::$app->notificationscomponents->createNotification($options);

            $message = "Se notifico a todos los vendedores que mañana se bloquea el forecast";
        }

        return $this->stdout($message);
    }

    // The command "yii job/notify-enable-plan" executed all days
    public function actionNotifyEnablePlan() {
        $message = "";

        $today = new \DateTime("now");
        $today->setTime(0, 0, 0);

        $campaign = \common\models\Campaign::getActualCampaign();
        $planFrom = new \DateTime($campaign->PlanDateFrom);
        $planFrom->setTime(0, 0, 0);

        if ($planFrom == $today) {
            \common\models\Notification::deleteAll("Description like '%Puede cargar el plan a partir del%' ");
            $options = ['Description' => 'Puede cargar el plan a partir del ' . $today->format('d/m/Y'),
                'FromUserId' => \common\models\User::find()->where(['Username' => 'admin'])->one()->UserId,
                'ToProfileId' => \common\models\AuthItem::ROLE_SELLER,
                'WithEmail' => true,
                'send_notification_mail' => true,
                'Subject' => 'PODIUM - Forecast'
            ];

            Yii::$app->notificationscomponents->createNotification($options);

            $message = "Se notifico a todos los vendedores que hoy se puede cargar el plan sin problemas";
        }

        return $this->stdout($message);
    }

    // The command "yii job/notify-lock-plan" executed all days
    public function actionNotifyLockPlan() {
        $message = "";

        $tommorow = new \DateTime("now");
        $tommorow->add(new \DateInterval('P7D'));
        $tommorow->setTime(0, 0, 0);


        $campaign = \common\models\Campaign::getActualCampaign();
        $planFrom = new \DateTime($campaign->PlanSettingDateFrom);
        $planFrom->setTime(0, 0, 0);

        if ($planFrom == $tommorow) {
            \common\models\Notification::deleteAll("Description like '%El plan se bloquea el%' ");
            $options = ['Description' => 'El plan se bloquea el ' . $tommorow->format('d/m/Y'),
                'FromUserId' => \common\models\User::find()->where(['Username' => 'admin'])->one()->UserId,
                'ToProfileId' => \common\models\AuthItem::ROLE_SELLER,
                'WithEmail' => true,
                'send_notification_mail' => true,
                'Subject' => 'PODIUM - Forecast'
            ];

            Yii::$app->notificationscomponents->createNotification($options);


            $message = "Se notifico a todos los vendedores que mañana se bloquea el plan";
        }

        return $this->stdout($message);
    }

    // The command "yii job/notify-validation-plan" executed all days
    public function actionNotifyValidationPlan() {
        $message = "";

        $tommorow = new \DateTime("now");
        $tommorow->add(new \DateInterval('P7D'));
        $tommorow->setTime(0, 0, 0);


        $campaign = \common\models\Campaign::getActualCampaign();
        $planTo = new \DateTime($campaign->PlanSettingDateTo);
        $planTo->setTime(0, 0, 0);

        if ($planTo == $tommorow) {
            \common\models\Notification::deleteAll("Description like '%La validación del plan finaliza el%' ");
            $options = ['Description' => 'La validación del plan finaliza el ' . $tommorow->format('d/m/Y'),
                'FromUserId' => \common\models\User::find()->where(['Username' => 'admin'])->one()->UserId,
                'ToProfileId' => \common\models\AuthItem::ROLE_ADMIN,
                'WithEmail' => true,
                'send_notification_mail' => true,
                'Subject' => 'PODIUM - Forecast'
            ];

            Yii::$app->notificationscomponents->createNotification($options);


            $message = "Se notifico a todos los usuarios administradores que mañana finaliza la fecha de validacion de plan";
        }

        return $this->stdout($message);
    }

}
