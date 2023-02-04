-- DROP TABLE tmp_forecast_gmid_total;

CREATE TABLE tmp_forecast_gmid_total (
     TradeProductId int NOT NULL,
     CampaignId int NOT NULL,
     January float DEFAULT 0.0 NOT NULL,
     February float DEFAULT 0 NOT NULL,
     March float DEFAULT 0 NOT NULL,
     April float DEFAULT 0 NOT NULL,
     May float DEFAULT 0 NOT NULL,
     June float DEFAULT 0 NOT NULL,
     July float DEFAULT 0 NOT NULL,
     August float DEFAULT 0 NOT NULL,
     September float DEFAULT 0 NOT NULL,
     October float DEFAULT 0 NOT NULL,
     November float DEFAULT 0 NOT NULL,
     December float DEFAULT 0 NOT NULL
);

-- DROP TABLE tmp_forecast_total_by_client_and_tradeproduct;

CREATE TABLE tmp_forecast_total_by_client_and_tradeproduct (
       ClientId int NOT NULL,
       TradeProductId int NOT NULL,
       CampaignId int NOT NULL,
       January float DEFAULT 0 NOT NULL,
       February float DEFAULT 0 NOT NULL,
       March float DEFAULT 0 NOT NULL,
       April float DEFAULT 0 NOT NULL,
       May float DEFAULT 0 NOT NULL,
       June float DEFAULT 0 NOT NULL,
       July float DEFAULT 0 NOT NULL,
       August float DEFAULT 0 NOT NULL,
       September float DEFAULT 0 NOT NULL,
       October float DEFAULT 0 NOT NULL,
       November float DEFAULT 0 NOT NULL,
       December float DEFAULT 0 NOT NULL
);
-- DROP PROCEDURE [dbo].[SP_Forecast_Client_Product_Percentage];
CREATE PROCEDURE [dbo].[SP_Forecast_Client_Product_Percentage]
AS
BEGIN

    DROP TABLE IF EXISTS #forecast_gmid_total;

    DROP TABLE IF EXISTS #country_abbr;

    CREATE TABLE #country_abbr(
                                  abbr varchar(30) NOT NULL,
                                  CountryId INT NOT NULL
    );

    INSERT INTO #country_abbr(abbr, CountryId) VALUES
       ('AR', (SELECT CountryId from country where Abbreviation = 'ARG')),
       ('BO', (SELECT CountryId from country where Abbreviation = 'BOL')),
       ('CL', (SELECT CountryId from country where Abbreviation = 'CHL')),
       ('UY', (SELECT CountryId from country where Abbreviation = 'URY')),
       ('PY', (SELECT CountryId from country where Abbreviation = 'PRY'));

