<?php

use yii\db\Migration;

class m200427_175707_create_view_export_consolid_marketing extends Migration
{
    public function safeUp()
    {
        $this->execute("
CREATE VIEW [dbo].[ExportConsolidMarketing]
AS
SELECT    fs.CampaignId,
          coun.Description       AS Pais,
          pm.UserId              AS 'Product Manager',
          pm.Fullname            AS 'Nombre Product Manager',
          fs.ClientId            AS 'Cliente',
          fs.Client              AS 'Nombre Cliente',
          ct.Description         AS 'Clasificacion',
          fs.ValueCenter         AS 'Value Center',
          fs.TradeProductId      AS 'Trade Product',
          fs.TradeProduct        as 'Nombre Trade Product',
          fs.PerformanceCenterId AS 'Performance',
          fs.PerformanceCenter   AS 'Nombre Performance',
          fs.GmidId              AS 'GMID',
          fs.GmidDescription     AS 'Nombre GMID',
          (CASE
               WHEN T.[Month] = 'January' THEN 1
               WHEN T.[Month] = 'February' THEN 2
               WHEN T.[Month] = 'March' THEN 3
               WHEN T.[Month] = 'April' THEN 4
               WHEN T.[Month] = 'May' THEN 5
               WHEN T.[Month] = 'June' THEN 6
               WHEN T.[Month] = 'July' THEN 7
               WHEN T.[Month] = 'August' THEN 8
               WHEN T.[Month] = 'September' THEN 9
               WHEN T.[Month] = 'October' THEN 10
               WHEN T.[Month] = 'November' THEN 11
               WHEN T.[Month] = 'December' THEN 12
              END)               AS 'MES',
    'Q' = NULL,
          fs.ForecastPrice       AS 'Precio',
          T.Volume               AS 'Volumen',
          (
              CASE
                  WHEN T.[Month] = 'January' THEN fs.JanuarySaleForecastUSD
                  WHEN T.[Month] = 'February' THEN fs.FebruarySaleForecastUSD
                  WHEN T.[Month] = 'March' THEN fs.MarchSaleForecastUSD
                  WHEN T.[Month] = 'April' THEN fs.AprilSaleForecastUSD
                  WHEN T.[Month] = 'May' THEN fs.MaySaleForecastUSD
                  WHEN T.[Month] = 'June' THEN fs.JuneSaleForecastUSD
                  WHEN T.[Month] = 'July' THEN fs.JulySaleForecastUSD
                  WHEN T.[Month] = 'August' THEN fs.AugustSaleForecastUSD
                  WHEN T.[Month] = 'September' THEN fs.SeptemberSaleForecastUSD
                  WHEN T.[Month] = 'October' THEN fs.OctoberSaleForecastUSD
                  WHEN T.[Month] = 'November' THEN fs.NovemberSaleForecastUSD
                  WHEN T.[Month] = 'December' THEN fs.DecemberSaleForecastUSD
                  END
              )                  AS 'USD'

FROM (
         SELECT CampaignId,
                ClientProductId,
                Volume,
                [Month]
         FROM SaleWithForecastMarketing
                  UNPIVOT
                  (
                  Volume FOR [Month] IN (January,February,March,April,May,June,July,August,September,October,November,December)
                  ) AS p
     ) AS T
         INNER JOIN SaleWithForecastMarketing fs
                    ON fs.ClientProductId = T.ClientProductId AND T.CampaignId = fs.CampaignId
         INNER JOIN client c
                    ON c.ClientId = fs.ClientId
         INNER JOIN country coun
                    ON coun.CountryId = c.CountryId
         INNER JOIN pm_product pmp
                    ON pmp.TradeProductId = fs.TradeProductId AND pmp.GmidId = fs.GmidId
         INNER JOIN [user] pm
                    ON pm.UserId = pmp.UserId
         LEFT JOIN [client_type] ct
                   ON ct.ClientTypeId = c.ClientTypeId

        ");
    }

    public function safeDown()
    {
        $this->execute("DROP VIEW ExportConsolidMarketing");
    }
}
