CREATE VIEW vw_forecast_total_by_client_and_tradeproduct AS
SELECT
    ClientProduct.ClientId AS ClientId,
    Forecast.CampaignId,
    CASE WHEN (SUM(ForecastGmidTotal.January)>0) THEN SUM(COALESCE(Forecast.January, 0))*100/SUM(ForecastGmidTotal.January) ELSE 0 END AS January,
    CASE WHEN (SUM(ForecastGmidTotal.February)>0) THEN SUM(COALESCE(Forecast.February, 0))*100/SUM(ForecastGmidTotal.February) ELSE 0 END AS February,
    CASE WHEN (SUM(ForecastGmidTotal.March)>0) THEN SUM(COALESCE(Forecast.March, 0))*100/SUM(ForecastGmidTotal.March) ELSE 0 END AS March,
    CASE WHEN (SUM(ForecastGmidTotal.April)>0) THEN SUM(COALESCE(Forecast.April, 0))*100/SUM(ForecastGmidTotal.April) ELSE 0 END AS April,
    CASE WHEN (SUM(ForecastGmidTotal.May)>0) THEN SUM(COALESCE(Forecast.May, 0))*100/SUM(ForecastGmidTotal.May) ELSE 0 END AS May,
    CASE WHEN (SUM(ForecastGmidTotal.June)>0) THEN SUM(COALESCE(Forecast.June, 0))*100/SUM(ForecastGmidTotal.June) ELSE 0 END AS June,
    CASE WHEN (SUM(ForecastGmidTotal.July)>0) THEN SUM(COALESCE(Forecast.July, 0))*100/SUM(ForecastGmidTotal.July) ELSE 0 END AS July,
    CASE WHEN (SUM(ForecastGmidTotal.August)>0) THEN SUM(COALESCE(Forecast.August, 0))*100/SUM(ForecastGmidTotal.August) ELSE 0 END AS August,
    CASE WHEN (SUM(ForecastGmidTotal.September)>0) THEN SUM(COALESCE(Forecast.September, 0))*100/SUM(ForecastGmidTotal.September) ELSE 0 END AS September,
    CASE WHEN (SUM(ForecastGmidTotal.October)>0) THEN SUM(COALESCE(Forecast.October, 0))*100/SUM(ForecastGmidTotal.October) ELSE 0 END AS October,
    CASE WHEN (SUM(ForecastGmidTotal.November)>0) THEN SUM(COALESCE(Forecast.November, 0))*100/SUM(ForecastGmidTotal.November) ELSE 0 END AS November,
    CASE WHEN (SUM(ForecastGmidTotal.December)>0) THEN SUM(COALESCE(Forecast.December, 0))*100/SUM(ForecastGmidTotal.December) ELSE 0 END AS December
FROM
    forecast AS Forecast
        INNER JOIN client_product AS ClientProduct ON
            ClientProduct.ClientProductId = Forecast.ClientProductId
        INNER JOIN vw_forecast_gmid_total ForecastGmidTotal ON ForecastGmidTotal.TradeProductId = ClientProduct.TradeProductId AND Forecast.CampaignId = ForecastGmidTotal.CampaignId
GROUP BY ClientProduct.ClientId, Forecast.CampaignId, ForecastGmidTotal.TradeProductId ;
