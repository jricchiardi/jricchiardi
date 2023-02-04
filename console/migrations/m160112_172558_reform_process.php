<?php

use yii\db\Migration;

class m160112_172558_reform_process extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER PROCEDURE [dbo].[SP_ImportProducts]
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
UPDATE value_center SET Description = temp.F4 
					
FROM value_center vc
INNER JOIN TEMP_PRODUCT temp
ON vc.ValueCenterId = temp.ValueCenter


-- INSERT NEWS VALUES CENTERS 
INSERT value_center(ValueCenterId,Description)
SELECT DISTINCT temp.ValueCenter ,temp.F4 
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
INSERT trade_product(TradeProductId,Description,PerformanceCenterId,Price,Profit,IsForecastable,SendMail)
SELECT DISTINCT temp.[Trade Product] , 
				temp.F9 ,
				temp.[Performance Center],
				Precio = (SELECT MIN(tem.Precio) FROM  TEMP_PRODUCT tem WHERE tem.[Trade Product] = temp.[Trade Product]),
				Margen = (SELECT MIN(tem.Margen) FROM  TEMP_PRODUCT tem WHERE tem.[Trade Product] = temp.[Trade Product]),
				IsForecastable = CASE WHEN temp.F4 = 'SEEDS' THEN 1 ELSE 0 END,
				1
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


DECLARE @FutureCampaignId INT
SET @FutureCampaignId = (SELECT TOP 1 CampaignId FROM campaign WHERE IsFuture = 1)

-- INSERTS NEWS ROWS FROM CLIENT_PRODUCT TO PLAN 
IF (@FutureCampaignId IS NOT NULL)  BEGIN 
INSERT INTO [plan](ClientProductId,CampaignId)
SELECT cp.ClientProductId,@FutureCampaignId 
FROM
	( SELECT p.ClientProductId,p.CampaignId 
	  FROM [plan] p
	  WHERE p.CampaignId =@FutureCampaignId
    ) planing

RIGHT JOIN client_product cp 
ON cp.ClientProductId = planing.ClientProductId
WHERE planing.ClientProductId IS NULL 

END

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
	DROP TABLE  #ERRORS");
        
        
