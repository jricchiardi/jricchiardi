
-- INGRESAMOS LOS PRODUCTOS QUE FALTAN INGRESAR DE TODOS LOS PAISES PARA TODOS LOS CLIENTES

DECLARE @ClientId AS INT
DECLARE CliInfo CURSOR FOR SELECT ClientId FROM client
OPEN CliInfo
FETCH NEXT FROM CliInfo INTO @ClientId
WHILE @@fetch_status = 0
BEGIN
	 -- trades faltantes
	 INSERT INTO client_product(TradeProductId,ClientId,IsForecastable)
     SELECT falta.TradeProductId,@ClientId,0
	 FROM trade_product falta
	 LEFT JOIN
	 (
	  SELECT t.TradeProductId 
	FROM trade_product t
	INNER JOIN client_product cp
	ON  t.TradeProductId = cp.TradeProductId
	WHERE t.IsForecastable = 1 AND cp.ClientId = @ClientId
) tiene
ON falta.TradeProductId = tiene.TradeProductId
WHERE falta.IsForecastable = 1 AND tiene.TradeProductId IS NULL

-- gmid faltantes

	 INSERT INTO client_product(GmidId,TradeProductId,ClientId,IsForecastable)
     SELECT falta.GmidId,falta.TradeProductId,@ClientId,0 
FROM gmid falta
LEFT JOIN
(
	SELECT g.GmidId 
	FROM gmid g
	INNER JOIN client_product cp
	ON  g.GmidId = cp.GmidId AND g.TradeProductId = cp.TradeProductId
	WHERE g.IsForecastable = 1 AND cp.ClientId = @ClientId
) tiene
ON falta.GmidId = tiene.GmidId
WHERE falta.IsForecastable = 1 AND tiene.GmidId IS NULL

    FETCH NEXT FROM CliInfo INTO @ClientId
END
CLOSE CliInfo
DEALLOCATE CliInfo

-- RECONFIGURAMOS PLAN Y FORECAST
/*
DECLARE @CampaignId AS INT
SET @CampaignId  = 7

INSERT INTO [plan](ClientProductId,CampaignId)
SELECT falta.ClientProductId ,@CampaignId
FROM client_product falta 
LEFT JOIN
(
  SELECT cp.ClientProductId 
  FROM client_product cp
  INNER JOIN [plan] p
  ON cp.ClientProductId = p.ClientProductId
  WHERE p.CampaignId = @CampaignId
 ) tiene
 ON falta.ClientProductId = tiene.ClientProductId
WHERE tiene.ClientProductId IS NULL 



INSERT INTO [forecast](ClientProductId,CampaignId)
SELECT falta.ClientProductId ,@CampaignId
FROM client_product falta 
LEFT JOIN
(
  SELECT cp.ClientProductId 
  FROM client_product cp
  INNER JOIN [forecast] p
  ON cp.ClientProductId = p.ClientProductId
  WHERE p.CampaignId = @CampaignId
 ) tiene
 ON falta.ClientProductId = tiene.ClientProductId
WHERE tiene.ClientProductId IS NULL 

*/


