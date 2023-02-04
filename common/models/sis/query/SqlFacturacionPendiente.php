<?php

namespace common\models\sis\query;

use common\models\sis\SisSql;
use common\models\sis\SisSqlJoin;

class SqlFacturacionPendiente extends SisSql
{

    const TABLE_NAME = 'FactPendiente';

    private $negativeCodes = [
        "ZRE7", "ZRE", "ZARE", "ZREF",
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

        return sprintf('SELECT %s AS GmidId,
        %s AS ClientId,
        SUM((CASE WHEN SalesDocType IN (%s) THEN 1 ELSE -1 END)*DeliveryQ) as Total
        FROM DESPNOFC 
        GROUP BY %s, %s',
            Convert::materialCodeToGmid('MaterialCode'),
            Convert::int('SoldToCustNumber'),
            $negativeCodesString,
            Convert::materialCodeToGmid('MaterialCode'),
            Convert::int('SoldToCustNumber'));

    }

    public function getMetaTable($clientIds = [], $gmidIds = []) : string
    {
        $clientIds = implode(',', $clientIds);
        $gmidIds = implode(',', $gmidIds);

        return sprintf('
                SELECT
                    SalesDoc,
                    SalesItem,
                    SalesDocType,
                    SoldToCustNumber,
                    SoldToCustName,
                    MaterialCode,
                    MaterialDescript,
                    DeliveryQ,
                    SalesUoM
                    FROM
                        DESPNOFC
                    INNER JOIN unificacion_cliente_deduplicated uc ON uc.SoldToParty = CASE WHEN IsNumeric(SoldToCustNumber)= 1 THEN CONVERT(INT, SoldToCustNumber) ELSE null END
                    WHERE
                        CASE WHEN IsNumeric(REPLACE(MaterialCode, \'D\', \'\'))= 1 THEN CONVERT(INT, REPLACE(MaterialCode, \'D\', \'\')) ELSE null END in (%s)
                         AND uc.ConversionCode IN (%s)', $gmidIds, $clientIds);

    }

}