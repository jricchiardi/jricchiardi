<?php

namespace frontend\controllers;

class ReportController extends \common\components\controllers\CustomController {

    public function actionResume() {

        $reportModel = new \common\models\ReportSearch();
        
        $reportModel->CampaignId = \common\models\Campaign::getActualCampaign()->CampaignId;
        $reportModel->CampaignFutureId = \common\models\Campaign::getActualCampaign()->CampaignId;
        
        if (\Yii::$app->user->can(\common\models\AuthItem::ROLE_RSM)) {
            $reportModel->RsmId = \Yii::$app->user->identity->UserId;
        }

        if (\Yii::$app->user->can(\common\models\AuthItem::ROLE_DSM)) {
            $reportModel->DsmId = \Yii::$app->user->identity->UserId;
        }
        
        $results = $reportModel->reportResume(\Yii::$app->request->queryParams);
        
        return $this->render('resume', ['results' => $results,
                    'reportModel' => $reportModel]);
    }

}
