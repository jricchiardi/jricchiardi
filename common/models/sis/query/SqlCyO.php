<?php

namespace common\models\sis\query;

use common\models\sis\HasSaleMonth;
use common\models\sis\SisCampaignFilter;
use common\models\sis\SisSql;
use common\models\sis\SisSqlJoin;

class SqlCyO extends SisSql
{
    use HasSaleMonth;

    const TABLE_NAME = 'CyO';

    public function getJoin() : SisSqlJoin
    {
        $table = $this->getSql();
        $on = sprintf('Gmid.GmidId = %s.GmidId AND ClientSeller.ClientId = %s.ClientId', self::TABLE_NAME, self::TABLE_NAME);

        return SisSqlJoin::left($table, self::TABLE_NAME, $on);
    }

    private function getSql() : string
    {
        return sprintf('
            SELECT GmidId, ClientId, SUM(InventoryBalance) as Total
            FROM cyo 
            WHERE CampaignId = %s 
            GROUP BY GmidId, ClientId',
            SisCampaignFilter::getFilteredCampaign()->CampaignId);
    }

}