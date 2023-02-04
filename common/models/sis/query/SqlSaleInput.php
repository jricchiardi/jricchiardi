<?php

namespace common\models\sis\query;

use common\models\sis\HasSaleMonth;
use common\models\sis\SisCampaignFilter;
use common\models\sis\SisSql;
use common\models\sis\SisSqlJoin;

class SqlSaleInput extends SisSql
{
    use HasMonthlySelect;

    const TABLE_NAME = 'SaleInput';

    public function getJoin() : SisSqlJoin
    {
        $table = $this->getSql();
        $on = sprintf('Gmid.GmidId = %s.GmidId AND ClientSeller.ClientId = %s.ClientId', self::TABLE_NAME, self::TABLE_NAME);

        return SisSqlJoin::left($table, self::TABLE_NAME, $on);
    }

    private function getSql() : string
    {

        return sprintf('
        SELECT Gmid.GmidId AS GmidId,
            ClientProduct.ClientId AS ClientId,
            %s AS Total 
        FROM forecast AS Forecast 
        INNER JOIN client_product AS ClientProduct ON ClientProduct.ClientProductId = Forecast.ClientProductId 
        INNER JOIN one_gmid_per_tradeproduct AS Gmid ON Gmid.TradeProductId = ClientProduct.TradeProductId
        WHERE %s
        GROUP BY ClientProduct.ClientId, Gmid.GmidId',
            'SUM(COALESCE('.implode(',0)) + SUM(COALESCE(', $this->getSaleInputMonths()).',0))',
            sprintf('Forecast.CampaignId = %s ', SisCampaignFilter::getFilteredCampaign()->CampaignId));
    }


}