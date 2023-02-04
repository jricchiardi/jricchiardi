<?php

use yii\db\Migration;

class m200517_181909_add_sp_import_marketing_products extends Migration
{
    public function safeUp()
    {
        $this->execute("
CREATE PROCEDURE [dbo].[SP_ImportMarketingProducts]
AS
    SET NOCOUNT ON;

    /****************************************** VALIDATIONS *************************************************/

    CREATE TABLE #ERRORS
    (
        TRADEPRODUCT VARCHAR(20) NULL,
        GMID         VARCHAR(20),
        DESCRIPTION  VARCHAR(150),
        CAUSE        VARCHAR(50)
    )

    -- VALIDATE GMID DUPLICATES (NESTOR SAY THAN THE PERFORMANCE AG MISC IS BAD)
INSERT #ERRORS(GMID, DESCRIPTION, CAUSE)
SELECT GMID,
       F11              AS [Description],
       'GMID DUPLICADO' AS Cause
FROM TEMP_PRODUCT
WHERE F7 <> 'AG MISC'
GROUP BY GMID, F11
HAVING COUNT(*) > 1

    -- VALIDATE DESCRIPTIONS DUPLICATES
INSERT #ERRORS(GMID, DESCRIPTION, CAUSE)
SELECT GMID,
       F11                     AS [Description],
       'DESCRIPCION DUPLICADA' AS Cause
FROM TEMP_PRODUCT
WHERE F7 <> 'AG MISC'
  AND GMID NOT IN (SELECT GMID FROM #ERRORS)
GROUP BY GMID, F11
HAVING COUNT(*) > 1

    -- VALIDATE TRADES DUPLICATED
INSERT #ERRORS(TRADEPRODUCT, DESCRIPTION, CAUSE)
SELECT td.[Trade Product],
       td.F9,
       'PERFORMANCE DIFERENTE' AS Cause
FROM (
         SELECT DISTINCT temp.[Trade Product],
                         temp.F9,
                         temp.[Performance Center]
         FROM TEMP_PRODUCT temp
         WHERE temp.F7 <> 'AG MISC'
     ) td
GROUP BY td.[Trade Product], td.F9
HAVING COUNT(*) > 1
    IF (SELECT COUNT(1)
        FROM #ERRORS) > 0
        BEGIN
            SELECT * FROM #ERRORS
        END
    ELSE
        BEGIN

            SELECT * FROM #ERRORS;

            /***************************************** PLAN AND FORECAST OF CAMPAIGN ACTUAL *******************************************/
            DECLARE @ActualCampaignId INT
            SET @ActualCampaignId = (SELECT TOP 1 CampaignId
                                     FROM campaign
                                     WHERE IsActual = 1)

            -- CLIENTS PRODUCTS
            INSERT INTO client_marketing_product(GmidId, ClientMarketingId, TradeProductId, IsForecastable)
            SELECT DISTINCT g.GmidId, cli.ClientMarketingId, g.TradeProductId, 1
            FROM gmid g
                     INNER JOIN country c
                                ON c.CountryId = g.CountryId
                     INNER JOIN client_marketing cli
                                ON cli.CountryId = c.CountryId
                     INNER JOIN trade_product t
                                ON t.TradeProductId = g.TradeProductId
                     INNER JOIN performance_center pc
                                ON pc.PerformanceCenterId = t.PerformanceCenterId
                     LEFT JOIN client_marketing_product cp
                               ON cp.GmidId = g.GmidId AND cli.ClientMarketingId = cp.ClientMarketingId
            WHERE pc.ValueCenterId <> 10111
              AND cp.GmidId IS NULL;

            INSERT INTO client_marketing_product(ClientMarketingId, TradeProductId, IsForecastable)
            SELECT DISTINCT cli.ClientMarketingId, t.TradeProductId, 1
            FROM trade_product t
                     INNER JOIN gmid g
                                ON g.TradeProductId = t.TradeProductId
                     INNER JOIN client_marketing cli
                                ON cli.CountryId = g.CountryId
                     INNER JOIN performance_center pc
                                ON pc.PerformanceCenterId = t.PerformanceCenterId
                     LEFT JOIN client_marketing_product cp
                               ON cp.TradeProductId = t.TradeProductId AND cli.ClientMarketingId = cp.ClientMarketingId
            WHERE pc.ValueCenterId = 10111
              AND cp.TradeProductId IS NULL
            GROUP BY cli.ClientMarketingId, t.TradeProductId, g.CountryId;

            DECLARE @FutureCampaignId INT
            SET @FutureCampaignId = (SELECT TOP 1 CampaignId
                                     FROM campaign
                                     WHERE IsFuture = 1);

            -- INSERTS NEWS ROWS FROM CLIENT_PRODUCT TO FORECAST
            INSERT INTO [forecast_marketing](ClientMarketingProductId, CampaignId)
            SELECT cp.ClientMarketingProductId, @ActualCampaignId
            FROM (SELECT f.ClientMarketingProductId, f.CampaignId
                  FROM [forecast_marketing] f
                  WHERE f.CampaignId = @ActualCampaignId
                 ) fore

                     RIGHT JOIN client_marketing_product cp
                                ON cp.ClientMarketingProductId = fore.ClientMarketingProductId
            WHERE fore.ClientMarketingProductId IS NULL;

        END;
        DELETE FROM TEMP_PRODUCT;
        DROP TABLE #ERRORS;
        ");
    }

    public function safeDown()
    {
        $this->execute("DROP PROCEDURE SP_ImportMarketingProducts");
    }
}
