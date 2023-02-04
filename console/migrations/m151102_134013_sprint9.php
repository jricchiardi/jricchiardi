<?php

use yii\db\Migration;

class m151102_134013_sprint9 extends Migration
{
    public function up()
    {
        
        /* 1 PASO CAMBIAR A MANOPLA LAS PK A INT DE LA TABLA GMID Y TRADE_PRODUCT*/
        
        
        
        /*2  TIRAR MIGRATIONS*/
        $this->execute("SET IDENTITY_INSERT [dbo].[type_import] ON ;
                        INSERT INTO type_import(TypeImportId,Name) 
                        VALUES (7,'PLAN')
                        SET IDENTITY_INSERT [dbo].[type_import] OFF;");
        
        
        $this->execute("ALTER VIEW [dbo].[SaleWithPlan]
AS

SELECT  result.*, 
		'Q1' = (result.January + result.February + result.March),
		'Q2' = (result.April + result.May + result.June),
		'Q3' = (result.July + result.August + result.September),
		'Q4' = (result.October + result.November + result.December),
		'Total' = result.January + result.February + result.March + result.April + result.May + result.June +result.July + result.August + result.September + result.October + result.November + result.December
FROM 
(
 SELECT p.ClientProductId,		   
	   p.CampaignId,	   	
	   cp.ClientId,
	   cp.IsForecastable,   
	   u.UserId AS SellerId,	   
	   u.Fullname AS SellerName,	   
	   tp.TradeProductId,
	   tp.Description AS TradeProduct,	   
	   tp.Price AS TradeProductPrice,
	   tp.profit AS TradeProductProfit,
	   g.GmidId,
	   g.Description AS GmidDescription,
	   g.Price AS GmidPrice,
	   g.Profit AS GmidProfit,
	   pc.PerformanceCenterId,
	   pc.Description AS PerformanceCenter,
	   vc.ValueCenterId,
	   vc.Description AS ValueCenter,
	   isnull(p.January,0) AS January, isnull(p.February,0) AS February ,isnull(p.March,0) AS March, 
	   isnull(p.April,0) AS April  ,isnull(p.May,0) AS May ,isnull(p.June,0) AS June ,
	   isnull(p.July,0) AS July ,isnull(p.August,0) AS August ,isnull(p.September,0)  AS September,
	   isnull(p.October,0) AS October,isnull(p.November,0) AS November,isnull(p.December,0) AS December ,
	   p.Q1 AS 'PlanQ1',p.Q2 AS 'PlanQ2',p.Q3 AS 'PlanQ3',p.Q4 AS 'PlanQ4',
	   p.Total AS 'PlanTotal',

	   PlanDescription = CASE  vc.ValueCenterId WHEN 10111 THEN tp.Description
							 		ELSE  g.Description
							  END	,
  	   PlanPrice = CASE  vc.ValueCenterId WHEN 10111 THEN tp.Price
									ELSE  g.Price
							  END	,
		cli.Description AS Client,
		cli.GroupId AS GroupId

FROM dbo.[plan] p
INNER JOIN client_product cp 
ON cp.ClientProductId = p.ClientProductId
INNER JOIN client cli 
ON cp.ClientId = cli.ClientId
INNER JOIN dbo.client_seller cs 
ON cs.ClientId = cp.ClientId
INNER JOIN dbo.[user] u 
ON u.UserId = cs.SellerId
INNER JOIN dbo.trade_product tp
ON tp.TradeProductId = cp.TradeProductId
LEFT JOIN gmid g 
ON g.GmidId = cp.GmidId
INNER JOIN dbo.performance_center pc 
ON pc.PerformanceCenterId = tp.PerformanceCenterId
INNER JOIN value_center vc 
ON vc.ValueCenterId = pc.ValueCenterId
) AS result");
        $this->execute("ALTER VIEW [dbo].[SaleWithForecast]
AS

SELECT  result.*, 
		'Q1' = (result.January + result.February + result.March),
		'Q2' = (result.April + result.May + result.June),
		'Q3' = (result.July + result.August + result.September),
		'Q4' = (result.October + result.November + result.December),
		'Total' = result.January + result.February + result.March + result.April + result.May + result.June +result.July + result.August + result.September + result.October + result.November + result.December
FROM 
(
 SELECT f.ClientProductId,		   
	   f.CampaignId,	   	
	   cp.ClientId,
	   cp.IsForecastable,   
	   u.UserId AS SellerId,	   
	   u.Fullname AS SellerName,	   
	   tp.TradeProductId,
	   tp.Description AS TradeProduct,	   
	   tp.Price AS TradeProductPrice,
	   tp.profit AS TradeProductProfit,
	   g.GmidId,
	   g.Description AS GmidDescription,
	   g.Price AS GmidPrice,
	   g.Profit AS GmidProfit,
	   pc.PerformanceCenterId,
	   pc.Description AS PerformanceCenter,
	   vc.ValueCenterId,
	   vc.Description AS ValueCenter,
	   f.January AS 'ForecastJanuary' , f.February AS 'ForecastFebruary',f.March AS 'ForecastMarch', 
	   f.April AS 'ForecastApril' ,f.May AS 'ForecastMay' ,f.June AS 'ForecastJune',
	   f.July AS 'ForecastJuly',f.August AS 'ForecastAugust',f.September AS 'ForecastSeptember',
	   f.October AS 'ForecastOctober',f.November AS 'ForecastNovember',f.December AS 'ForecastDecember',
	   f.Q1 AS 'ForecastQ1',f.Q2 AS 'ForecastQ2',f.Q3 AS 'ForecastQ3',f.Q4 AS 'ForecastQ4',
	   f.Total AS 'ForecastTotal',
	   sal.January AS 'SaleJanuary',sal.February AS 'SaleFebruary',sal.March AS 'SaleMarch', 
	   sal.April AS 'SaleApril' ,sal.May AS 'SaleMay',sal.June AS 'SaleJune',
	   sal.July AS 'SaleJuly',sal.August AS 'SaleAugust',sal.September AS 'SaleSeptember',
	   sal.October AS 'SaleOctober',sal.November AS 'SaleNovember',sal.December AS 'SaleDecember',
	   sal.CampaignId AS 'CampaignSale',
	   'January' =  CASE WHEN 1 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM') THEN isnull(sal.January,0) ELSE isnull(f.January,0) END,
	   'February' = CASE WHEN 2 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM') THEN isnull(sal.February,0) ELSE isnull(f.February,0) END,
	   'March' =  CASE WHEN 3 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM') THEN isnull(sal.March,0) ELSE isnull(f.March,0) END,
	   'April' =  CASE WHEN 4 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM') THEN isnull(sal.April,0) ELSE isnull(f.April,0) END,
	   'May' =  CASE WHEN 5 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM') THEN isnull(sal.May,0) ELSE isnull(f.May,0) END,
	   'June' = CASE WHEN 6 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM') THEN isnull(sal.June,0) ELSE isnull(f.June,0) END,
	   'July' = CASE WHEN 7 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM') THEN isnull(sal.July,0) ELSE isnull(f.July,0) END,
	   'August' = CASE WHEN 8 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM') THEN isnull(sal.August,0) ELSE isnull(f.August,0) END,
	   'September' =CASE WHEN 9 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM') THEN isnull(sal.September,0) ELSE isnull(f.September,0) END,
	   'October' = CASE WHEN 10 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM') THEN isnull(sal.October,0) ELSE isnull(f.October,0) END,
	   'November' = CASE WHEN 11 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM') THEN isnull(sal.November,0) ELSE isnull(f.November,0) END,
	   'December' = CASE WHEN 12 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')THEN isnull(sal.December,0) ELSE isnull(f.December,0) END,
	   ForecastDescription = CASE  vc.ValueCenterId WHEN 10111 THEN tp.Description
							 		ELSE  g.Description
							  END	,
  	   ForecastPrice = CASE  vc.ValueCenterId WHEN 10111 THEN tp.Price
									ELSE  g.Price
							  END	,
		cli.Description AS Client,
		cli.GroupId AS GroupId		

FROM dbo.forecast f
INNER JOIN client_product cp 
ON cp.ClientProductId = f.ClientProductId
INNER JOIN client cli ON cp.ClientId = cli.ClientId
LEFT JOIN dbo.SaleFormat sal
ON f.ClientProductId = sal.ClientProductId
INNER JOIN dbo.client_seller cs 
ON cs.ClientId = cp.ClientId
INNER JOIN dbo.[user] u 
ON u.UserId = cs.SellerId
INNER JOIN dbo.trade_product tp
ON tp.TradeProductId = cp.TradeProductId
LEFT JOIN gmid g 
ON g.GmidId = cp.GmidId
INNER JOIN dbo.performance_center pc 
ON pc.PerformanceCenterId = tp.PerformanceCenterId
INNER JOIN value_center vc 
ON vc.ValueCenterId = pc.ValueCenterId
) AS result");
        
        
        $this->execute("ALTER PROCEDURE [dbo].[SP_ImportCyO]

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
	ON g.GmidId  = cyo.GmidId
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
        
        
    $this->execute("
    DROP TABLE [dbo].[TEMP_SALE] ;
    
    CREATE TABLE [dbo].[TEMP_SALE](
	[Country] [varchar](60) NULL,
	[Liable Customer] [varchar](200) NULL,
	[F3] [varchar](50) NULL,
	[GMID] [int] NULL,
	[F5] [varchar](200) NULL,
	[Field Seller] [varchar](50) NULL,
	[F7] [varchar](100) NULL,
	[Calendar year] [int] NULL,
	[Calendar month] [int] NULL,
	[Actual] [decimal](10, 2) NULL,
	[Total] [decimal](10, 2) NULL,
	[Actual2] [decimal](10, 2) NULL
        )");
    
    
    $this->execute("
    DROP TABLE [dbo].[TEMP_CYO] ;
    
    CREATE TABLE [dbo].[TEMP_CYO](
	[ClientId] [int] NULL,
	[GmidId] [int] NULL,
	[CampaignId] [int] NULL,
	[InventoryBalance] [decimal](10, 2) NULL
    )");
    
    $this->execute("
DROP TABLE [dbo].[TEMP_PRODUCT] ;

CREATE TABLE [dbo].[TEMP_PRODUCT](
	[Country] [varchar](10) NULL,
	[F2] [varchar](50) NULL,
	[ValueCenter] [varchar](100) NULL,
	[F4] [varchar](50) NULL,
	[Performance Center] [varchar](50) NULL,
	[F7] [varchar](100) NULL,
	[Trade Product] [int] NULL,
	[F9] [varchar](150) NULL,
	[GMID] [int] NULL,
	[F11] [varchar](200) NULL,
	[Precio] [varchar](50) NULL,
	[Margen] [varchar](50) NULL
        )");
   
    }

    public function down()
    {
        echo "m151102_134013_sprint9 cannot be reverted.\n";

        return false;
    }


}