-- DROP TABLE tmp_forecast_total_by_client_and_tradeproduct;

    CREATE TABLE #tmp_forecast_total_by_client_and_tradeproduct (
           ClientId int NOT NULL,
           TradeProductId int NOT NULL,
           CampaignId int NOT NULL,
           January float DEFAULT 0 NOT NULL,
           February float DEFAULT 0 NOT NULL,
           March float DEFAULT 0 NOT NULL,
           April float DEFAULT 0 NOT NULL,
           May float DEFAULT 0 NOT NULL,
           June float DEFAULT 0 NOT NULL,
           July float DEFAULT 0 NOT NULL,
           August float DEFAULT 0 NOT NULL,
           September float DEFAULT 0 NOT NULL,
           October float DEFAULT 0 NOT NULL,
           November float DEFAULT 0 NOT NULL,
           December float DEFAULT 0 NOT NULL
    );
    SELECT
        ClientProduct.TradeProductId AS TradeProductId,
        campaign.CampaignId,
        COALESCE(SUM(January), 0)AS January,
        COALESCE(SUM(February), 0) AS February,
        COALESCE(SUM(March), 0) AS March,
        COALESCE(SUM(April), 0) AS April,
        COALESCE(SUM(May), 0) AS May,
        COALESCE(SUM(June), 0) AS June,
        COALESCE(SUM(July), 0) AS July,
        COALESCE(SUM(August), 0) AS August,
        COALESCE(SUM(September), 0) AS September,
        COALESCE(SUM(October), 0) AS October,
        COALESCE(SUM(November), 0) AS November,
        COALESCE(SUM(December), 0) AS December
    INTO #tmp_forecast_gmid_total
    FROM
        forecast AS Forecast
            INNER JOIN campaign ON campaign.CampaignId = Forecast.CampaignId
            INNER JOIN client_product AS ClientProduct ON ClientProduct.ClientProductId = Forecast.ClientProductId
    WHERE (Forecast.January <> 0 OR Forecast.February <> 0 OR Forecast.March <> 0 OR Forecast.April <> 0 OR Forecast.May <> 0 OR Forecast.June <> 0 OR Forecast.July <> 0 OR Forecast.August <> 0 OR Forecast.September <> 0 OR Forecast.October <> 0 OR Forecast.November <> 0 OR Forecast.December <> 0)
      AND campaign.IsActual = 1
    GROUP BY ClientProduct.TradeProductId, campaign.CampaignId
    HAVING COALESCE(SUM(January), 0) <> 0 OR
            COALESCE(SUM(February), 0) <> 0 OR
            COALESCE(SUM(March), 0) <> 0 OR
            COALESCE(SUM(April), 0) <> 0 OR
            COALESCE(SUM(May), 0) <> 0 OR
            COALESCE(SUM(June), 0) <> 0 OR
            COALESCE(SUM(July), 0) <> 0 OR
            COALESCE(SUM(August), 0) <> 0 OR
            COALESCE(SUM(September), 0) <> 0 OR
            COALESCE(SUM(October), 0) <> 0 OR
            COALESCE(SUM(November), 0) <> 0 OR
            COALESCE(SUM(December), 0) <> 0;
    SELECT
        ClientProduct.ClientId,
        ForecastGmidTotal.TradeProductId,
        Forecast.CampaignId,
        CASE WHEN (SUM(ForecastGmidTotal.January)>0) THEN COALESCE(SUM(Forecast.January), 0)*100/SUM(ForecastGmidTotal.January) ELSE 0 END AS January,
        CASE WHEN (SUM(ForecastGmidTotal.February)>0) THEN COALESCE(SUM(Forecast.February), 0)*100/SUM(ForecastGmidTotal.February) ELSE 0 END AS February,
        CASE WHEN (SUM(ForecastGmidTotal.March)>0) THEN COALESCE(SUM(Forecast.March), 0)*100/SUM(ForecastGmidTotal.March) ELSE 0 END AS March,
        CASE WHEN (SUM(ForecastGmidTotal.April)>0) THEN COALESCE(SUM(Forecast.April), 0)*100/SUM(ForecastGmidTotal.April) ELSE 0 END AS April,
        CASE WHEN (SUM(ForecastGmidTotal.May)>0) THEN COALESCE(SUM(Forecast.May), 0)*100/SUM(ForecastGmidTotal.May) ELSE 0 END AS May,
        CASE WHEN (SUM(ForecastGmidTotal.June)>0) THEN COALESCE(SUM(Forecast.June), 0)*100/SUM(ForecastGmidTotal.June) ELSE 0 END AS June,
        CASE WHEN (SUM(ForecastGmidTotal.July)>0) THEN COALESCE(SUM(Forecast.July), 0)*100/SUM(ForecastGmidTotal.July) ELSE 0 END AS July,
        CASE WHEN (SUM(ForecastGmidTotal.August)>0) THEN COALESCE(SUM(Forecast.August), 0)*100/SUM(ForecastGmidTotal.August) ELSE 0 END AS August,
        CASE WHEN (SUM(ForecastGmidTotal.September)>0) THEN COALESCE(SUM(Forecast.September), 0)*100/SUM(ForecastGmidTotal.September) ELSE 0 END AS September,
        CASE WHEN (SUM(ForecastGmidTotal.October)>0) THEN COALESCE(SUM(Forecast.October), 0)*100/SUM(ForecastGmidTotal.October) ELSE 0 END AS October,
        CASE WHEN (SUM(ForecastGmidTotal.November)>0) THEN COALESCE(SUM(Forecast.November), 0)*100/SUM(ForecastGmidTotal.November) ELSE 0 END AS November,
        CASE WHEN (SUM(ForecastGmidTotal.December)>0) THEN COALESCE(SUM(Forecast.December), 0)*100/SUM(ForecastGmidTotal.December) ELSE 0 END AS December
    INTO #tmp_forecast_total_by_client_and_tradeproduct
    FROM
        forecast AS Forecast
            INNER JOIN client_product AS ClientProduct
                       ON ClientProduct.ClientProductId = Forecast.ClientProductId
            INNER JOIN tmp_forecast_gmid_total ForecastGmidTotal
                       ON ForecastGmidTotal.TradeProductId = ClientProduct.TradeProductId AND Forecast.CampaignId = ForecastGmidTotal.CampaignId
    WHERE  (ForecastGmidTotal.January!=0 OR ForecastGmidTotal.February!=0 OR ForecastGmidTotal.March!=0 OR ForecastGmidTotal.April!=0 OR ForecastGmidTotal.May!=0 OR ForecastGmidTotal.June!=0 OR ForecastGmidTotal.July!=0 OR ForecastGmidTotal.August!=0 OR ForecastGmidTotal.September!=0 OR ForecastGmidTotal.October!=0 OR ForecastGmidTotal.November!=0 OR ForecastGmidTotal.December!=0)
    GROUP BY ClientProduct.ClientId, Forecast.CampaignId, ForecastGmidTotal.TradeProductId
    HAVING SUM(ForecastGmidTotal.January)!=0 OR
            SUM(ForecastGmidTotal.February)!=0 OR
            SUM(ForecastGmidTotal.March)!=0 OR
            SUM(ForecastGmidTotal.April)!=0 OR
            SUM(ForecastGmidTotal.May)!=0 OR
            SUM(ForecastGmidTotal.June)!=0 OR
            SUM(ForecastGmidTotal.July)!=0 OR
            SUM(ForecastGmidTotal.August)!=0 OR
            SUM(ForecastGmidTotal.September)!=0 OR
            SUM(ForecastGmidTotal.October)!=0 OR
            SUM(ForecastGmidTotal.November)!=0 OR
            SUM(ForecastGmidTotal.December)!=0 ;

    SELECT OneGmidPerTradeproduct.GmidId AS GmidId,
           Campaign.CampaignId,
           ForecastTotalClientTradeProduct.ClientId,
           Country.CountryId,
           CONVERT(FLOAT, AVG(FCASTIBP.January)) * (CONVERT(FLOAT, AVG(ForecastTotalClientTradeProduct.January))/100) AS January,
           CONVERT(FLOAT, AVG(FCASTIBP.February)) * (CONVERT(FLOAT, AVG(ForecastTotalClientTradeProduct.February))/100) AS February,
           CONVERT(FLOAT, AVG(FCASTIBP.March)) * (CONVERT(FLOAT, AVG(ForecastTotalClientTradeProduct.March))/100) AS March,
           CONVERT(FLOAT, AVG(FCASTIBP.April)) * (CONVERT(FLOAT, AVG(ForecastTotalClientTradeProduct.April))/100) AS April,
           CONVERT(FLOAT, AVG(FCASTIBP.May)) * (CONVERT(FLOAT, AVG(ForecastTotalClientTradeProduct.May))/100) AS May,
           CONVERT(FLOAT, AVG(FCASTIBP.June)) * (CONVERT(FLOAT, AVG(ForecastTotalClientTradeProduct.June))/100) AS June,
           CONVERT(FLOAT, AVG(FCASTIBP.July)) * (CONVERT(FLOAT, AVG(ForecastTotalClientTradeProduct.July))/100) AS July,
           CONVERT(FLOAT, AVG(FCASTIBP.August)) * (CONVERT(FLOAT, AVG(ForecastTotalClientTradeProduct.August))/100) AS August,
           CONVERT(FLOAT, AVG(FCASTIBP.September)) * (CONVERT(FLOAT, AVG(ForecastTotalClientTradeProduct.September))/100) AS September,
           CONVERT(FLOAT, AVG(FCASTIBP.October)) * (CONVERT(FLOAT, AVG(ForecastTotalClientTradeProduct.October))/100) AS October,
           CONVERT(FLOAT, AVG(FCASTIBP.November)) * (CONVERT(FLOAT, AVG(ForecastTotalClientTradeProduct.November))/100) AS November,
           CONVERT(FLOAT, AVG(FCASTIBP.December)) * (CONVERT(FLOAT, AVG(ForecastTotalClientTradeProduct.December))/100) AS December
    INTO #forecastibp_by_client_product
    FROM FCASTIBP
             INNER JOIN campaign Campaign ON Campaign.Name  = FCASTIBP.Año
             INNER JOIN gmid Gmid ON CONVERT(INT, REPLACE(FCASTIBP.OldProductID , 'D','')) = Gmid.GmidId
             INNER JOIN tmp_forecast_total_by_client_and_tradeproduct AS ForecastTotalClientTradeProduct ON ForecastTotalClientTradeProduct.CampaignId = Campaign.CampaignId AND Gmid.TradeProductId = ForecastTotalClientTradeProduct.TradeProductId
             INNER JOIN one_gmid_per_tradeproduct AS OneGmidPerTradeproduct ON OneGmidPerTradeproduct.TradeProductId = ForecastTotalClientTradeProduct.TradeProductId
             INNER JOIN #country_abbr Country ON Country.abbr = FCASTIBP.ShipToCountry

    GROUP BY ForecastTotalClientTradeProduct.ClientId, OneGmidPerTradeproduct.GmidId, Campaign.CampaignId, Country.CountryId ;

END;