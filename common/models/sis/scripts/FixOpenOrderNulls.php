<?php

namespace common\models\sis\scripts;

use common\models\sis\HasSaleMonth;
use common\models\sis\SisCampaignFilter;
use common\models\sis\SisSql;
use common\models\sis\SisSqlJoin;
use Yii;

class FixOpenOrderNulls implements ScriptInterface
{
    public static function run()
    {
        Yii::$app->db->createCommand('
            UPDATE OPENORDERS set ConfirmedDelvDate = NULL where ConfirmedDelvDate  = \'\' OR ConfirmedDelvDate  = \'NULL\' ')->execute();
    }
}