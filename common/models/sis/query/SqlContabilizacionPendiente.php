<?php

namespace common\models\sis\query;

use common\models\sis\SisSql;
use common\models\sis\SisSqlJoin;

class SqlContabilizacionPendiente extends SisSql
{
    const TABLE_NAME = 'ContPendiente';

    private $negativeCodes = [
        "ZRR","ZRE","ZARE","ZRD"
    ];

    public function getJoin() : SisSqlJoin
    {
        $table = $this->getSql();
        $on = sprintf('Gmid.GmidId = %s.GmidId AND ClientSeller.ClientId = %s.ClientId', self::TABLE_NAME, self::TABLE_NAME);

        return SisSqlJoin::left($table, self::TABLE_NAME, $on);
    }

    private function getSql() : string
    {
        $negativeCodesString = "'".implode("','", $this->negativeCodes)."'";

        return sprintf('
            SELECT %s AS GmidId,
                %s AS ClientId,
                SUM((CASE WHEN BillingType IN ( %s ) THEN 1 ELSE -1 END)*BilledQ) as Total
            FROM FCNOCONT
            GROUP BY %s, %s',
            Convert::materialCodeToGmid('MaterialCode'),
            Convert::int('SoldToPartyNumber'),
            $negativeCodesString,
            Convert::materialCodeToGmid('MaterialCode'),
            Convert::int('SoldToPartyNumber')
        );

    }

    public function getMetaTable($clientIds = [], $gmidIds = []) : string
    {
        $clientIds = implode(',', $clientIds);
        $gmidIds = implode(',', $gmidIds);

        return sprintf('
                SELECT
                    SalesOrg,
                    BillingNo,
                    BillingType,
                    SoldToPartyNumber,
                    SoldToPartyName,
                    Item,
                    MaterialCode,
                    MaterialDescript,
                    BilledQ,
                    BaseUoM,
                    BillingDate
                    FROM
                        FCNOCONT
                    INNER JOIN unificacion_cliente_deduplicated uc ON uc.SoldToParty = CASE WHEN IsNumeric(SoldToPartyNumber)= 1 THEN CONVERT(INT, SoldToPartyNumber) ELSE null END
                    WHERE
                        CASE WHEN IsNumeric(REPLACE(MaterialCode, \'D\', \'\'))= 1 THEN CONVERT(INT, REPLACE(MaterialCode, \'D\', \'\')) ELSE null END in (%s)
                         AND uc.ConversionCode IN (%s)', $gmidIds, $clientIds);

    }
}