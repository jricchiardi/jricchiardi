<?php

namespace common\models\sis\query;

use common\models\sis\HasSaleMonth;
use common\models\sis\SisCampaignFilter;
use common\models\sis\SisSql;
use common\models\sis\SisFilters;
use common\models\sis\SisSqlJoin;
use Yii;

class SqlOpenOrder extends SisSql
{
    const TABLE_NAME = 'OpenOrders';

    public function getSelect() : array
    {
        return [
            sprintf('SUM(COALESCE(%s.Pedidos,0)) AS Pedidos',self::TABLE_NAME),
            sprintf('SUM(COALESCE(%s.PedidosFuturo,0)) AS PedidosFuturos',self::TABLE_NAME),
        ];
    }

    public function getJoin() : SisSqlJoin
    {
        $table = $this->getSql();
        $on = sprintf('Report.GmidId = %s.GmidId AND Report.ClientId = %s.ClientId  AND Report.CampaignId = %s ', self::TABLE_NAME, self::TABLE_NAME,
            SisCampaignFilter::getFilteredCampaign()->CampaignId);

        return SisSqlJoin::left($table, self::TABLE_NAME, $on);
    }

    private function getSql() : string
    {
        return sprintf('
            SELECT %s AS GmidId,
                (select TOP 1 uc.ConversionCode FROM unificacion_cliente_deduplicated uc WHERE uc.SoldToParty = %s) AS ClientId,
                SUM(CASE WHEN NOT ConfirmedDelvDate IS NULL AND (DATEADD(day, %s, GETDATE()) >= convert(date, ConfirmedDelvDate, 103)) THEN OpenQConfirmedQ ELSE 0 END) as Pedidos,
                SUM(CASE WHEN ConfirmedDelvDate IS NULL OR (DATEADD(day, %s, GETDATE()) < convert(date, ConfirmedDelvDate, 103)) THEN OpenQConfirmedQ ELSE 0 END) as PedidosFuturo
            FROM OPENORDERS
            WHERE ConfirmedDelvDate IS NOT NULL OR ConfirmedDelvDate IS NULL
            GROUP BY %s, %s',
            Convert::materialCodeToGmid('MaterialCode'),
            Convert::int('SoldToCustNumber'),
            $this->getFilterDays(),
            $this->getFilterDays(),
            Convert::materialCodeToGmid('MaterialCode'),
            Convert::int('SoldToCustNumber'));
    }

    public function getHaving() : array
    {
        return [
            sprintf('SUM(COALESCE(%s.Pedidos,0)) != 0', self::TABLE_NAME),
            sprintf('SUM(COALESCE(%s.PedidosFuturo,0)) != 0', self::TABLE_NAME),
        ];
    }

    public function getFilterDays(){
        return Yii::$app->request->get('days') ?? 10;
    }

    public function getMetaTable($clientIds = [], $gmidIds = []) : string
    {
        $clientIds = implode(',', $clientIds);
        $gmidIds = implode(',', $gmidIds);
        if((new SisFilters())->getFilterColumn() == 'Pedidos') {
            $dateQuery = sprintf("(ConfirmedDelvDate IS NOT NULL AND (DATEADD(day, %s, GETDATE()) >= convert(date, ConfirmedDelvDate, 103)))", $this->getFilterDays());
        } else {
            $dateQuery = sprintf("(ConfirmedDelvDate IS NULL OR (DATEADD(day, %s, GETDATE()) < convert(date, ConfirmedDelvDate, 103)))", $this->getFilterDays());
        }

        return sprintf('
                SELECT
                SalesOrg,
                Item,
                OrderNo,
                DelivNo,
                CredBlock,
                OrderType,
                SoldToCustNumber,
                SoldToCustName,
                MaterialCode,
                MaterialDescript,
                PlantCode,
                OpenQConfirmedQ,
                OrderQ,
                SalesUoM,
                ConfirmedDelvDate,
                ShipToCustNumber,
                ShipToCustName,
                CustPurchaseOrdNo,
                ConfirmedShipDate
                    FROM
                        OPENORDERS
                    INNER JOIN unificacion_cliente_deduplicated uc ON uc.SoldToParty = CASE WHEN IsNumeric(SoldToCustNumber)= 1 THEN CONVERT(INT, SoldToCustNumber) ELSE null END
                    WHERE
                        %s AND
                        CASE WHEN IsNumeric(REPLACE(MaterialCode, \'D\', \'\'))= 1 THEN CONVERT(INT, REPLACE(MaterialCode, \'D\', \'\')) ELSE null END in (%s)
                         AND uc.ConversionCode IN (%s)', $dateQuery, $gmidIds, $clientIds);

    }
}