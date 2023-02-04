<?php

use yii\db\Migration;

class m151111_175413_fix3_sprint9 extends Migration {

    public function up() {

  $this->execute("
       DROP TABLE [dbo].[TEMP_PRODUCT];           
       CREATE TABLE [dbo].[TEMP_PRODUCT](
	[Country] [varchar](10) NULL,
	[F2] [varchar](50) NULL,
	[ValueCenter] [varchar](100) NULL,
	[F4] [varchar](50) NULL,
	[Performance Center] [varchar](50) NULL,
	[F7] [varchar](100) NULL,
	[Trade Product] [varchar](50) NULL,
	[F9] [varchar](150) NULL,
	[GMID] [int] NULL,
	[F11] [varchar](200) NULL,
	[Precio] [varchar](50) NULL,
	[Margen] [varchar](50) NULL
       )");
 
  $this->execute("
           DROP TABLE [dbo].[TEMP_PLAN];
           
CREATE TABLE [dbo].[TEMP_PLAN](
	[ClientProductId] [int] NULL,
	[Country] [varchar](50) NULL,
	[DSM] [varchar](150) NULL,
	[DSMName] [varchar](150) NULL,
	[SellerId] [varchar](50) NULL,
	[SellerName] [varchar](150) NULL,
	[ClientId] [varchar](100) NULL,
	[NameClient] [varchar](150) NULL,
	[ClientType] [varchar](150) NULL,
	[ValueCenter] [varchar](150) NULL,
	[PerformanceCenter] [varchar](150) NULL,
	[Description] [varchar](150) NULL,
	[January] [int] NULL,
	[February] [int] NULL,
	[March] [int] NULL,
	[Q1] [int] NULL,
	[April] [int] NULL,
	[May] [int] NULL,
	[June] [int] NULL,
	[Q2] [int] NULL,
	[July] [int] NULL,
	[August] [int] NULL,
	[September] [int] NULL,
	[Q3] [int] NULL,
	[October] [int] NULL,
	[November] [int] NULL,
	[December] [int] NULL,
	[Q4] [int] NULL,
	[Total] [int] NULL
)");

          $this->execute("  INSERT INTO type_audit(Name,PublicName) VALUES ('Importación Plan Offline','TYPE_IMPORT_PLAN_OFFLINE');
  INSERT INTO type_audit(Name,PublicName) VALUES ('Importación Forecast Offline','TYPE_IMPORT_FORECAST_OFFLINE');
  INSERT INTO type_audit(Name,PublicName) VALUES ('Exportación Plan Offline','TYPE_EXPORT_PLAN_OFFLINE');
  INSERT INTO type_audit(Name,PublicName) VALUES ('Exportación Forecast Offline','TYPE_EXPORT_FORECAST_OFFLINE');");
  
  
          // VISTAS
  
          

  
 $this->execute("-- VIEW INVERSE TABLE SALE
ALTER VIEW [dbo].[InverseSale] 
AS
 select sails.ClientId,
	   sails.GmidId,
	   sails.CampaignId,	   
	   SUM(January) AS January,
	   SUM(JanuaryUSD) AS JanuaryUSD,
	   SUM(February) AS February,
	   SUM(FebruaryUSD) AS FebruaryUSD,
	   SUM(March) AS March,
	   SUM(MarchUSD) AS MarchUSD,
	   SUM(April) AS April,
	   SUM(AprilUSD) AS AprilUSD,
	   SUM(May) AS May,
	   SUM(MayUSD) AS MayUSD,
	   SUM(June) AS June,
	   SUM(JuneUSD) AS JuneUSD,
	   SUM(July) AS July,
	   SUM(JulyUSD) AS JulyUSD,
	   SUM(August) AS August,
	   SUM(AugustUSD) AS AugustUSD,
	   SUM(September) AS September,
	   SUM(SeptemberUSD) AS SeptemberUSD,
	   SUM(October) AS October,
	   SUM(OctoberUSD) AS OctoberUSD,
	   SUM(November) AS November,
	   SUM(NovemberUSD) AS NovemberUSD,
	   SUM(December) AS December,
	   SUM(DecemberUSD) AS DecemberUSD
from
( 
  
SELECT ClientId,GmidId,CampaignId ,
									 [1] AS 'January',
									 [2] AS 'February',
									 [3] AS 'March',								     
									 [4] AS 'April',
									 [5] AS 'May',
									 [6] AS 'June',									 
									 [7] AS 'July',
									 [8] AS 'August',
									 [9] AS 'September',									 
									 [10] AS 'October',
									 [11] AS 'November',
									 [12] AS 'December'	,
									 0 AS 'JanuaryUSD',
									 0 AS 'FebruaryUSD',
									 0 AS 'MarchUSD',								     
									 0 AS 'AprilUSD',
									 0 AS 'MayUSD',
									 0 AS 'JuneUSD',									 
									 0 AS 'JulyUSD',
									 0 AS 'AugustUSD',
									 0 AS 'SeptemberUSD',									 
									 0 AS 'OctoberUSD',
									 0 AS 'NovemberUSD',
									 0 AS 'DecemberUSD'	
															 
 FROM (
 SELECT ClientId,GmidId,CampaignId,[Month],Amount
 FROM dbo.sale) sal
 PIVOT (SUM(Amount) FOR [Month] IN (\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\",\"9\",\"10\",\"11\",\"12\")) AS pvt

 UNION ALL

 SELECT ClientId,GmidId,CampaignId ,
									 0 AS 'January',
									 0 AS 'February',
									 0 AS 'March',								     
									 0 AS 'April',
									 0 AS 'May',
									 0 AS 'June',									 
									 0 AS 'July',
									 0 AS 'August',
									 0 AS 'September',									 
									 0 AS 'October',
									 0 AS 'November',
									 0 AS 'December'	,
									 [1] AS 'JanuaryUSD',
									 [2] AS 'FebruaryUSD',
									 [3] AS 'MarchUSD',								     
									 [4] AS 'AprilUSD',
									 [5] AS 'MayUSD',
									 [6] AS 'JuneUSD',									 
									 [7] AS 'JulyUSD',
									 [8] AS 'AugustUSD',
									 [9] AS 'SeptemberUSD',									 
									 [10] AS 'OctoberUSD',
									 [11] AS 'NovemberUSD',
									 [12] AS 'DecemberUSD'	
														 
 FROM (
 SELECT ClientId,GmidId,CampaignId,[Month],Total
 FROM dbo.sale) sal2
 PIVOT (SUM(Total) FOR [Month] IN (\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\",\"9\",\"10\",\"11\",\"12\")) AS pvt2
 ) sails

 group by ClientId,GmidId,CampaignId
");
           
          
    
          

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
	   u.DowUserId AS SellerDowId,	   
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
		cli.GroupId AS GroupId,
		coun.Description AS Country,
		dsm.DowUserId AS DsmId,
		dsm.Fullname AS DSM,
		isnull(tc.Description,'-') AS ClientType


FROM dbo.[plan] p
INNER JOIN client_product cp 
ON cp.ClientProductId = p.ClientProductId
INNER JOIN client cli 
ON cp.ClientId = cli.ClientId
LEFT JOIN client_type tc
ON tc.ClientTypeId = cli.ClientTypeId
LEFT JOIN country coun 
ON coun.CountryId = cli.CountryId
INNER JOIN dbo.client_seller cs 
ON cs.ClientId = cp.ClientId
INNER JOIN dbo.[user] u 
ON u.UserId = cs.SellerId
INNER JOIN dbo.[user] dsm
ON dsm.UserId =  u.ParentId
INNER JOIN dbo.trade_product tp
ON tp.TradeProductId = cp.TradeProductId
LEFT JOIN gmid g 
ON g.GmidId = cp.GmidId
INNER JOIN dbo.performance_center pc 
ON pc.PerformanceCenterId = tp.PerformanceCenterId
INNER JOIN value_center vc 
ON vc.ValueCenterId = pc.ValueCenterId
) AS result");




$this->execute("ALTER VIEW [dbo].[SaleToForecast]
AS
SELECT		 
			  [ClientId]
			  ,[GmidId]
			  ,[CampaignId]
			  ,sum(isnull([January],0)) AS [January]
			  ,sum(isnull([February],0)) AS [February]
			  ,sum(isnull([March],0)) AS [March] 
			  ,sum(isnull([April],0)) AS [April]
			  ,sum(isnull([May],0)) AS  [May]
			  ,sum(isnull([June],0)) AS [June]
			  ,sum(isnull([July],0)) AS [July]
			  ,sum(isnull([August],0)) AS [August]
			  ,sum(isnull([September],0)) AS [September]
			  ,sum(isnull([October],0)) AS [October]
			  ,sum(isnull([November],0)) AS [November]
			  ,sum(isnull([December],0)) AS [December]

			  ,sum(isnull([JanuaryUSD],0)) AS [JanuaryUSD]
			  ,sum(isnull([FebruaryUSD],0)) AS [FebruaryUSD]
			  ,sum(isnull([MarchUSD],0)) AS [MarchUSD] 
			  ,sum(isnull([AprilUSD],0)) AS [AprilUSD]
			  ,sum(isnull([MayUSD],0)) AS  [MayUSD]
			  ,sum(isnull([JuneUSD],0)) AS [JuneUSD]
			  ,sum(isnull([JulyUSD],0)) AS [JulyUSD]
			  ,sum(isnull([AugustUSD],0)) AS [AugustUSD]
			  ,sum(isnull([SeptemberUSD],0)) AS [SeptemberUSD]
			  ,sum(isnull([OctoberUSD],0)) AS [OctoberUSD]
			  ,sum(isnull([NovemberUSD],0)) AS [NovemberUSD]
			  ,sum(isnull([DecemberUSD],0)) AS [DecemberUSD]
FROM (
	  SELECT 
		     c.[ClientId]
			,[GmidId]
			,[CampaignId]
			,isnull([January],0) AS [January]
			,isnull([February],0) AS [February]
			,isnull([March],0) AS [March]
			,isnull([April],0) AS [April]
			,isnull([May],0) AS [May]
			,isnull([June],0) AS [June]
			,isnull([July],0) AS [July]
			,isnull([August],0) AS [August]
			,isnull([September],0) AS [September]
			,isnull([October],0) AS [October]
			,isnull([November],0) AS [November]
			,isnull([December],0) AS [December]
			,isnull([JanuaryUSD],0) AS [JanuaryUSD]
			,isnull([FebruaryUSD],0) AS [FebruaryUSD]
			,isnull([MarchUSD],0) AS [MarchUSD]
			,isnull([AprilUSD],0) AS [AprilUSD]
			,isnull([MayUSD],0) AS [MayUSD]
			,isnull([JuneUSD],0) AS [JuneUSD]
			,isnull([JulyUSD],0) AS [JulyUSD]
			,isnull([AugustUSD],0) AS [AugustUSD]
			,isnull([SeptemberUSD],0) AS [SeptemberUSD]
			,isnull([OctoberUSD],0) AS [OctoberUSD]
			,isnull([NovemberUSD],0) AS [NovemberUSD]
			,isnull([DecemberUSD],0) AS [DecemberUSD]
		FROM  [dbo].[InverseSale] childSale
		INNER JOIN client c
		ON childSale.ClientId = c.ClientId
		WHERE c.GroupId IS NULL
		UNION ALL
		SELECT [GroupId]
			  ,[GmidId]
			  ,[CampaignId]
			  ,sum(isnull([January],0)) AS [January]
			  ,sum(isnull([February],0)) AS [February]
			  ,sum(isnull([March],0)) AS [March] 
			  ,sum(isnull([April],0)) AS [April]
			  ,sum(isnull([May],0)) AS  [May]
			  ,sum(isnull([June],0)) AS [June]
			  ,sum(isnull([July],0)) AS [July]
			  ,sum(isnull([August],0)) AS [August]
			  ,sum(isnull([September],0)) AS [September]
			  ,sum(isnull([October],0)) AS [October]
			  ,sum(isnull([November],0)) AS [November]
			  ,sum(isnull([December],0)) AS [December]

			  ,sum(isnull([JanuaryUSD],0)) AS [JanuaryUSD]
			  ,sum(isnull([FebruaryUSD],0)) AS [FebruaryUSD]
			  ,sum(isnull([MarchUSD],0)) AS [MarchUSD] 
			  ,sum(isnull([AprilUSD],0)) AS [AprilUSD]
			  ,sum(isnull([MayUSD],0)) AS  [MayUSD]
			  ,sum(isnull([JuneUSD],0)) AS [JuneUSD]
			  ,sum(isnull([JulyUSD],0)) AS [JulyUSD]
			  ,sum(isnull([AugustUSD],0)) AS [AugustUSD]
			  ,sum(isnull([SeptemberUSD],0)) AS [SeptemberUSD]
			  ,sum(isnull([OctoberUSD],0)) AS [OctoberUSD]
			  ,sum(isnull([NovemberUSD],0)) AS [NovemberUSD]
			  ,sum(isnull([DecemberUSD],0)) AS [DecemberUSD]
		FROM  [dbo].[InverseSale] childSale
		INNER JOIN client c
		ON childSale.ClientId = c.ClientId
		WHERE c.GroupId IS NOT NULL 
		GROUP BY [GroupId]
		        ,[GmidId]
		        ,[CampaignId]
	   ) e	   
	   GROUP BY ClientId ,CampaignId ,GmidId");


$this->execute("-- VIEW SALE WITH client_product AND trade_product
ALTER VIEW [dbo].[SaleFormat]
AS
SELECT cp.ClientProductId,
	   cp.ClientId,
	   NULL AS GmidId,
	   cp.TradeProductId,
	   cp.IsForecastable,
		invSale.CampaignId,
		SUM(invSale.January) AS January ,SUM(invSale.February) AS February,SUM(invSale.March) AS March,
		'Q1'= isnull(SUM(invSale.January),0)+isnull(SUM(invSale.February),0)+isnull(SUM(invSale.March),0),		
		SUM(invSale.April) AS April,SUM(invSale.May) AS May,SUM(invSale.June) AS June,
		'Q2'= isnull(SUM(invSale.April),0)+isnull(SUM(invSale.May),0)+isnull(SUM(invSale.June),0),		
		SUM(invSale.July) AS July,SUM(invSale.August) AS August,SUM(invSale.September) AS September,
		'Q3'= isnull(SUM(invSale.July),0)+isnull(SUM(invSale.August),0)+isnull(SUM(invSale.September),0),	
		SUM(invSale.October) AS October,SUM(invSale.November) AS November,SUM(invSale.December) AS December,
	    'Q4'= isnull(SUM(invSale.October),0)+isnull(SUM(invSale.November),0)+isnull(SUM(invSale.December),0),	


		SUM(invSale.JanuaryUSD) AS JanuaryUSD ,SUM(invSale.FebruaryUSD) AS FebruaryUSD,SUM(invSale.MarchUSD) AS MarchUSD,
		'Q1USD'= isnull(SUM(invSale.JanuaryUSD),0)+isnull(SUM(invSale.FebruaryUSD),0)+isnull(SUM(invSale.MarchUSD),0),		
		SUM(invSale.AprilUSD) AS AprilUSD,SUM(invSale.MayUSD) AS MayUSD,SUM(invSale.JuneUSD) AS JuneUSD,
		'Q2USD'= isnull(SUM(invSale.AprilUSD),0)+isnull(SUM(invSale.MayUSD),0)+isnull(SUM(invSale.JuneUSD),0),		
		SUM(invSale.JulyUSD) AS JulyUSD,SUM(invSale.AugustUSD) AS AugustUSD,SUM(invSale.SeptemberUSD) AS SeptemberUSD,
		'Q3USD'= isnull(SUM(invSale.JulyUSD),0)+isnull(SUM(invSale.AugustUSD),0)+isnull(SUM(invSale.SeptemberUSD),0),	
		SUM(invSale.OctoberUSD) AS OctoberUSD,SUM(invSale.NovemberUSD) AS NovemberUSD,SUM(invSale.DecemberUSD) AS DecemberUSD,
	    'Q4USD'= isnull(SUM(invSale.OctoberUSD),0)+isnull(SUM(invSale.NovemberUSD),0)+isnull(SUM(invSale.DecemberUSD),0)	
FROM dbo.SaleToForecast invSale
INNER JOIN dbo.gmid g 
ON g.GmidId = invSale.GmidId
INNER JOIN dbo.trade_product tp 
ON tp.TradeProductId = g.TradeProductId
INNER JOIN dbo.client_product cp 
ON invSale.ClientId = cp.ClientId AND cp.TradeProductId = tp.TradeProductId AND cp.GmidId IS NULL
GROUP BY cp.ClientProductId, cp.ClientId, cp.TradeProductId, cp.IsForecastable,	invSale.CampaignId
UNION
SELECT cp.ClientProductId,
	   cp.ClientId,
	   cp.GmidId,
	   cp.TradeProductId,
	   cp.IsForecastable,
		invSale.CampaignId,
		invSale.January,invSale.February,invSale.March,
		'Q1'= isnull(invSale.January,0)+isnull(invSale.February,0)+isnull(invSale.March,0),		
		invSale.April,invSale.May,invSale.June,
		'Q2'= isnull(invSale.April,0)+isnull(invSale.May,0)+isnull(invSale.June,0),		
		invSale.July,invSale.August,invSale.September,
		'Q3'= isnull(invSale.July,0)+isnull(invSale.August,0)+isnull(invSale.September,0),	
		invSale.October,invSale.November,invSale.December,
	    'Q4'= isnull(invSale.October,0)+isnull(invSale.November,0)+isnull(invSale.December,0)	,

		invSale.JanuaryUSD,invSale.FebruaryUSD,invSale.MarchUSD,
		'Q1USD'= isnull(invSale.JanuaryUSD,0)+isnull(invSale.FebruaryUSD,0)+isnull(invSale.MarchUSD,0),		
		invSale.AprilUSD,invSale.MayUSD,invSale.JuneUSD,
		'Q2USD'= isnull(invSale.AprilUSD,0)+isnull(invSale.MayUSD,0)+isnull(invSale.JuneUSD,0),		
		invSale.JulyUSD,invSale.AugustUSD,invSale.SeptemberUSD,
		'Q3USD'= isnull(invSale.JulyUSD,0)+isnull(invSale.AugustUSD,0)+isnull(invSale.SeptemberUSD,0),	
		invSale.OctoberUSD,invSale.NovemberUSD,invSale.DecemberUSD,
	    'Q4USD'= isnull(invSale.OctoberUSD,0)+isnull(invSale.NovemberUSD,0)+isnull(invSale.DecemberUSD,0)	
FROM dbo.SaleToForecast invSale
INNER JOIN dbo.gmid g 
ON g.GmidId = invSale.GmidId
INNER JOIN dbo.trade_product tp 
ON tp.TradeProductId = g.TradeProductId
INNER JOIN dbo.client_product cp 
ON invSale.ClientId = cp.ClientId AND cp.GmidId = g.GmidId 
");

$this->execute("ALTER VIEW [dbo].[SaleWithForecast]
AS

SELECT  result.*, 
		'Q1' = (result.January + result.February + result.March),
		'Q2' = (result.April + result.May + result.June),
		'Q3' = (result.July + result.August + result.September),
		'Q4' = (result.October + result.November + result.December),
		'Total' = result.January + result.February + result.March + result.April + result.May + result.June +result.July + result.August + result.September + result.October + result.November + result.December,
		'JanuarySaleForecastUSD' =  CASE WHEN 1 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM') THEN isnull(result.JanuaryUSD,0) ELSE isnull(result.January,0)*isnull(result.ForecastPrice,0)  END,
	    'FebruarySaleForecastUSD' = CASE WHEN 2 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM') THEN isnull(result.FebruaryUSD,0) ELSE isnull(result.February,0)*isnull(result.ForecastPrice,0) END,
	    'MarchSaleForecastUSD' =  CASE WHEN 3 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM') THEN isnull(result.MarchUSD,0) ELSE isnull(result.March,0)*isnull(result.ForecastPrice,0) END,
	    'AprilSaleForecastUSD' =  CASE WHEN 4 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM') THEN isnull(result.AprilUSD,0) ELSE isnull(result.April,0)*isnull(result.ForecastPrice,0) END,
	    'MaySaleForecastUSD' =  CASE WHEN 5 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM') THEN isnull(result.MayUSD,0) ELSE isnull(result.May,0)*isnull(result.ForecastPrice,0) END,
	    'JuneSaleForecastUSD' = CASE WHEN 6 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM') THEN isnull(result.JuneUSD,0) ELSE isnull(result.June,0)*isnull(result.ForecastPrice,0) END,
	    'JulySaleForecastUSD' = CASE WHEN 7 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM') THEN isnull(result.JulyUSD,0) ELSE isnull(result.July,0)*isnull(result.ForecastPrice,0) END,
	    'AugustSaleForecastUSD' = CASE WHEN 8 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM') THEN isnull(result.AugustUSD,0) ELSE isnull(result.August,0)*isnull(result.ForecastPrice,0) END,
	    'SeptemberSaleForecastUSD' =CASE WHEN 9 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM') THEN isnull(result.SeptemberUSD,0) ELSE isnull(result.September,0)*isnull(result.ForecastPrice,0) END,
	    'OctoberSaleForecastUSD' = CASE WHEN 10 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM') THEN isnull(result.OctoberUSD,0) ELSE isnull(result.October,0)*isnull(result.ForecastPrice,0) END,
	    'NovemberSaleForecastUSD' = CASE WHEN 11 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM') THEN isnull(result.NovemberUSD,0) ELSE isnull(result.November,0)*isnull(result.ForecastPrice,0) END,
	    'DecemberSaleForecastUSD' = CASE WHEN 12 < (SELECT TOP 1 Value FROM setting where DisplayName = 'FORECAST_ENABLE_FROM')THEN isnull(result.DecemberUSD,0) ELSE isnull(result.December,0)*isnull(result.ForecastPrice,0) END

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
ON f.ClientProductId = sal.ClientProductId and sal.CampaignId = f.CampaignId
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







$this->execute("CREATE VIEW [dbo].[TableResume]
AS

SELECT items.CampaignId,
	   items.SellerName,
	   items.SellerId,	   
	   dsm.UserId AS DsmId,
	   rsm.UserId AS RsmId,
	   TradeProductId,
	   PerformanceCenterId,
	   ValueCenterId,
	   SUM(Q1ForecastMoreSaleVolume) AS Q1ForecastMoreSaleVolume,
	   SUM(Q2ForecastMoreSaleVolume) AS Q2ForecastMoreSaleVolume,
	   SUM(Q3ForecastMoreSaleVolume) AS Q3ForecastMoreSaleVolume,
	   SUM(Q4ForecastMoreSaleVolume) AS Q4ForecastMoreSaleVolume,

	   SUM(Q1ForecastMoreSaleUSD) AS Q1ForecastMoreSaleUSD,
	   SUM(Q2ForecastMoreSaleUSD) AS Q2ForecastMoreSaleUSD,
	   SUM(Q3ForecastMoreSaleUSD) AS Q3ForecastMoreSaleUSD,
	   SUM(Q4ForecastMoreSaleUSD) AS Q4ForecastMoreSaleUSD,

	   SUM(Q1PlanVolume) AS Q1PlanVolume,
	   SUM(Q2PlanVolume) AS Q2PlanVolume,
	   SUM(Q3PlanVolume) AS Q3PlanVolume,
	   SUM(Q4PlanVolume) AS Q4PlanVolume,	

	   SUM(Q1PlanUSD) AS Q1PlanUSD,
	   SUM(Q2PlanUSD) AS Q2PlanUSD,
	   SUM(Q3PlanUSD) AS Q3PlanUSD,
	   SUM(Q4PlanUSD) AS Q4PlanUSD,

	   SUM(TotalPlanVolume) AS TotalPlanVolume,
	   SUM(TotalPlanUSD) AS TotalPlanUSD,

	   SUM(TotalCyOVolume) AS TotalCyOVolume,
	   SUM(TotalCyOUSD) AS TotalCyOUSD , 

	   SUM(TotalForecastMoreSaleVolume) AS 'TotalForecastMoreSaleVolume',
	   SUM(Q1ForecastMoreSaleUSD + Q2ForecastMoreSaleUSD + Q3ForecastMoreSaleUSD +Q4ForecastMoreSaleUSD) AS 'TotalForecastMoreSaleUSD',
	   SUM(items.Profit) AS Profit
FROM
(
SELECT CampaignId,
	   SellerName,
	   SellerId,	
	   TradeProductId,
	   PerformanceCenterId,
	   ValueCenterId,
	   SUM(Q1) AS Q1ForecastMoreSaleVolume,
	   SUM(Q2) AS Q2ForecastMoreSaleVolume,
	   SUM(Q3) AS Q3ForecastMoreSaleVolume,
	   SUM(Q4) AS Q4ForecastMoreSaleVolume ,
	   SUM(isnull(JanuarySaleForecastUSD,0))+ SUM(isnull(FebruarySaleForecastUSD,0)) + SUM(isnull(MarchSaleForecastUSD,0)) AS Q1ForecastMoreSaleUSD,
	   SUM(isnull(AprilSaleForecastUSD,0))+ SUM(isnull(MaySaleForecastUSD,0)) + SUM(isnull(JuneSaleForecastUSD,0)) AS Q2ForecastMoreSaleUSD,
	   SUM(isnull(JulySaleForecastUSD,0))+ SUM(isnull(AugustSaleForecastUSD,0)) + SUM(isnull(SeptemberSaleForecastUSD,0)) AS Q3ForecastMoreSaleUSD,
	   SUM(isnull(OctoberSaleForecastUSD,0))+ SUM(isnull(NovemberSaleForecastUSD,0)) + SUM(isnull(DecemberSaleForecastUSD,0)) AS Q4ForecastMoreSaleUSD	   ,
	   SUM(Total) AS TotalForecastMoreSaleVolume,
	   0 AS 'Q1PlanVolume',
	   0 AS 'Q2PlanVolume',
	   0 AS 'Q3PlanVolume',
	   0 AS 'Q4PlanVolume',
	   0 AS 'TotalPlanVolume',
	   0 AS 'Q1PlanUSD',
	   0 AS 'Q2PlanUSD',
	   0 AS 'Q3PlanUSD',
	   0 AS 'Q4PlanUSD',
	   0 AS 'TotalPlanUSD',
	   0 AS 'TotalCyOVolume',
	   0 AS 'TotalCyOUSD',
	   0 AS 'Profit'
FROM  SaleWithForecast
GROUP BY SellerId,SellerName,CampaignId,TradeProductId,PerformanceCenterId,ValueCenterId

UNION ALL 

SELECT CampaignId,
	   SellerName,
	   SellerId,
	   TradeProductId,
	   PerformanceCenterId,
	   ValueCenterId,
	   0 AS 'Q1ForecastMoreSaleVolume',
	   0 AS 'Q2ForecastMoreSaleVolume',
	   0 AS 'Q3ForecastMoreSaleVolume',
	   0 AS 'Q4ForecastMoreSaleVolume',	
	   0 AS 'Q1ForecastMoreSaleUSD',
	   0 AS 'Q2ForecastMoreSaleUSD',
	   0 AS 'Q3ForecastMoreSaleUSD',
	   0 AS 'Q4ForecastMoreSaleUSD',
	   0 AS 'TotalForecastMoreSaleVolume',
	   SUM(Q1) AS Q1PlanVolume,
	   SUM(Q2) AS Q2PlanVolume,
	   SUM(Q3) AS Q3PlanVolume,
	   SUM(Q4) AS Q4PlanVolume , 
	   SUM(Total) AS TotalPlanVolume,
	   SUM(PlanPrice*Q1) AS Q1PlanUSD,
	   SUM(PlanPrice*Q2) AS Q2PlanUSD,
	   SUM(PlanPrice*Q3) AS Q3PlanUSD,
	   SUM(PlanPrice*Q4) AS Q4PlanUSD,
	   SUM(PlanPrice*Q1) + SUM(PlanPrice*Q2) + SUM(PlanPrice*Q3)+ SUM(PlanPrice*Q4) AS TotalPlanUSD,
	   0  AS 'TotalCyOVolume',
	   0 AS 'TotalCyOUSD',
	   0 AS 'Profit'
FROM SaleWithPlan
GROUP BY SellerId,SellerName,CampaignId,TradeProductId,PerformanceCenterId,ValueCenterId

UNION ALL 

SELECT c.CampaignId,
	   seller.Fullname AS SellerName,
	   seller.UserId AS SellerId,
	   tp.TradeProductId,
	   pc.PerformanceCenterId,
	   ValueCenterId	,
	   0 AS 'Q1ForecastMoreSaleVolume',
	   0 AS 'Q2ForecastMoreSaleVolume',
	   0 AS 'Q3ForecastMoreSaleVolume',
	   0 AS 'Q4ForecastMoreSaleVolume',	
	   0 AS 'TotalForecastMoreSaleVolume',
	   0 AS 'Q1ForecastMoreSaleUSD',
	   0 AS 'Q2ForecastMoreSaleUSD',
	   0 AS 'Q3ForecastMoreSaleUSD',
	   0 AS 'Q4ForecastMoreSaleUSD',
	   0 AS 'Q1PlanVolume',
	   0 AS 'Q2PlanVolume',
	   0 AS 'Q3PlanVolume',
	   0 AS 'Q4PlanVolume',
	   0 AS 'TotalPlanVolume',
	   0 AS 'Q1PlanUSD',
	   0 AS 'Q2PlanUSD',
	   0 AS 'Q3PlanUSD',
	   0 AS 'Q4PlanUSD',
	   0 AS 'TotalPlanUSD',
	   SUM(c.InventoryBalance) AS TotalCyOVolume,
	   SUM(c.InventoryBalance * g.Price) AS TotalCyOUSD,
	   0 AS 'Profit'

FROM cyo c
INNER JOIN gmid g 
ON c.GmidId = g.GmidId
INNER JOIN trade_product tp 
ON tp.TradeProductId = g.TradeProductId
INNER JOIN performance_center pc
ON pc.PerformanceCenterId = tp.PerformanceCenterId
INNER JOIN client_seller cs 
ON cs.ClientId = c.ClientId
INNER JOIN [user] seller 
ON seller.UserId = cs.SellerId

GROUP BY seller.UserId, seller.Fullname , c.CampaignId,tp.TradeProductId,pc.PerformanceCenterId,ValueCenterId


) items
INNER JOIN [user] seller  
ON seller.UserId = items.SellerId
INNER JOIN [user] dsm 
ON dsm.UserId = seller.ParentId
INNER JOIN [user] rsm 
ON rsm.UserId = dsm.ParentId
GROUP BY items.CampaignId,items.SellerName ,items.SellerId , dsm.UserId , rsm.UserId,TradeProductId,  PerformanceCenterId,  ValueCenterId

") ;
$this->execute("CREATE VIEW [dbo].[ReportComparativeBySellers]
AS

SELECT items.CampaignId,
	   items.SellerName,	   
	   dsm.UserId AS DsmId,
	   rsm.UserId AS RsmId,
	   SUM(Q1ForecastMoreSaleVolume) AS Q1ForecastMoreSaleVolume,
	   SUM(Q2ForecastMoreSaleVolume) AS Q2ForecastMoreSaleVolume,
	   SUM(Q3ForecastMoreSaleVolume) AS Q3ForecastMoreSaleVolume,
	   SUM(Q4ForecastMoreSaleVolume) AS Q4ForecastMoreSaleVolume,

	   SUM(Q1ForecastMoreSaleUSD) AS Q1ForecastMoreSaleUSD,
	   SUM(Q2ForecastMoreSaleUSD) AS Q2ForecastMoreSaleUSD,
	   SUM(Q3ForecastMoreSaleUSD) AS Q3ForecastMoreSaleUSD,
	   SUM(Q4ForecastMoreSaleUSD) AS Q4ForecastMoreSaleUSD,

	   SUM(Q1PlanVolume) AS Q1PlanVolume,
	   SUM(Q2PlanVolume) AS Q2PlanVolume,
	   SUM(Q3PlanVolume) AS Q3PlanVolume,
	   SUM(Q4PlanVolume) AS Q4PlanVolume,	

	   SUM(Q1PlanUSD) AS Q1PlanUSD,
	   SUM(Q2PlanUSD) AS Q2PlanUSD,
	   SUM(Q3PlanUSD) AS Q3PlanUSD,
	   SUM(Q4PlanUSD) AS Q4PlanUSD,

	   SUM(TotalPlanVolume) AS TotalPlanVolume,
	   SUM(TotalPlanUSD) AS TotalPlanUSD,

	   SUM(TotalCyOVolume) AS TotalCyOVolume,
	   SUM(TotalCyOUSD) AS TotalCyOUSD , 

	   SUM(TotalForecastMoreSaleVolume) AS 'TotalForecastMoreSaleVolume',
	   SUM(Q1ForecastMoreSaleUSD + Q2ForecastMoreSaleUSD + Q3ForecastMoreSaleUSD +Q4ForecastMoreSaleUSD) AS 'TotalForecastMoreSaleUSD',
	   SUM(items.Profit) AS Profit
FROM
(
SELECT CampaignId,
	   SellerName,
	   SellerId,	
	   SUM(Q1) AS Q1ForecastMoreSaleVolume,
	   SUM(Q2) AS Q2ForecastMoreSaleVolume,
	   SUM(Q3) AS Q3ForecastMoreSaleVolume,
	   SUM(Q4) AS Q4ForecastMoreSaleVolume ,
	   SUM(isnull(JanuarySaleForecastUSD,0))+ SUM(isnull(FebruarySaleForecastUSD,0)) + SUM(isnull(MarchSaleForecastUSD,0)) AS Q1ForecastMoreSaleUSD,
	   SUM(isnull(AprilSaleForecastUSD,0))+ SUM(isnull(MaySaleForecastUSD,0)) + SUM(isnull(JuneSaleForecastUSD,0)) AS Q2ForecastMoreSaleUSD,
	   SUM(isnull(JulySaleForecastUSD,0))+ SUM(isnull(AugustSaleForecastUSD,0)) + SUM(isnull(SeptemberSaleForecastUSD,0)) AS Q3ForecastMoreSaleUSD,
	   SUM(isnull(OctoberSaleForecastUSD,0))+ SUM(isnull(NovemberSaleForecastUSD,0)) + SUM(isnull(DecemberSaleForecastUSD,0)) AS Q4ForecastMoreSaleUSD	   ,
	   SUM(Total) AS TotalForecastMoreSaleVolume,
	   0 AS 'Q1PlanVolume',
	   0 AS 'Q2PlanVolume',
	   0 AS 'Q3PlanVolume',
	   0 AS 'Q4PlanVolume',
	   0 AS 'TotalPlanVolume',
	   0 AS 'Q1PlanUSD',
	   0 AS 'Q2PlanUSD',
	   0 AS 'Q3PlanUSD',
	   0 AS 'Q4PlanUSD',
	   0 AS 'TotalPlanUSD',
	   0 AS 'TotalCyOVolume',
	   0 AS 'TotalCyOUSD',
	   0 AS 'Profit'
FROM  SaleWithForecast
GROUP BY SellerId,SellerName,CampaignId

UNION ALL 

SELECT CampaignId,
	   SellerName,
	   SellerId,
	   0 AS 'Q1ForecastMoreSaleVolume',
	   0 AS 'Q2ForecastMoreSaleVolume',
	   0 AS 'Q3ForecastMoreSaleVolume',
	   0 AS 'Q4ForecastMoreSaleVolume',	
	   0 AS 'Q1ForecastMoreSaleUSD',
	   0 AS 'Q2ForecastMoreSaleUSD',
	   0 AS 'Q3ForecastMoreSaleUSD',
	   0 AS 'Q4ForecastMoreSaleUSD',
	   0 AS 'TotalForecastMoreSaleVolume',
	   SUM(Q1) AS Q1PlanVolume,
	   SUM(Q2) AS Q2PlanVolume,
	   SUM(Q3) AS Q3PlanVolume,
	   SUM(Q4) AS Q4PlanVolume , 
	   SUM(Total) AS TotalPlanVolume,
	   SUM(PlanPrice*Q1) AS Q1PlanUSD,
	   SUM(PlanPrice*Q2) AS Q2PlanUSD,
	   SUM(PlanPrice*Q3) AS Q3PlanUSD,
	   SUM(PlanPrice*Q4) AS Q4PlanUSD,
	   SUM(PlanPrice*Q1) + SUM(PlanPrice*Q2) + SUM(PlanPrice*Q3)+ SUM(PlanPrice*Q4) AS TotalPlanUSD,
	   0  AS 'TotalCyOVolume',
	   0 AS 'TotalCyOUSD',
	   0 AS 'Profit'
FROM SaleWithPlan
GROUP BY SellerId,SellerName,CampaignId

UNION ALL 

SELECT c.CampaignId,
	   seller.Fullname AS SellerName,
	   seller.UserId AS SellerId,	
	   0 AS 'Q1ForecastMoreSaleVolume',
	   0 AS 'Q2ForecastMoreSaleVolume',
	   0 AS 'Q3ForecastMoreSaleVolume',
	   0 AS 'Q4ForecastMoreSaleVolume',	
	   0 AS 'TotalForecastMoreSaleVolume',
	   0 AS 'Q1ForecastMoreSaleUSD',
	   0 AS 'Q2ForecastMoreSaleUSD',
	   0 AS 'Q3ForecastMoreSaleUSD',
	   0 AS 'Q4ForecastMoreSaleUSD',
	   0 AS 'Q1PlanVolume',
	   0 AS 'Q2PlanVolume',
	   0 AS 'Q3PlanVolume',
	   0 AS 'Q4PlanVolume',
	   0 AS 'TotalPlanVolume',
	   0 AS 'Q1PlanUSD',
	   0 AS 'Q2PlanUSD',
	   0 AS 'Q3PlanUSD',
	   0 AS 'Q4PlanUSD',
	   0 AS 'TotalPlanUSD',
	   SUM(c.InventoryBalance) AS TotalCyOVolume,
	   SUM(c.InventoryBalance * g.Price) AS TotalCyOUSD,
	   0 AS 'Profit'

FROM cyo c
INNER JOIN gmid g 
ON c.GmidId = g.GmidId
INNER JOIN client_seller cs 
ON cs.ClientId = c.ClientId
INNER JOIN [user] seller 
ON seller.UserId = cs.SellerId

GROUP BY seller.UserId, seller.Fullname , c.CampaignId

UNION ALL 

SELECT s.CampaignId,
	   seller.Fullname AS SellerName,
	   seller.UserId AS SellerId,	
	   0 AS 'Q1ForecastMoreSaleVolume',
	   0 AS 'Q2ForecastMoreSaleVolume',
	   0 AS 'Q3ForecastMoreSaleVolume',
	   0 AS 'Q4ForecastMoreSaleVolume',	
	   0 AS 'TotalForecastMoreSaleVolume',
	   0 AS 'Q1ForecastMoreSaleUSD',
	   0 AS 'Q2ForecastMoreSaleUSD',
	   0 AS 'Q3ForecastMoreSaleUSD',
	   0 AS 'Q4ForecastMoreSaleUSD',
	   0 AS 'Q1PlanVolume',
	   0 AS 'Q2PlanVolume',
	   0 AS 'Q3PlanVolume',
	   0 AS 'Q4PlanVolume',
	   0 AS 'TotalPlanVolume',
	   0 AS 'Q1PlanUSD',
	   0 AS 'Q2PlanUSD',
	   0 AS 'Q3PlanUSD',
	   0 AS 'Q4PlanUSD',
	   0 AS 'TotalPlanUSD',
	   0 AS 'TotalCyOVolume',
	   0 AS 'TotalCyOUSD',
	  (SUM(g.Profit * s.Total) / SUM(s.Total) ) *100 AS Profit
FROM sale s
INNER JOIN gmid  g
ON s.GmidId = g.GmidId
INNER JOIN client_seller cs 
ON cs.ClientId = s.ClientId
INNER JOIN [user] seller 
ON seller.UserId = cs.SellerId
GROUP BY s.CampaignId,seller.UserId,seller.Fullname

) items
INNER JOIN [user] seller  
ON seller.UserId = items.SellerId
INNER JOIN [user] dsm 
ON dsm.UserId = seller.ParentId
INNER JOIN [user] rsm 
ON rsm.UserId = dsm.ParentId
GROUP BY items.CampaignId,items.SellerName , dsm.UserId , rsm.UserId");

 
$this->execute("ALTER VIEW [dbo].[GmidUnionTrade]
AS	
SELECT g.GmidId,	
	   TradeProductId = NULL,		
	   Description = g.Description,
	   tp.Description AS TradeProduct,
	   pc.Description AS PerformanceCenter ,
	   vc.Description AS ValueCenter,
	   tp.Price,
	   tp.Profit,
	   g.CountryId,
	   g.IsActive
FROM gmid g
INNER JOIN trade_product tp
ON g.TradeProductId = tp.TradeProductId
INNER JOIN performance_center pc 
ON pc.PerformanceCenterId = tp.PerformanceCenterId
INNER JOIN value_center vc 
ON vc.ValueCenterId = pc.ValueCenterId
WHERE g.IsForecastable = 1
UNION
SELECT GmidId = NULL,
	   tp.TradeProductId,
	   Description = tp.Description,
	   tp.Description AS TradeProduct,
	   pc.Description AS PerformanceCenter ,
	   vc.Description AS ValueCenter,
	   tp.Price,
	   tp.Profit,
	   NULL AS CountryId,
	   tp.IsActive
FROM trade_product tp
INNER JOIN gmid g 
ON g.TradeProductId = tp.TradeProductId
INNER JOIN performance_center pc 
ON pc.PerformanceCenterId = tp.PerformanceCenterId
INNER JOIN value_center vc 
ON vc.ValueCenterId = pc.ValueCenterId
WHERE tp.IsForecastable = 1
GROUP BY tp.TradeProductId,tp.Description,pc.Description,vc.Description,tp.Price,tp.Profit, tp.IsActive


");

  
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


$this->execute("ALTER VIEW [dbo].[ExportComparative]
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
  ON dsm.UserId = seller.ParentId");
     


   // STORES


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

	INSERT #ERRORS(GMID,CAUSE)
	SELECT  ts.GMID,			
			'GMID INEXISTENTE'
	FROM TEMP_SALE ts
	WHERE NOT EXISTS(SELECT 1 FROM gmid g WHERE g.GmidId =ts.GMID )
	GROUP BY ts.GMID
	ORDER BY ts.GMID


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


$this->execute("
ALTER PROCEDURE [dbo].[SP_ImportProducts]
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
	DROP TABLE  #ERRORS
");

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

$this->execute("ALTER PROCEDURE [dbo].[SP_ImportCustomer]
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

  END;
    DELETE FROM TEMP_CUSTOMER;");



    }

    public function down() {
        echo "m151111_175413_fix3_sprint9 cannot be reverted.\n";

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
