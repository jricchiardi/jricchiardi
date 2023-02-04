<?php

namespace common\models\sis\query;

use common\models\sis\SisCampaignFilter;
use common\models\sis\SisSql;
use common\models\sis\SisSqlJoin;

class SqlForecast extends SisSql
{
    use HasMonthlySelect;

    const TABLE_NAME = 'Forecast';

    public function getJoin() : SisSqlJoin
    {
        $table = $this->getSql();
        $on = sprintf('Gmid.GmidId = %s.GmidId AND ClientSeller.ClientId = %s.ClientId AND Client.CountryId = %s.CountryId', self::TABLE_NAME, self::TABLE_NAME, self::TABLE_NAME);
        return SisSqlJoin::left($table, self::TABLE_NAME, $on);
    }

    private function getSql() : string
    {

        return sprintf('
                SELECT GmidId, CountryId, ClientId, %s as Total
                FROM forecastibp_by_client_product
                WHERE CampaignId = %s',
            implode(' + ', $this->getSaleInputMonths()),
            SisCampaignFilter::getFilteredCampaign()->CampaignId
        );
    }


}