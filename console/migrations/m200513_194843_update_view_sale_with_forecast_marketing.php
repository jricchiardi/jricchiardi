<?php

use yii\db\Migration;

class m200513_194843_update_view_sale_with_forecast_marketing extends Migration
{
    public function safeUp()
    {
        $this->execute("DROP VIEW SaleWithForecastMarketing");
        $this->execute("
CREATE VIEW [dbo].[SaleWithForecastMarketing]
AS

SELECT result.*,
       'Q1'                       = (result.January + result.February + result.March),
       'Q2'                       = (result.April + result.May + result.June),
       'Q3'                       = (result.July + result.August + result.September),
       'Q4'                       = (result.October + result.November + result.December),
       'Total'                    = result.January + result.February + result.March + result.April + result.May +
                                    result.June + result.July + result.August + result.September + result.October +
                                    result.November + result.December,
       'JanuarySaleForecastUSD'   = CASE
                                        WHEN 1 < (SELECT TOP 1 Value
                                                  FROM setting
                                                  where DisplayName = 'FORECAST_ENABLE_FROM')
                                            THEN isnull(result.JanuaryUSD, 0)
                                        ELSE isnull(result.January, 0) * isnull(result.ForecastPrice, 0) END,
       'FebruarySaleForecastUSD'  = CASE
                                        WHEN 2 < (SELECT TOP 1 Value
                                                  FROM setting
                                                  where DisplayName = 'FORECAST_ENABLE_FROM')
                                            THEN isnull(result.FebruaryUSD, 0)
                                        ELSE isnull(result.February, 0) * isnull(result.ForecastPrice, 0) END,
       'MarchSaleForecastUSD'     = CASE
                                        WHEN 3 < (SELECT TOP 1 Value
                                                  FROM setting
                                                  where DisplayName = 'FORECAST_ENABLE_FROM')
                                            THEN isnull(result.MarchUSD, 0)
                                        ELSE isnull(result.March, 0) * isnull(result.ForecastPrice, 0) END,
       'AprilSaleForecastUSD'     = CASE
                                        WHEN 4 < (SELECT TOP 1 Value
                                                  FROM setting
                                                  where DisplayName = 'FORECAST_ENABLE_FROM')
                                            THEN isnull(result.AprilUSD, 0)
                                        ELSE isnull(result.April, 0) * isnull(result.ForecastPrice, 0) END,
       'MaySaleForecastUSD'       = CASE
                                        WHEN 5 < (SELECT TOP 1 Value
                                                  FROM setting
                                                  where DisplayName = 'FORECAST_ENABLE_FROM')
                                            THEN isnull(result.MayUSD, 0)
                                        ELSE isnull(result.May, 0) * isnull(result.ForecastPrice, 0) END,
       'JuneSaleForecastUSD'      = CASE
                                        WHEN 6 < (SELECT TOP 1 Value
                                                  FROM setting
                                                  where DisplayName = 'FORECAST_ENABLE_FROM')
                                            THEN isnull(result.JuneUSD, 0)
                                        ELSE isnull(result.June, 0) * isnull(result.ForecastPrice, 0) END,
       'JulySaleForecastUSD'      = CASE
                                        WHEN 7 < (SELECT TOP 1 Value
                                                  FROM setting
                                                  where DisplayName = 'FORECAST_ENABLE_FROM')
                                            THEN isnull(result.JulyUSD, 0)
                                        ELSE isnull(result.July, 0) * isnull(result.ForecastPrice, 0) END,
       'AugustSaleForecastUSD'    = CASE
                                        WHEN 8 < (SELECT TOP 1 Value
                                                  FROM setting
                                                  where DisplayName = 'FORECAST_ENABLE_FROM')
                                            THEN isnull(result.AugustUSD, 0)
                                        ELSE isnull(result.August, 0) * isnull(result.ForecastPrice, 0) END,
       'SeptemberSaleForecastUSD' =CASE
                                       WHEN 9 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(result.SeptemberUSD, 0)
                                       ELSE isnull(result.September, 0) * isnull(result.ForecastPrice, 0) END,
       'OctoberSaleForecastUSD'   = CASE
                                        WHEN 10 < (SELECT TOP 1 Value
                                                   FROM setting
                                                   where DisplayName = 'FORECAST_ENABLE_FROM')
                                            THEN isnull(result.OctoberUSD, 0)
                                        ELSE isnull(result.October, 0) * isnull(result.ForecastPrice, 0) END,
       'NovemberSaleForecastUSD'  = CASE
                                        WHEN 11 < (SELECT TOP 1 Value
                                                   FROM setting
                                                   where DisplayName = 'FORECAST_ENABLE_FROM')
                                            THEN isnull(result.NovemberUSD, 0)
                                        ELSE isnull(result.November, 0) * isnull(result.ForecastPrice, 0) END,
       'DecemberSaleForecastUSD'  = CASE
                                        WHEN 12 < (SELECT TOP 1 Value
                                                   FROM setting
                                                   where DisplayName = 'FORECAST_ENABLE_FROM')
                                            THEN isnull(result.DecemberUSD, 0)
                                        ELSE isnull(result.December, 0) * isnull(result.ForecastPrice, 0) END

FROM (
         SELECT                    f.ClientMarketingProductId,
                                   f.CampaignId,
                                   cp.ClientMarketingId,
                                   cp.IsForecastable,
                                   tp.TradeProductId,
                                   tp.Description  AS TradeProduct,
                                   tp.Price        AS TradeProductPrice,
                                   tp.profit       AS TradeProductProfit,
                                   g.GmidId,
                                   g.Description   AS GmidDescription,
                                   g.Price         AS GmidPrice,
                                   g.Profit        AS GmidProfit,
                                   g.CountryId,
                                   pc.PerformanceCenterId,
                                   pc.Description  AS PerformanceCenter,
                                   vc.ValueCenterId,
                                   vc.Description  AS ValueCenter,
                                   f.January       AS 'ForecastJanuary',
                                   f.February      AS 'ForecastFebruary',
                                   f.March         AS 'ForecastMarch',
                                   f.April         AS 'ForecastApril',
                                   f.May           AS 'ForecastMay',
                                   f.June          AS 'ForecastJune',
                                   f.July          AS 'ForecastJuly',
                                   f.August        AS 'ForecastAugust',
                                   f.September     AS 'ForecastSeptember',
                                   f.October       AS 'ForecastOctober',
                                   f.November      AS 'ForecastNovember',
                                   f.December      AS 'ForecastDecember',
                                   f.Q1            AS 'ForecastQ1',
                                   f.Q2            AS 'ForecastQ2',
                                   f.Q3            AS 'ForecastQ3',
                                   f.Q4            AS 'ForecastQ4',
                                   f.Total         AS 'ForecastTotal',
                                   sal.January     AS 'SaleJanuary',
                                   sal.February    AS 'SaleFebruary',
                                   sal.March       AS 'SaleMarch',
                                   sal.April       AS 'SaleApril',
                                   sal.May         AS 'SaleMay',
                                   sal.June        AS 'SaleJune',
                                   sal.July        AS 'SaleJuly',
                                   sal.August      AS 'SaleAugust',
                                   sal.September   AS 'SaleSeptember',
                                   sal.October     AS 'SaleOctober',
                                   sal.November    AS 'SaleNovember',
                                   sal.December    AS 'SaleDecember',
                                   sal.CampaignId  AS 'CampaignSale',
             'January'           = CASE
                                       WHEN 1 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(sal.January, 0)
                                       ELSE isnull(f.January, 0) END,
             'February'          = CASE
                                       WHEN 2 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(sal.February, 0)
                                       ELSE isnull(f.February, 0) END,
             'March'             = CASE
                                       WHEN 3 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(sal.March, 0)
                                       ELSE isnull(f.March, 0) END,
             'April'             = CASE
                                       WHEN 4 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(sal.April, 0)
                                       ELSE isnull(f.April, 0) END,
             'May'               = CASE
                                       WHEN 5 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(sal.May, 0)
                                       ELSE isnull(f.May, 0) END,
             'June'              = CASE
                                       WHEN 6 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(sal.June, 0)
                                       ELSE isnull(f.June, 0) END,
             'July'              = CASE
                                       WHEN 7 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(sal.July, 0)
                                       ELSE isnull(f.July, 0) END,
             'August'            = CASE
                                       WHEN 8 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(sal.August, 0)
                                       ELSE isnull(f.August, 0) END,
             'September'         =CASE
                                      WHEN 9 <
                                           (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                          THEN isnull(sal.September, 0)
                                      ELSE isnull(f.September, 0) END,
             'October'           = CASE
                                       WHEN 10 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(sal.October, 0)
                                       ELSE isnull(f.October, 0) END,
             'November'          = CASE
                                       WHEN 11 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(sal.November, 0)
                                       ELSE isnull(f.November, 0) END,
             'December'          = CASE
                                       WHEN 12 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(sal.December, 0)
                                       ELSE isnull(f.December, 0) END,

                                   sal.JanuaryUSD,
                                   sal.FebruaryUSD,
                                   sal.MarchUSD,
                                   sal.AprilUSD,
                                   sal.MayUSD,
                                   sal.JuneUSD,
                                   sal.JulyUSD,
                                   sal.AugustUSD,
                                   sal.SeptemberUSD,
                                   sal.OctoberUSD,
                                   sal.NovemberUSD,
                                   sal.DecemberUSD,


             ForecastDescription = CASE vc.ValueCenterId
                                       WHEN 10111 THEN tp.Description
                                       ELSE g.Description
                                       END,
             ForecastPrice       = CASE vc.ValueCenterId
                                       WHEN 10111 THEN tp.Price
                                       ELSE g.Price
                                       END,
                                   cli.Description AS Client,
                                   cli.GroupId     AS GroupId

         FROM dbo.forecast_marketing f
                  INNER JOIN client_marketing_product cp
                             ON cp.ClientMarketingProductId = f.ClientMarketingProductId
                  INNER JOIN client_marketing cli ON cp.ClientMarketingId = cli.ClientMarketingId
                  LEFT JOIN dbo.SaleFormat sal
                            ON f.ClientMarketingProductId = sal.ClientProductId and sal.CampaignId = f.CampaignId
                  INNER JOIN dbo.trade_product tp
                             ON tp.TradeProductId = cp.TradeProductId
                  LEFT JOIN gmid g
                            ON g.GmidId = cp.GmidId
                  INNER JOIN dbo.performance_center pc
                             ON pc.PerformanceCenterId = tp.PerformanceCenterId
                  INNER JOIN value_center vc
                             ON vc.ValueCenterId = pc.ValueCenterId
     ) AS result
        ");
    }

    public function safeDown()
    {
        $this->execute("DROP VIEW SaleWithForecastMarketing");
        $this->execute("
CREATE VIEW [dbo].[SaleWithForecastMarketing]
AS

SELECT result.*,
       'Q1'                       = (result.January + result.February + result.March),
       'Q2'                       = (result.April + result.May + result.June),
       'Q3'                       = (result.July + result.August + result.September),
       'Q4'                       = (result.October + result.November + result.December),
       'Total'                    = result.January + result.February + result.March + result.April + result.May +
                                    result.June + result.July + result.August + result.September + result.October +
                                    result.November + result.December,
       'JanuarySaleForecastUSD'   = CASE
                                        WHEN 1 < (SELECT TOP 1 Value
                                                  FROM setting
                                                  where DisplayName = 'FORECAST_ENABLE_FROM')
                                            THEN isnull(result.JanuaryUSD, 0)
                                        ELSE isnull(result.January, 0) * isnull(result.ForecastPrice, 0) END,
       'FebruarySaleForecastUSD'  = CASE
                                        WHEN 2 < (SELECT TOP 1 Value
                                                  FROM setting
                                                  where DisplayName = 'FORECAST_ENABLE_FROM')
                                            THEN isnull(result.FebruaryUSD, 0)
                                        ELSE isnull(result.February, 0) * isnull(result.ForecastPrice, 0) END,
       'MarchSaleForecastUSD'     = CASE
                                        WHEN 3 < (SELECT TOP 1 Value
                                                  FROM setting
                                                  where DisplayName = 'FORECAST_ENABLE_FROM')
                                            THEN isnull(result.MarchUSD, 0)
                                        ELSE isnull(result.March, 0) * isnull(result.ForecastPrice, 0) END,
       'AprilSaleForecastUSD'     = CASE
                                        WHEN 4 < (SELECT TOP 1 Value
                                                  FROM setting
                                                  where DisplayName = 'FORECAST_ENABLE_FROM')
                                            THEN isnull(result.AprilUSD, 0)
                                        ELSE isnull(result.April, 0) * isnull(result.ForecastPrice, 0) END,
       'MaySaleForecastUSD'       = CASE
                                        WHEN 5 < (SELECT TOP 1 Value
                                                  FROM setting
                                                  where DisplayName = 'FORECAST_ENABLE_FROM')
                                            THEN isnull(result.MayUSD, 0)
                                        ELSE isnull(result.May, 0) * isnull(result.ForecastPrice, 0) END,
       'JuneSaleForecastUSD'      = CASE
                                        WHEN 6 < (SELECT TOP 1 Value
                                                  FROM setting
                                                  where DisplayName = 'FORECAST_ENABLE_FROM')
                                            THEN isnull(result.JuneUSD, 0)
                                        ELSE isnull(result.June, 0) * isnull(result.ForecastPrice, 0) END,
       'JulySaleForecastUSD'      = CASE
                                        WHEN 7 < (SELECT TOP 1 Value
                                                  FROM setting
                                                  where DisplayName = 'FORECAST_ENABLE_FROM')
                                            THEN isnull(result.JulyUSD, 0)
                                        ELSE isnull(result.July, 0) * isnull(result.ForecastPrice, 0) END,
       'AugustSaleForecastUSD'    = CASE
                                        WHEN 8 < (SELECT TOP 1 Value
                                                  FROM setting
                                                  where DisplayName = 'FORECAST_ENABLE_FROM')
                                            THEN isnull(result.AugustUSD, 0)
                                        ELSE isnull(result.August, 0) * isnull(result.ForecastPrice, 0) END,
       'SeptemberSaleForecastUSD' =CASE
                                       WHEN 9 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(result.SeptemberUSD, 0)
                                       ELSE isnull(result.September, 0) * isnull(result.ForecastPrice, 0) END,
       'OctoberSaleForecastUSD'   = CASE
                                        WHEN 10 < (SELECT TOP 1 Value
                                                   FROM setting
                                                   where DisplayName = 'FORECAST_ENABLE_FROM')
                                            THEN isnull(result.OctoberUSD, 0)
                                        ELSE isnull(result.October, 0) * isnull(result.ForecastPrice, 0) END,
       'NovemberSaleForecastUSD'  = CASE
                                        WHEN 11 < (SELECT TOP 1 Value
                                                   FROM setting
                                                   where DisplayName = 'FORECAST_ENABLE_FROM')
                                            THEN isnull(result.NovemberUSD, 0)
                                        ELSE isnull(result.November, 0) * isnull(result.ForecastPrice, 0) END,
       'DecemberSaleForecastUSD'  = CASE
                                        WHEN 12 < (SELECT TOP 1 Value
                                                   FROM setting
                                                   where DisplayName = 'FORECAST_ENABLE_FROM')
                                            THEN isnull(result.DecemberUSD, 0)
                                        ELSE isnull(result.December, 0) * isnull(result.ForecastPrice, 0) END

FROM (
         SELECT                    f.ClientProductId,
                                   f.CampaignId,
                                   cp.ClientId,
                                   cp.IsForecastable,
                                   tp.TradeProductId,
                                   tp.Description  AS TradeProduct,
                                   tp.Price        AS TradeProductPrice,
                                   tp.profit       AS TradeProductProfit,
                                   g.GmidId,
                                   g.Description   AS GmidDescription,
                                   g.Price         AS GmidPrice,
                                   g.Profit        AS GmidProfit,
                                   g.CountryId,
                                   pc.PerformanceCenterId,
                                   pc.Description  AS PerformanceCenter,
                                   vc.ValueCenterId,
                                   vc.Description  AS ValueCenter,
                                   f.January       AS 'ForecastJanuary',
                                   f.February      AS 'ForecastFebruary',
                                   f.March         AS 'ForecastMarch',
                                   f.April         AS 'ForecastApril',
                                   f.May           AS 'ForecastMay',
                                   f.June          AS 'ForecastJune',
                                   f.July          AS 'ForecastJuly',
                                   f.August        AS 'ForecastAugust',
                                   f.September     AS 'ForecastSeptember',
                                   f.October       AS 'ForecastOctober',
                                   f.November      AS 'ForecastNovember',
                                   f.December      AS 'ForecastDecember',
                                   f.Q1            AS 'ForecastQ1',
                                   f.Q2            AS 'ForecastQ2',
                                   f.Q3            AS 'ForecastQ3',
                                   f.Q4            AS 'ForecastQ4',
                                   f.Total         AS 'ForecastTotal',
                                   sal.January     AS 'SaleJanuary',
                                   sal.February    AS 'SaleFebruary',
                                   sal.March       AS 'SaleMarch',
                                   sal.April       AS 'SaleApril',
                                   sal.May         AS 'SaleMay',
                                   sal.June        AS 'SaleJune',
                                   sal.July        AS 'SaleJuly',
                                   sal.August      AS 'SaleAugust',
                                   sal.September   AS 'SaleSeptember',
                                   sal.October     AS 'SaleOctober',
                                   sal.November    AS 'SaleNovember',
                                   sal.December    AS 'SaleDecember',
                                   sal.CampaignId  AS 'CampaignSale',
             'January'           = CASE
                                       WHEN 1 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(sal.January, 0)
                                       ELSE isnull(f.January, 0) END,
             'February'          = CASE
                                       WHEN 2 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(sal.February, 0)
                                       ELSE isnull(f.February, 0) END,
             'March'             = CASE
                                       WHEN 3 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(sal.March, 0)
                                       ELSE isnull(f.March, 0) END,
             'April'             = CASE
                                       WHEN 4 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(sal.April, 0)
                                       ELSE isnull(f.April, 0) END,
             'May'               = CASE
                                       WHEN 5 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(sal.May, 0)
                                       ELSE isnull(f.May, 0) END,
             'June'              = CASE
                                       WHEN 6 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(sal.June, 0)
                                       ELSE isnull(f.June, 0) END,
             'July'              = CASE
                                       WHEN 7 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(sal.July, 0)
                                       ELSE isnull(f.July, 0) END,
             'August'            = CASE
                                       WHEN 8 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(sal.August, 0)
                                       ELSE isnull(f.August, 0) END,
             'September'         =CASE
                                      WHEN 9 <
                                           (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                          THEN isnull(sal.September, 0)
                                      ELSE isnull(f.September, 0) END,
             'October'           = CASE
                                       WHEN 10 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(sal.October, 0)
                                       ELSE isnull(f.October, 0) END,
             'November'          = CASE
                                       WHEN 11 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(sal.November, 0)
                                       ELSE isnull(f.November, 0) END,
             'December'          = CASE
                                       WHEN 12 <
                                            (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')
                                           THEN isnull(sal.December, 0)
                                       ELSE isnull(f.December, 0) END,

                                   sal.JanuaryUSD,
                                   sal.FebruaryUSD,
                                   sal.MarchUSD,
                                   sal.AprilUSD,
                                   sal.MayUSD,
                                   sal.JuneUSD,
                                   sal.JulyUSD,
                                   sal.AugustUSD,
                                   sal.SeptemberUSD,
                                   sal.OctoberUSD,
                                   sal.NovemberUSD,
                                   sal.DecemberUSD,


             ForecastDescription = CASE vc.ValueCenterId
                                       WHEN 10111 THEN tp.Description
                                       ELSE g.Description
                                       END,
             ForecastPrice       = CASE vc.ValueCenterId
                                       WHEN 10111 THEN tp.Price
                                       ELSE g.Price
                                       END,
                                   cli.Description AS Client,
                                   cli.GroupId     AS GroupId

         FROM dbo.forecast_marketing f
                  INNER JOIN client_product cp
                             ON cp.ClientProductId = f.ClientProductId
                  INNER JOIN client cli ON cp.ClientId = cli.ClientId
                  LEFT JOIN dbo.SaleFormat sal
                            ON f.ClientProductId = sal.ClientProductId and sal.CampaignId = f.CampaignId
                  INNER JOIN dbo.trade_product tp
                             ON tp.TradeProductId = cp.TradeProductId
                  LEFT JOIN gmid g
                            ON g.GmidId = cp.GmidId
                  INNER JOIN dbo.performance_center pc
                             ON pc.PerformanceCenterId = tp.PerformanceCenterId
                  INNER JOIN value_center vc
                             ON vc.ValueCenterId = pc.ValueCenterId
     ) AS result");
    }
}
