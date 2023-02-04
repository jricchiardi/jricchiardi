<?php

use yii\db\Migration;

class m151016_120814_sprint7 extends Migration
{
    public function safeUp()
    {
$this->execute("CREATE TABLE [dbo].[cyo](
	[CyoId] [int] IDENTITY(1,1) NOT NULL,
	[ClientId] [int] NULL,
	[GmidId] [varchar](20) NULL,
	[CampaignId] [int] NULL,
	[InventoryBalance] [decimal](10, 2) NULL,
 CONSTRAINT [PK_cyo] PRIMARY KEY CLUSTERED 
(
	[CyoId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [dbo].[TEMP_CYO](
	[ClientId] [int] NULL,
	[GmidId] [varchar](20) NULL,
	[CampaignId] [int] NULL,
	[InventoryBalance] [decimal](10, 2) NULL
) ON [PRIMARY]

SET ANSI_PADDING OFF

ALTER TABLE [dbo].[cyo]  WITH CHECK ADD  CONSTRAINT [FK_cyo_campaign] FOREIGN KEY([CampaignId])
REFERENCES [dbo].[campaign] ([CampaignId])

ALTER TABLE [dbo].[cyo] CHECK CONSTRAINT [FK_cyo_campaign]

ALTER TABLE [dbo].[cyo]  WITH CHECK ADD  CONSTRAINT [FK_cyo_client] FOREIGN KEY([ClientId])
REFERENCES [dbo].[client] ([ClientId])

ALTER TABLE [dbo].[cyo] CHECK CONSTRAINT [FK_cyo_client]

ALTER TABLE [dbo].[cyo]  WITH CHECK ADD  CONSTRAINT [FK_cyo_cyo] FOREIGN KEY([CyoId])
REFERENCES [dbo].[cyo] ([CyoId])

ALTER TABLE [dbo].[cyo] CHECK CONSTRAINT [FK_cyo_cyo]

ALTER TABLE [dbo].[cyo]  WITH CHECK ADD  CONSTRAINT [FK_cyo_gmid] FOREIGN KEY([GmidId])

REFERENCES [dbo].[gmid] ([GmidId])

ALTER TABLE [dbo].[cyo] CHECK CONSTRAINT [FK_cyo_gmid]
");

$this->execute("INSERT [dbo].[type_import] ([Name]) VALUES ( N'CyO') ");

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
		cli.Description AS Client

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
	    'Q4'= isnull(SUM(invSale.October),0)+isnull(SUM(invSale.November),0)+isnull(SUM(invSale.December),0)	
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
	    'Q4'= isnull(invSale.October,0)+isnull(invSale.November,0)+isnull(invSale.December,0)	
FROM dbo.SaleToForecast invSale
INNER JOIN dbo.gmid g 
ON g.GmidId = invSale.GmidId
INNER JOIN dbo.trade_product tp 
ON tp.TradeProductId = g.TradeProductId
INNER JOIN dbo.client_product cp 
ON invSale.ClientId = cp.ClientId AND cp.GmidId = g.GmidId 
");

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
GROUP BY tp.TradeProductId,tp.Description,pc.Description,vc.Description,tp.Price,tp.Profit, tp.IsActive");
    }

    public function safeDown()
    {
        echo "m151016_120814_sprint7 cannot be reverted.\n";

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
