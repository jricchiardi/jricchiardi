<?php

namespace common\models\sis\query;

use common\models\sis\SisCampaignFilter;
use common\models\sis\SisSql;
use common\models\sis\SisSqlJoin;

class SqlPlan extends SisSql
{
    use HasMonthlySelect;

    const TABLE_NAME = 'Plan';



}