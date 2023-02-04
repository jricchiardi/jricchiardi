<?php

namespace common\models\sis;

use common\models\CampaignSearch;
use Yii;

class SisCampaignFilter
{
    static $selected = null;

    static function getFilteredCampaign(){
        if(empty(self::$selected)){
            self::$selected = self::load();
        }
        return self::$selected;
    }

    static function load(){
        $selectedCampaign = Yii::$app->request->get('campaign');

        if (empty($selectedCampaign)) {
            return CampaignSearch::getActualCampaign();
        }

        return CampaignSearch::findOne(['CampaignId' => $selectedCampaign]);
    }
}