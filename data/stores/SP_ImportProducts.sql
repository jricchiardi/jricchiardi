
ALTER PROCEDURE SP_ImportProducts
AS
	SET NOCOUNT ON;


	/****************************************** VALIDATIONS *************************************************/
	
	CREATE TABLE #ERRORS
	(
	    TRADEPRODUCT VARCHAR(20) NULL,
		GMID VARCHAR(20),
		DESCRIPTION VARCHAR(150),
		CAUSE VARCHAR(50)
	)

	-- VALIDATE GMID DUPLICATES (NESTOR SAY THAN THE PERFORMANCE AG MISC IS BAD)
	INSERT #ERRORS(GMID,DESCRIPTION,CAUSE)
 	SELECT GMID ,
		   F11 AS [Description], 
		   'GMID DUPLICADO' AS Cause 
	 FROM TEMP_PRODUCT
	 WHERE F7 <> 'AG MISC'
	 GROUP BY GMID,F11
	 HAVING COUNT(*)>1

	 -- VALIDATE DESCRIPTIONS DUPLICATES 
	 INSERT #ERRORS(GMID,DESCRIPTION,CAUSE)
	 SELECT GMID ,
			F11 AS [Description] ,
			'DESCRIPCION DUPLICADA'  AS Cause
	 FROM TEMP_PRODUCT
	 WHERE F7 <> 'AG MISC' AND GMID NOT IN(SELECT GMID FROM #ERRORS)
	 GROUP BY GMID,F11
	 HAVING COUNT(*)>1

	 -- VALIDATE TRADES DUPLICATED
	   INSERT #ERRORS(TRADEPRODUCT,DESCRIPTION,CAUSE)
	   SELECT  td.[Trade Product] , 
			   td.F9,			 
			   'PERFORMANCE DIFERENTE'  AS Cause
	  FROM 
	  (
	   SELECT DISTINCT temp.[Trade Product] , 
				temp.F9 ,		
				temp.[Performance Center]									
	  FROM TEMP_PRODUCT temp
	  WHERE  temp.F7 <> 'AG MISC'
	  ) td	 
	  GROUP BY td.[Trade Product] , td.F9 
	  HAVING COUNT(*) >1

	IF (SELECT COUNT(1) FROM #ERRORS)> 0 BEGIN 
		SELECT * FROM #ERRORS
	END	 
	ELSE BEGIN

	SELECT * FROM #ERRORS;	

/******************************************** COUNTRY ***************************************************/

-- UPDATE COUNTRY
UPDATE country SET	Description = temp.F2 , 
					Abbreviation = temp.Country
FROM country c
INNER JOIN TEMP_PRODUCT temp
ON c.Description = temp.F2


-- INSERT COUNTRY
INSERT country(Description,Abbreviation)
SELECT DISTINCT temp.F2 ,temp.Country
FROM TEMP_PRODUCT temp
LEFT JOIN country c
ON c.Description = temp.F2
WHERE c.CountryId IS NULL


/******************************************** VALUE_CENTER ***************************************************/

-- UPDATE VALUES CENTERS
UPDATE value_center SET Description = temp.F4 , 
						Abbreviation = temp.F5
FROM value_center vc
INNER JOIN TEMP_PRODUCT temp
ON vc.ValueCenterId = temp.ValueCenter


-- INSERT NEWS VALUES CENTERS 
INSERT value_center(ValueCenterId,Description,Abbreviation)
SELECT DISTINCT temp.ValueCenter ,temp.F4 ,temp.F5
FROM TEMP_PRODUCT temp
LEFT JOIN value_center vc
ON vc.ValueCenterId = temp.ValueCenter
WHERE vc.ValueCenterId IS NULL

/******************************************** PERFORMANCE_CENTER ***************************************************/


-- UPDATES PERFORMANCES CENTERS
UPDATE performance_center SET Description = temp.F7 , 
							  ValueCenterId = temp.ValueCenter
FROM performance_center pc
INNER JOIN TEMP_PRODUCT temp
ON pc.PerformanceCenterId = temp.[Performance Center]




-- INSERT NEWS PERFORMANCES CENTERS
INSERT performance_center(PerformanceCenterId,Description,ValueCenterId)
SELECT DISTINCT temp.[Performance Center] ,temp.F7 ,temp.ValueCenter
FROM TEMP_PRODUCT temp
LEFT JOIN performance_center pc
ON pc.PerformanceCenterId = temp.[Performance Center]
WHERE pc.PerformanceCenterId IS NULL

/******************************************** TRADE_PRODUCT ***************************************************/

-- UPDATES TRADES PRODUCTS
UPDATE trade_product SET Description = temp.F9 , 
					     PerformanceCenterId = temp.[Performance Center],
						 Price = (SELECT MIN(tem.Precio) FROM  TEMP_PRODUCT tem WHERE tem.[Trade Product] = temp.[Trade Product]),
						 Profit = (SELECT MIN(tem.Margen) FROM  TEMP_PRODUCT tem WHERE tem.[Trade Product] = temp.[Trade Product])						 

FROM trade_product tp
INNER JOIN TEMP_PRODUCT temp
ON tp.TradeProductId = temp.[Trade Product]
WHERE  temp.F7 <> 'AG MISC'


-- INSERT NEWS TRADES PRODUCTS
INSERT trade_product(TradeProductId,Description,PerformanceCenterId,Price,Profit,IsForecastable)
SELECT DISTINCT temp.[Trade Product] , 
				temp.F9 ,
				temp.[Performance Center],
				Precio = (SELECT MIN(tem.Precio) FROM  TEMP_PRODUCT tem WHERE tem.[Trade Product] = temp.[Trade Product]),
				Margen = (SELECT MIN(tem.Margen) FROM  TEMP_PRODUCT tem WHERE tem.[Trade Product] = temp.[Trade Product]),
				IsForecastable = CASE WHEN temp.F4 = 'SEEDS' THEN 1 ELSE 0 END
FROM TEMP_PRODUCT temp
LEFT JOIN trade_product tp
ON tp.TradeProductId = temp.[Trade Product]
WHERE tp.TradeProductId IS NULL AND temp.F7 <> 'AG MISC';

--INTO #NEW_TRADES 
--INSERT trade_product(TradeProductId,Description,PerformanceCenterId,Price,Profit,IsForecastable)
--SELECT [Trade Product],F9,[Performance Center],Precio,Margen,IsForecastable FROM #NEW_TRADES;


/******************************************** GMID ***************************************************/



-- UPDATES GMIDs
UPDATE gmid SET Description = temp.F11 , 
					     TradeProductId = temp.[Trade Product],
						 Price = temp.Precio,
						 Profit = temp.Margen,
						 CountryId = (SELECT TOP 1 CountryId FROM country WHERE Description = temp.F2)					 

FROM gmid g
INNER JOIN TEMP_PRODUCT temp
ON g.GmidId = temp.GMID
WHERE temp.F7 <> 'AG MISC'



-- INSERT NEWS GMIDs
INSERT gmid(GmidId,Description,TradeProductId,Price,Profit,CountryId,IsForecastable)
SELECT DISTINCT temp.GMID , temp.F11 ,temp.[Trade Product],temp.Precio,temp.Margen,(SELECT TOP 1 CountryId 
																					FROM country 
																					WHERE Description = temp.F2) AS Country,
					IsForecastable = CASE WHEN temp.F4 <> 'SEEDS' THEN 1 ELSE 0 END
FROM TEMP_PRODUCT temp
LEFT JOIN gmid g
ON g.GmidId = temp.GMID
WHERE g.GmidId IS NULL AND temp.F7 <> 'AG MISC'


--INTO #NEW_GMIDS
--SELECT GMID,F11,[Trade Product],Precio,Margen,Country,IsForecastable 
--FROM #NEW_GMIDS




/***************************************** PLAN AND FORECAST OF CAMPAIGN ACTUAL *******************************************/

DECLARE @ActualCampaignId INT
SET @ActualCampaignId = (SELECT TOP 1 CampaignId FROM campaign WHERE IsActual = 1)

-- CLIENTS PRODUCTS


INSERT INTO client_product(GmidId,ClientId,TradeProductId,IsForecastable)
SELECT DISTINCT g.GmidId, cli.ClientId,g.TradeProductId,1
FROM gmid g
INNER JOIN country c
ON c.CountryId = g.CountryId
INNER JOIN client cli
ON cli.CountryId = c.CountryId
INNER JOIN trade_product t
ON t.TradeProductId = g.TradeProductId
INNER JOIN performance_center pc 
ON pc.PerformanceCenterId = t.PerformanceCenterId
LEFT JOIN client_product cp 
ON cp.GmidId = g.GmidId AND cli.ClientId = cp.ClientId
WHERE pc.ValueCenterId <> 10111 AND cp.GmidId IS NULL
;

-- CLIENTS PRODUCTS


INSERT INTO client_product(GmidId,ClientId,TradeProductId,IsForecastable)
SELECT DISTINCT g.GmidId, cli.ClientId,g.TradeProductId,1
FROM gmid g
INNER JOIN country c
ON c.CountryId = g.CountryId
INNER JOIN client cli
ON cli.CountryId = c.CountryId
INNER JOIN trade_product t
ON t.TradeProductId = g.TradeProductId
INNER JOIN performance_center pc 
ON pc.PerformanceCenterId = t.PerformanceCenterId
LEFT JOIN client_product cp 
ON cp.GmidId = g.GmidId AND cli.ClientId = cp.ClientId
WHERE pc.ValueCenterId <> 10111 AND cp.GmidId IS NULL
;


INSERT INTO client_product(ClientId,TradeProductId,IsForecastable)
SELECT DISTINCT cli.ClientId,t.TradeProductId,1
FROM trade_product t
INNER JOIN gmid g 
ON g.TradeProductId = t.TradeProductId
INNER JOIN client cli
ON cli.CountryId = g.CountryId
INNER JOIN performance_center pc 
ON pc.PerformanceCenterId = t.PerformanceCenterId
LEFT JOIN client_product cp 
ON cp.TradeProductId = t.TradeProductId AND cli.ClientId = cp.ClientId
WHERE pc.ValueCenterId = 10111 AND cp.TradeProductId IS NULL
GROUP BY cli.ClientId,t.TradeProductId, g.CountryId;



-- INSERTS NEWS ROWS FROM CLIENT_PRODUCT TO PLAN 

INSERT INTO [plan](ClientProductId,CampaignId)
SELECT cp.ClientProductId,@ActualCampaignId 
FROM
	( SELECT p.ClientProductId,p.CampaignId 
	  FROM [plan] p
	  WHERE p.CampaignId =@ActualCampaignId
    ) planing

RIGHT JOIN client_product cp 
ON cp.ClientProductId = planing.ClientProductId
WHERE planing.ClientProductId IS NULL 


-- INSERTS NEWS ROWS FROM CLIENT_PRODUCT TO FORECAST

INSERT INTO [forecast](ClientProductId,CampaignId)
SELECT cp.ClientProductId,@ActualCampaignId 
FROM
	( SELECT f.ClientProductId,f.CampaignId 
	  FROM [forecast] f
	  WHERE f.CampaignId = @ActualCampaignId
    ) fore

RIGHT JOIN client_product cp 
ON cp.ClientProductId = fore.ClientProductId
WHERE fore.ClientProductId IS NULL 



 END;
	DELETE FROM TEMP_PRODUCT;	
	DROP TABLE  #ERRORS

GO

EXEC SP_ImportProducts;


