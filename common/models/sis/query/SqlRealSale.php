<?php

namespace common\models\sis\query;

use common\models\sis\HasSaleMonth;
use common\models\sis\SisCampaignFilter;
use common\models\sis\SisSql;
use common\models\sis\SisSqlJoin;

class SqlRealSale extends SisSql
{
    use HasMonthlySelect;

    const TABLE_NAME = 'RealSale';

    public function getJoin() : SisSqlJoin
    {
        $table = $this->getSql();
        $on = sprintf('Gmid.GmidId = %s.GmidId AND ClientSeller.ClientId = %s.ClientId', self::TABLE_NAME, self::TABLE_NAME);

        return SisSqlJoin::left($table, self::TABLE_NAME, $on);
    }

    private function getSql() : string
    {
        return sprintf('
            SELECT GmidId, ClientId, SUM(Amount) as Total
            FROM sale
            WHERE CampaignId = %s AND Month IN (%s) 
            GROUP BY GmidId, ClientId',
            SisCampaignFilter::getFilteredCampaign()->CampaignId,
            implode(',', $this->getSaleInputMonthsNumber())
        );
    }

}