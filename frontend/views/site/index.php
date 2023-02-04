<?php

use \common\models\AuthItem;

switch (\Yii::$app->user->identity->authAssignment->item_name) {
    case AuthItem::ROLE_DIRECTOR_COMERCIAL : echo $this->render('dashboards/_dashboardDIRECTORCOMERCIAL', ['results' => $results, 'dashBoardFilter' => $dashBoardFilter]);
        break;
    case AuthItem::ROLE_ADMIN : echo $this->render('dashboards/_dashboardADMIN', ['results' => $results, 'dashBoardFilter' => $dashBoardFilter]);
        break;
    case AuthItem::ROLE_DSM : echo $this->render('dashboards/_dashboardDSM', ['results' => $results, 'dashBoardFilter' => $dashBoardFilter]);
        break;
    case AuthItem::ROLE_RSM : echo $this->render('dashboards/_dashboardRSM', ['results' => $results, 'dashBoardFilter' => $dashBoardFilter]);
        break;
    case AuthItem::ROLE_SELLER : echo $this->render('dashboards/_dashboardSeller', ['results' => $results, 'dashBoardFilter' => $dashBoardFilter]);
        break;
}