$this->execute("
ALTER PROCEDURE [dbo].[SP_ImportCustomer]
AS
  SET NOCOUNT ON;


/********************************************	VALIDATIONS ************************************************************/

	CREATE TABLE #ERRORS
	(
		CLIENT INT,
		DESCRIPTION VARCHAR(150),
		CAUSE VARCHAR(50)
	)
	
	-- VALIDATE DUPLICATE CLIENT

	INSERT INTO #ERRORS(CLIENT,DESCRIPTION,CAUSE)
	SELECT [Liable Customer],F3,'CLIENTE DUPLICADO'
	FROM TEMP_CUSTOMER
	GROUP BY [Liable Customer],F3
	HAVING COUNT(*) > 1;

	IF (SELECT COUNT(1) FROM #ERRORS)> 0 BEGIN 
		SELECT * FROM #ERRORS;
	END	 
	ELSE BEGIN

	SELECT * FROM #ERRORS;


/******************************************** COUNTRY ***************************************************/

-- UPDATE COUNTRY
UPDATE country SET	Description = temp.Country 					
FROM country c
INNER JOIN TEMP_CUSTOMER temp
ON c.Description = temp.Country


-- INSERT COUNTRY
INSERT country(Description)
SELECT DISTINCT temp.Country
FROM TEMP_CUSTOMER temp
LEFT JOIN country c
ON c.Description = temp.Country
WHERE c.CountryId IS NULL

/******************************************	CLIENT_TYPE	******************************************************/

-- UPDATE CLIENTS TYPES EXISTING
 UPDATE client_type SET Description = temp.Clasificacion
 FROM client_type ct 
 INNER JOIN TEMP_CUSTOMER temp 
 ON ct.Description = temp.Clasificacion



-- INSERTS NEWS CLIENTS TYPE
INSERT INTO client_type (Description)
SELECT DISTINCT tc.Clasificacion
FROM TEMP_CUSTOMER tc 
LEFT JOIN client_type ct 
ON ct.Description = tc.Clasificacion
WHERE ct.ClientTypeId IS NULL


/******************************************		USERS		*******************************************************/

-- INSERT RSMs 
INSERT INTO [user](DowUserId,Fullname,Email,Username,PasswordHash)
SELECT DISTINCT temp.RSM,
	   temp.F12,
	   temp.[Mail RSM],	   
	   CONCAT(replace(temp.[Mail RSM],'@dow.com',''),'rsm'),
	   '1c63129ae9db9c60c3e8aa94d3e00495'
FROM TEMP_CUSTOMER temp
LEFT JOIN [user] u 
ON u.DowUserId = temp.RSM
WHERE u.UserId IS NULL
 

-- INSERT DSMs 
INSERT INTO [user](DowUserId,ParentId,Fullname,Email,Username,PasswordHash)
SELECT DISTINCT 
	   temp.DSM,
	   rsm.UserId,
	   temp.F9,
	   temp.[Mail DSM],	   
	   CONCAT(replace(temp.[Mail DSM],'@dow.com',''),'dsm') AS Username,
	   '1c63129ae9db9c60c3e8aa94d3e00495' AS PasswordHash
FROM TEMP_CUSTOMER temp
LEFT JOIN 
( SELECT u.UserId,u.DowUserId
  FROM [user] u 
  WHERE CHARINDEX('dsm',u.Username) > 0 
) u
ON u.DowUserId = temp.DSM
INNER JOIN [user] rsm
ON rsm.DowUserId = temp.RSM
WHERE u.UserId IS NULL  AND CHARINDEX('rsm',rsm.Username) > 0

 
-- INSERT Sellers 
INSERT INTO [user](DowUserId,ParentId,Fullname,Email,Username,PasswordHash)
SELECT DISTINCT 
	   temp.[Field Seller],
	   dsm.UserId,
	   temp.F6,
	   temp.[Mail vendedor],	   
	   replace(temp.[Mail vendedor],'@dow.com','') AS Username,
	   '1c63129ae9db9c60c3e8aa94d3e00495' AS PasswordHash
FROM TEMP_CUSTOMER temp
LEFT JOIN 
( SELECT u.UserId,u.DowUserId
  FROM [user] u 
  WHERE NOT( CHARINDEX('dsm',u.Username) > 0 OR CHARINDEX('rsm',u.Username) > 0)
) u
ON u.DowUserId = temp.[Field Seller]
INNER JOIN [user] dsm
ON dsm.DowUserId = temp.DSM
WHERE u.UserId IS NULL AND CHARINDEX('dsm',dsm.Username) > 0
ORDER BY UserId





-- INSERT ROLES
DELETE FROM auth_assignment WHERE item_name IN ('DSM','RSM','SELLER');


INSERT INTO auth_assignment(user_id,item_name)
SELECT u.UserId, CASE WHEN u.Username like '%rsm%'  THEN 'RSM'
					  WHEN u.Username like '%dsm%'  THEN 'DSM'						  
			     ELSE 
					'SELLER'
			   END AS item_name
FROM [user] u
LEFT JOIN auth_assignment asg
ON asg.user_id = u.UserId
WHERE asg.user_id IS NULL



-- INSERT CLIENTS OTHERS


INSERT INTO client(ClientId,Description,IsGroup,CountryId,IsActive)
SELECT -u.UserId ,'OTROS',1,(SELECT TOP 1 CountryId 
							 FROM client cli
							 INNER JOIN client_seller cs 
							 ON cs.ClientId = cli.ClientId 
							 INNER JOIN [user] s 
							 ON s.UserId = cs.SellerId
							 WHERE u.UserId = s.UserId AND cli.CountryId IS NOT NULL
							 ) AS CountryId
							 ,1
FROM [user] u 
INNER JOIN auth_assignment asg
ON u.UserId = asg.user_id
WHERE asg.item_name = 'SELLER' AND NOT EXISTS(SELECT * FROM client ex WHERE ex.ClientId = -u.UserId )

-- UPDATE RSMs
UPDATE [user] SET Fullname = temp.F12, 
				  Email =  temp.[Mail RSM] , 
				  Username = CONCAT(replace(temp.[Mail RSM],'@dow.com',''),'rsm')
FROM [user] u 
INNER JOIN TEMP_CUSTOMER temp
ON u.DowUserId = temp.RSM
INNER JOIN auth_assignment asg
ON asg.user_id = u.UserId
WHERE asg.item_name = 'RSM'


-- UPDATE DSMs
UPDATE [user] SET Fullname = temp.F9, 
				  Email =  temp.[Mail DSM] , 
				  Username = CONCAT(replace(temp.[Mail DSM],'@dow.com',''),'dsm')
FROM [user] u 
INNER JOIN TEMP_CUSTOMER temp
ON u.DowUserId = temp.DSM
INNER JOIN auth_assignment asg
ON asg.user_id = u.UserId
WHERE asg.item_name = 'DSM'


-- UPDATE SELLERs
UPDATE [user] SET Fullname = temp.F6, 
				  Email = temp.[Mail vendedor] , 
				  Username = replace(temp.[Mail vendedor],'@dow.com','')
FROM [user] u 
INNER JOIN TEMP_CUSTOMER temp
ON u.DowUserId = temp.[Field Seller]
INNER JOIN auth_assignment asg
ON asg.user_id = u.UserId
WHERE asg.item_name = 'SELLER'


/******************************************		CLIENTS		*******************************************************/


-- INSERT NEWS CLIENTS

INSERT INTO client(ClientId, ClientTypeId, IsGroup, CountryId, Description , IsActive)
SELECT temp.[Liable Customer],ct.ClientTypeId, 0 ,cou.CountryId,temp.F3,1
FROM TEMP_CUSTOMER temp
LEFT JOIN client_type ct 
ON ct.Description = temp.Clasificacion
LEFT JOIN country cou 
ON cou.Description = temp.Country
LEFT JOIN client c
ON c.ClientId = temp.[Liable Customer]
WHERE c.ClientId IS NULL



--INSERT INTO client_seller
DELETE FROM client_seller;


-- INSERT RELATIONS CLIENT SELLER
INSERT INTO client_seller(ClientId,SellerId)
SELECT temp.[Liable Customer],
 	   s.UserId
FROM  TEMP_CUSTOMER temp
INNER JOIN [client] c
ON c.ClientId = temp.[Liable Customer]
INNER JOIN [user] s
ON s.DowUserId = temp.[Field Seller]
INNER JOIN auth_assignment asg
ON asg.user_id = s.UserId
WHERE asg.item_name = 'SELLER'

-- INSERT OTHERS in client_seller

INSERT INTO client_seller(ClientId,SellerId)
SELECT -u.UserId,u.UserId 
FROM [user] u 
INNER JOIN auth_assignment asg
ON u.UserId = asg.user_id
WHERE asg.item_name = 'SELLER'

-- UPDATE CLIENTS
UPDATE client SET Description = temp.F3, 
				  ClientTypeId = ct.ClientTypeId,
				  CountryId = cou.CountryId ,
				  IsGroup = 0, 
				  GroupId = CASE WHEN ct.Description = 'OTROS' THEN -cs.SellerId  ELSE NULL END
FROM client c 
INNER JOIN TEMP_CUSTOMER temp 
ON c.ClientId = temp.[Liable Customer]
INNER JOIN client_type ct 
ON ct.Description = temp.Clasificacion
INNER JOIN country cou 
ON cou.Description = temp.Country
INNER JOIN client_seller cs 
ON cs.ClientId = c.ClientId

-- UPDATE COUNTRIES CLIENT OTHERS IS VERY DIFfICULT
UPDATE client  SET CountryId = 
					 ( SELECT TOP 1 c.CountryId
					  FROM client c 
					  INNER JOIN client_seller csi 
					  ON c.ClientId = csi.ClientId 
					  WHERE csi.SellerId = cs.SellerId AND csi.ClientId >0					  
					  ) 
FROM client cli 
INNER JOIN client_seller cs 
ON cli.ClientId = cs.ClientId
WHERE cli.ClientId < 0


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



DECLARE @FutureCampaignId INT
SET @FutureCampaignId = (SELECT TOP 1 CampaignId FROM campaign WHERE IsFuture = 1)

DECLARE @ActualCampaignId INT
SET @ActualCampaignId = (SELECT TOP 1 CampaignId FROM campaign WHERE IsActual = 1)

-- INSERTS NEWS ROWS FROM CLIENT_PRODUCT TO PLAN 
IF (@FutureCampaignId IS NOT NULL)  BEGIN 
INSERT INTO [plan](ClientProductId,CampaignId)
SELECT cp.ClientProductId,@FutureCampaignId 
FROM
	( SELECT p.ClientProductId,p.CampaignId 
	  FROM [plan] p
	  WHERE p.CampaignId =@FutureCampaignId
    ) planing

RIGHT JOIN client_product cp 
ON cp.ClientProductId = planing.ClientProductId
WHERE planing.ClientProductId IS NULL 

END

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
    DELETE FROM TEMP_CUSTOMER;");        
    }

    public function safeDown()
    {
        echo "m160112_172558_reform_process cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
