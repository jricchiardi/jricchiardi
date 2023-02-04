<?php

namespace common\components\helpers;

use common\models\AuthItem;
use common\models\Campaign;
use common\models\Forecast;
use common\models\Import;
use common\models\Sale;
use Yii;
use yii\base\Component;
use \common\models\AuthItem;

class DashBoardComponent extends Component implements IDashBoard {

    public function generateDashBoard($dashBoardFilter) {
        ini_set("memory_limit", - 1);
        ini_set("max_execution_time", "9200");
        $results = array();
        $results['Campaign'] = \common\models\Campaign::getActualCampaign()->Name;
        $dashBoardFilter->CampaignId = \common\models\Campaign::getActualCampaign()->CampaignId;
        switch (\Yii::$app->user->identity->authAssignment->item_name) {
            case AuthItem::ROLE_ADMIN:
                break;
            case AuthItem::ROLE_DIRECTOR_COMERCIAL:

                break;
            case AuthItem::ROLE_DSM:
                $dashBoardFilter->DsmId = \Yii::$app->user->identity->UserId;
                break;
            case AuthItem::ROLE_RSM:
                $dashBoardFilter->RsmId = \Yii::$app->user->identity->UserId;
                break;
            case AuthItem::ROLE_SELLER:
                $dashBoardFilter->SellerId = \Yii::$app->user->identity->UserId;
                break;
        }

        $results['lastDateSale'] = \common\models\Import::find()->select(['CreatedAt'])->where(['TypeImportId' => \common\models\TypeImport::SALE])->orderBy('CreatedAt DESC')->asArray()->one()['CreatedAt'];
        $results['lastDateCyo'] = \common\models\Import::find()->select(['CreatedAt'])->where(['TypeImportId' => \common\models\TypeImport::CyO])->orderBy('CreatedAt DESC')->asArray()->one()['CreatedAt'];
        $results['campaigns'] = \common\models\Campaign::getAll($dashBoardFilter);
        $results['sales'] = \common\models\Sale::getDashHistorySales($dashBoardFilter);
        $results['distribution'] = \common\models\Forecast::getDashDistribution($dashBoardFilter);
        $results['resume'] = \common\models\Forecast::getDashTableResume($dashBoardFilter);
        $results['profit'] = \common\models\Forecast::getDashProfit($dashBoardFilter);

        return $results;
    }

}
