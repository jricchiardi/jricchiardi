<?php

use yii\db\Migration;

class m151021_110629_sprint8 extends Migration
{
    public function up()
    {
        
        $this->execute("CREATE TABLE [dbo].[snapshot_forecast](
	[ClientProductId] [int] NOT NULL,
	[CampaignId] [int] NOT NULL,
	[January] [int] NULL,
	[February] [int] NULL,
	[March] [int] NULL,
	[April] [int] NULL,
	[May] [int] NULL,
	[June] [int] NULL,
	[July] [int] NULL,
	[August] [int] NULL,
	[September] [int] NULL,
	[October] [int] NULL,
	[November] [int] NULL,
	[December] [int] NULL,
	[Total] [int] NULL,
 CONSTRAINT [PK_snapshot_forecast_1] PRIMARY KEY CLUSTERED 
(
	[ClientProductId] ASC,
	[CampaignId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]



ALTER TABLE [dbo].[snapshot_forecast]  WITH NOCHECK ADD  CONSTRAINT [FK_snapshot_forecast_campaign] FOREIGN KEY([CampaignId])
REFERENCES [dbo].[campaign] ([CampaignId])


ALTER TABLE [dbo].[snapshot_forecast] NOCHECK CONSTRAINT [FK_snapshot_forecast_campaign]


ALTER TABLE [dbo].[snapshot_forecast]  WITH NOCHECK ADD  CONSTRAINT [FK_snapshot_forecast_client_product] FOREIGN KEY([ClientProductId])
REFERENCES [dbo].[client_product] ([ClientProductId])


ALTER TABLE [dbo].[snapshot_forecast] NOCHECK CONSTRAINT [FK_snapshot_forecast_client_product]
");
        
        $this->execute("CREATE PROCEDURE [dbo].[CreateSnapshotForecast]
AS
  BEGIN TRANSACTION 

   -- VARS STATICS
	DECLARE @CampaignId INT = (SELECT CampaignId FROM campaign WHERE IsActual = 1);	
	DECLARE @ActualMonth INT = ( SELECT month(getdate()))
	
	-- INSERT NEWS PRODUCTS FROM CLIENTS WITH SALES AND FORECAST >0
	INSERT INTO snapshot_forecast(ClientProductId,CampaignId)
	SELECT f.ClientProductId , @CampaignId		   
	FROM SaleWithForecast f
	LEFT JOIN snapshot_forecast sf
	ON f.ClientProductId = sf.ClientProductId
	WHERE f.Total >0 AND f.CampaignId = @CampaignId AND sf.ClientProductId IS NULL

	-- UPDATE ACTUAL MONTH TOTALS FROM FORECAST
	UPDATE snapshot_forecast SET [January] = CASE WHEN @ActualMonth = 1 THEN f.Total END
								,[February] = CASE WHEN @ActualMonth = 2 THEN f.Total END
								,[March] = CASE WHEN @ActualMonth = 3 THEN f.Total END
								,[April] = CASE WHEN @ActualMonth = 4 THEN f.Total END
								,[May] = CASE WHEN @ActualMonth = 5 THEN f.Total END
								,[June] = CASE WHEN @ActualMonth = 6 THEN f.Total END
								,[July] = CASE WHEN @ActualMonth = 7 THEN f.Total END
								,[August] = CASE WHEN @ActualMonth = 8 THEN f.Total END
								,[September] = CASE WHEN @ActualMonth = 9 THEN f.Total END
								,[October] = CASE WHEN @ActualMonth = 10 THEN f.Total END
								,[November] = CASE WHEN @ActualMonth = 11 THEN f.Total END
								,[December] = CASE WHEN @ActualMonth = 12 THEN f.Total END
   FROM snapshot_forecast sf
   INNER JOIN SaleWithForecast f 
   ON sf.ClientProductId = f.ClientProductId
   WHERE sf.CampaignId = @CampaignId AND f.Total >0
  
  COMMIT TRANSACTION");
        
  $this->execute("CREATE VIEW [dbo].[ExportComparative]
AS
  SELECT 
		 cou.Description AS Country ,
		 dsm.DowUserId AS DSM,
		 dsm.Fullname AS NameDSM,
		 seller.DowUserId AS Seller,
		 seller.Fullname NameSeller,
		 cli.ClientId,
		 cli.Description AS Client,
		 t.TradeProductId,
		 t.Description AS TradeProduct,
		 pc.PerformanceCenterId,
		 pc.Description AS PerformanceCenter,
		 g.GmidId,
		 g.Description AS Gmid, 	
		 sf.*
  FROM snapshot_forecast sf
  INNER JOIN client_product cp
  ON cp.ClientProductId = sf.ClientProductId 
  INNER JOIN trade_product t
  ON t.TradeProductId = cp.TradeProductId   
  LEFT JOIN gmid g 
  ON g.TradeProductId = t.TradeProductId AND g.GmidId = cp.GmidId
  INNER JOIN performance_center pc 
  ON pc.PerformanceCenterId = t.PerformanceCenterId
  INNER JOIN value_center vc 
  ON vc.ValueCenterId = pc.ValueCenterId
  INNER JOIN client cli 
  ON cli.ClientId = cp.ClientId
  INNER JOIN country cou
  ON cou.CountryId = cli.CountryId
  INNER JOIN client_seller cs
  ON cs.ClientId = cli.ClientId
  INNER JOIN [user] seller 
  ON seller.UserId = cs.SellerId
  INNER JOIN [user] dsm 
  ON dsm.UserId = seller.ParentId") ;
  
  
  
  $this->execute("
        DROP TABLE TEMP_SALE;
       
       CREATE TABLE [dbo].[TEMP_SALE](
	[Country] [varchar](60) NULL,
	[Liable Customer] [varchar](200) NULL,
	[F3] [varchar](50) NULL,
	[GMID] [varchar](100) NULL,
	[F5] [varchar](200) NULL,
	[Field Seller] [varchar](50) NULL,
	[F7] [varchar](100) NULL,
	[Calendar year] [int] NULL,
	[Calendar month] [int] NULL,
	[Actual] [decimal](10, 2) NULL,
	[Total] [decimal](10, 2) NULL,
	[Actual2] [decimal](10, 2) NULL
        )");
  
  
  $this->execute("ALTER PROCEDURE [dbo].[SP_ImportSales]
AS
	 SET NOCOUNT ON;
	
	-- VALIDATIONS SALES	
	CREATE TABLE #ERRORS
	(
		GMID VARCHAR(20),
		DESCRIPTION VARCHAR(150),
		[MONTH] INT ,
		CLIENT INT ,
		CAUSE VARCHAR(50)
	)

	INSERT #ERRORS(GMID,DESCRIPTION,[MONTH],CLIENT,CAUSE)
	SELECT  GMID,
			F5, 
			[Calendar month], 
			[Liable Customer],
			'HAY VENTA DUPLICADA'
	FROM TEMP_SALE
	GROUP BY [Liable Customer],F5,GMID,[Calendar year],[Calendar month]
	HAVING COUNT(*) > 1;


	IF (SELECT COUNT(1) FROM #ERRORS)>0 BEGIN 
		SELECT * FROM #ERRORS;
	END	 
	ELSE BEGIN
		SELECT * FROM #ERRORS;
	SELECT c.CampaignId,c.Name
	INTO #campaigns
	FROM TEMP_SALE ts
	INNER JOIN campaign c
	ON CAST(c.Name AS INT) = ts.[Calendar year]
	GROUP BY c.CampaignId,c.Name;



	DELETE FROM sale WHERE CampaignId IN (SELECT CampaignId FROM #campaigns);
		
	
	INSERT INTO sale(ClientId,GmidId,Month,Amount,Total,CampaignId)
	SELECT [Liable Customer],GMID,[Calendar month],Actual,Total,c.CampaignId
	FROM TEMP_SALE ts
	INNER JOIN campaign c
	ON CAST(c.Name AS INT) = ts.[Calendar year]
	
	END;
	
	DELETE FROM TEMP_SALE;");
  
  $this->execute("CREATE PROCEDURE [dbo].[SP_ImportCyO]

AS
	 SET NOCOUNT ON;
	
	-- VALIDATIONS CyO	
	CREATE TABLE #ERRORS
	(
		GMID VARCHAR(20) NULL,
		CLIENT INT NULL ,
		CAUSE VARCHAR(50) 
	)

	INSERT #ERRORS(GMID,CAUSE)
	SELECT DISTINCT cyo.GmidId,
			'EL GMID NO EXISTE EN NUESTROS REGISTROS'
	FROM TEMP_CYO cyo
	LEFT JOIN gmid g
	ON CAST(g.GmidId AS INT) = CAST(cyo.GmidId AS INT)
	WHERE g.GmidId IS NULL;
	

	INSERT #ERRORS(CLIENT,CAUSE)
	SELECT DISTINCT cyo.ClientId,
			'EL CLIENTE NO EXISTE EN NUESTROS REGISTROS'
	FROM TEMP_CYO cyo
	LEFT JOIN client c
	ON c.ClientId = cyo.ClientId
	WHERE c.ClientId IS NULL;

	IF (SELECT COUNT(1) FROM #ERRORS)>0 BEGIN 
		SELECT * FROM #ERRORS;
	END	 
	ELSE BEGIN
	
	SELECT * FROM #ERRORS;	
	DECLARE @ActualCampaignId INT
	SET @ActualCampaignId = (SELECT TOP 1 CampaignId FROM campaign WHERE IsActual = 1)
		
	DELETE FROM cyo WHERE CampaignId = @ActualCampaignId;
	
	INSERT INTO cyo(ClientId,GmidId,CampaignId,InventoryBalance)
	SELECT ClientId,GmidId,CampaignId,InventoryBalance
	FROM TEMP_CYO

	
	END;
	
	DELETE FROM TEMP_CYO;");
  
  $this->execute("ALTER VIEW [dbo].[ExportConsolid]
AS
SELECT fs.CampaignId,
	   coun.Description AS Pais,
	   rsm.UserId AS 'RSMId',
	   rsm.DowUserId AS 'RSM',
	   rsm.Fullname AS 'Nombre RSM',
	   dsm.UserId AS 'DSMId',
	   dsm.DowUserId AS 'DSM',
	   dsm.Fullname AS 'Nombre DSM',
	   seller.UserId AS 'SellerId',
	   seller.DowUserId AS Vendedor,
	   seller.Fullname AS 'Nombre Vendedor',
	   fs.ClientId AS 'Cliente',
	   fs.Client AS 'Nombre Cliente',
	   ct.Description AS 'Clasificacion',
	   fs.ValueCenter AS 'Value Center',
	   fs.TradeProductId AS 'Trade Product',
	   fs.TradeProduct as 'Nombre Trade Product',
	   fs.PerformanceCenterId AS 'Performance',
	   fs.PerformanceCenter AS 'Nombre Performance',
	   fs.GmidId AS 'GMID',
	   fs.GmidDescription AS 'Nombre GMID',
	   'MES' = NULL,
	   'Q' = NULL,
	   fs.ForecastPrice AS 'Precio',
       T.Volume AS 'Volumen',	  
  	   (T.Volume * fs.ForecastPrice ) AS 'USD'
	   
FROM 
(
  SELECT ClientProductId,		
 	 	 Volume
  FROM SaleWithForecast  
  UNPIVOT
  (    
    Volume FOR [Month] IN (January,February,March,April,May,June,July,August,September,October,November,December)
  ) AS p
) AS T 
INNER JOIN SaleWithForecast fs 
ON fs.ClientProductId = T.ClientProductId
INNER JOIN client c 
ON c.ClientId = fs.ClientId
INNER JOIN country coun
ON coun.CountryId = c.CountryId
INNER JOIN client_seller cs 
ON cs.ClientId = c.ClientId
INNER JOIN [user] seller
ON seller.UserId = cs.SellerId
INNER JOIN [user] dsm 
ON dsm.UserId = seller.ParentId
INNER JOIN [user] rsm 
ON rsm.UserId = dsm.ParentId
LEFT JOIN [client_type] ct 
ON ct.ClientTypeId = c.ClientTypeId");
  
  
    }

    public function down()
    {
        echo "m151021_110629_sprint8 cannot be reverted.\n";

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
