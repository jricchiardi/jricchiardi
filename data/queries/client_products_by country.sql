-- select count(1) from client_product -- 117552


-----------------------------
-- DELETE client_crops
-----------------------------
delete from client_product
DBCC CHECKIDENT('client_product', RESEED, 0)



-----------------------------
-- inserting crop protection -- 60338
-----------------------------

insert into client_product(ClientId, GmidId, TradeProductId)

select distinct c.ClientId, g.GmidId,g.TradeProductId from client c
inner join gmid g on g.CountryId = c.CountryId
inner join trade_product tp on tp.TradeProductId = g.TradeProductId
inner join performance_center pc on pc.PerformanceCenterId  = tp.PerformanceCenterId
where pc.ValueCenterId = 8886 



-----------------------------
-- inserting seeds -- 57214
-----------------------------
insert into client_product(ClientId, GmidId, TradeProductId)
select distinct c.ClientId, null GmidId,g.TradeProductId from client c
inner join gmid g on g.CountryId = c.CountryId
inner join trade_product tp on tp.TradeProductId = g.TradeProductId
inner join performance_center pc on pc.PerformanceCenterId  = tp.PerformanceCenterId
where pc.ValueCenterId = 10111 


-----------------------------
-- DELETE plans
-----------------------------
delete from [plan]

-----------------------------
-- DELETE forecasts
-----------------------------
delete from forecast


---------------------------------------------
-- insert into plan crop protection products
---------------------------------------------
insert into [plan]([ClientProductId], [Price], [CampaignId])

(select cp.ClientProductId,  g.Price, 1 CampaignId
from client_product cp
inner join gmid g on g.GmidId = cp.GmidId
inner join trade_product tp on tp.TradeProductId = g.TradeProductId
inner join performance_center pc on pc.PerformanceCenterId  = tp.PerformanceCenterId
where cp.IsForecastable = 1 and pc.ValueCenterId = 8886 )


---------------------------------------------
-- insert into plan seeds products
---------------------------------------------
insert into [plan]([ClientProductId], [Price], [CampaignId])

(select cp.ClientProductId,  tp.Price, 1 CampaignId
from client_product cp
inner join trade_product tp on tp.TradeProductId = cp.TradeProductId
inner join performance_center pc on pc.PerformanceCenterId  = tp.PerformanceCenterId
where cp.IsForecastable = 1 and pc.ValueCenterId = 10111 )


---------------------------------------------
-- copy plans to forecast
---------------------------------------------
INSERT INTO [dbo].[forecast]
SELECT ClientProductId,CampaignId,
  January,February,March,Q1,
  April,May,June,Q2,
  July,
  August,
  September,
  Q3,
  October,
  November,
  December,
  Q4,
  Total
FROM [dbo].[plan] 