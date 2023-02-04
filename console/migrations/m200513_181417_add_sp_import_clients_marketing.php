<?php

use yii\db\Migration;

class m200513_181417_add_sp_import_clients_marketing extends Migration
{
    public function safeUp()
    {
        $this->execute("
CREATE PROCEDURE [dbo].[SP_ImportClientsMarketing] AS
SET
  NOCOUNT ON;
  /********************************************	VALIDATIONS ************************************************************/
  CREATE TABLE #ERRORS
  (
    CLIENT INT,
    DESCRIPTION VARCHAR(150),
    CAUSE VARCHAR(50)
  ) -- VALIDATE DUPLICATE CLIENT
INSERT INTO #ERRORS(CLIENT, DESCRIPTION, CAUSE)
SELECT
  [Liable Customer],
  F3,
  'CLIENTE DUPLICADO'
FROM TEMP_CLIENT_MARKETING
GROUP BY
  [Liable Customer],
  F3
HAVING
  COUNT(*) > 1;
IF (
    SELECT
      COUNT(1)
    FROM #ERRORS) > 0
      BEGIN
    SELECT
      *
    FROM #ERRORS;
  END
  ELSE BEGIN
SELECT
  *
FROM #ERRORS;
  /******************************************** COUNTRY ***************************************************/
  -- UPDATE COUNTRY
UPDATE country
SET
  Description = temp.Country
FROM country c
INNER JOIN TEMP_CLIENT_MARKETING temp ON c.Description = temp.Country -- INSERT COUNTRY
INSERT country(Description)
SELECT
  DISTINCT temp.Country
FROM TEMP_CLIENT_MARKETING temp
LEFT JOIN country c ON c.Description = temp.Country
WHERE
  c.CountryId IS NULL
  /******************************************	CLIENT_TYPE	******************************************************/
  -- UPDATE CLIENTS TYPES EXISTING
UPDATE client_type
SET
  Description = temp.Clasificacion
FROM client_type ct
INNER JOIN TEMP_CLIENT_MARKETING temp ON ct.Description = temp.Clasificacion -- INSERTS NEWS CLIENTS TYPE
INSERT INTO client_type (Description)
SELECT
  DISTINCT tc.Clasificacion
FROM TEMP_CLIENT_MARKETING tc
LEFT JOIN client_type ct ON ct.Description = tc.Clasificacion
WHERE
  ct.ClientTypeId IS NULL
  /******************************************		CLIENTS		*******************************************************/
  -- INSERT NEWS CLIENTS
INSERT INTO client_marketing(
    ClientMarketingId,
    ClientTypeId,
    IsGroup,
    CountryId,
    Description,
    IsActive
  )
SELECT
  temp.[Liable Customer],
  ct.ClientTypeId,
  0,
  cou.CountryId,
  temp.F3,
  1
FROM TEMP_CLIENT_MARKETING temp
LEFT JOIN client_type ct ON ct.Description = temp.Clasificacion
LEFT JOIN country cou ON cou.Description = temp.Country
LEFT JOIN client_marketing c ON c.ClientMarketingId = temp.[Liable Customer]
WHERE
  c.ClientMarketingId IS NULL -- UPDATE CLIENTS
UPDATE client_marketing
SET
  Description = temp.F3,
  ClientTypeId = ct.ClientTypeId,
  CountryId = cou.CountryId,
  IsGroup = 0,
  GroupId = NULL
FROM client_marketing c
INNER JOIN TEMP_CLIENT_MARKETING temp ON c.ClientMarketingId = temp.[Liable Customer]
INNER JOIN client_type ct ON ct.Description = temp.Clasificacion
INNER JOIN country cou ON cou.Description = temp.Country -- CLIENTS PRODUCTS
INSERT INTO client_marketing_product(GmidId, ClientMarketingId, TradeProductId, IsForecastable)
SELECT
  DISTINCT g.GmidId,
  cli.ClientMarketingId,
  g.TradeProductId,
  1
FROM gmid g
INNER JOIN country c ON c.CountryId = g.CountryId
INNER JOIN client_marketing cli ON cli.CountryId = c.CountryId
INNER JOIN trade_product t ON t.TradeProductId = g.TradeProductId
INNER JOIN performance_center pc ON pc.PerformanceCenterId = t.PerformanceCenterId
LEFT JOIN client_marketing_product cp ON cp.GmidId = g.GmidId
  AND cli.ClientMarketingId = cp.ClientMarketingId
WHERE
  pc.ValueCenterId <> 10111
  AND cp.GmidId IS NULL;
INSERT INTO client_marketing_product(ClientMarketingId, TradeProductId, IsForecastable)
SELECT
  DISTINCT cli.ClientMarketingId,
  t.TradeProductId,
  1
FROM trade_product t
INNER JOIN gmid g ON g.TradeProductId = t.TradeProductId
INNER JOIN client_marketing cli ON cli.CountryId = g.CountryId
INNER JOIN performance_center pc ON pc.PerformanceCenterId = t.PerformanceCenterId
LEFT JOIN client_marketing_product cp ON cp.TradeProductId = t.TradeProductId
  AND cli.ClientMarketingId = cp.ClientMarketingId
WHERE
  pc.ValueCenterId = 10111
  AND cp.TradeProductId IS NULL
GROUP BY
  cli.ClientMarketingId,
  t.TradeProductId,
  g.CountryId;
DECLARE @FutureCampaignId INT SET
  @FutureCampaignId = (
    SELECT
      TOP 1 CampaignId
    FROM campaign
    WHERE
      IsFuture = 1
  ) DECLARE @ActualCampaignId INT
SET
  @ActualCampaignId = (
    SELECT
      TOP 1 CampaignId
    FROM campaign
    WHERE
      IsActual = 1
  ) -- INSERTS NEWS ROWS FROM CLIENT_PRODUCT TO FORECAST
INSERT INTO [forecast_marketing](ClientMarketingProductId, CampaignId)
SELECT
  cp.ClientMarketingProductId,
  @ActualCampaignId
FROM (
    SELECT
      f.ClientMarketingProductId,
      f.CampaignId
    FROM [forecast_marketing] f
    WHERE
      f.CampaignId = @ActualCampaignId
  ) fore
RIGHT JOIN client_marketing_product cp ON cp.ClientMarketingProductId = fore.ClientMarketingProductId
WHERE
  fore.ClientMarketingProductId IS NULL
END;
DELETE FROM TEMP_CLIENT_MARKETING;
        ");
    }

    public function safeDown()
    {
        $this->execute("DROP PROCEDURE SP_ImportClientsMarketing");
    }
}
