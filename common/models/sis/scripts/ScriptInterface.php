<?php

namespace common\models\sis\scripts;

use common\models\sis\HasSaleMonth;
use common\models\sis\SisCampaignFilter;
use common\models\sis\SisSql;
use common\models\sis\SisSqlJoin;

interface ScriptInterface
{
    public static function run();

}