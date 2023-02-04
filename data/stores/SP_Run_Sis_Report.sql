
CREATE TABLE sis_report (
    CampaignId int NOT NULL,
    CountryId int NULL,
    DsmUserId int NOT NULL,
    DsmUsuario varchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
    TamUserId int NOT NULL,
    TamUsuario varchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
    GmidId int NOT NULL,
    GmidDescription varchar(150) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
    Ingredient varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
    ClientId int NOT NULL,
    ClientDescription varchar(150) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
    SaleInputJanuary int DEFAULT 0 NOT NULL,
    SaleInputFebruary int DEFAULT 0 NOT NULL,
    SaleInputMarch int DEFAULT 0 NOT NULL,
    SaleInputApril int DEFAULT 0 NOT NULL,
    SaleInputMay int DEFAULT 0 NOT NULL,
    SaleInputJune int DEFAULT 0 NOT NULL,
    SaleInputJuly int DEFAULT 0 NOT NULL,
    SaleInputAugust int DEFAULT 0 NOT NULL,
    SaleInputSeptember int DEFAULT 0 NOT NULL,
    SaleInputOctober int DEFAULT 0 NOT NULL,
    SaleInputNovember int DEFAULT 0 NOT NULL,
    SaleInputDecember int DEFAULT 0 NOT NULL,
    ForecastJanuary float DEFAULT 0 NOT NULL,
    ForecastFebruary float DEFAULT 0 NOT NULL,
    ForecastMarch float DEFAULT 0 NOT NULL,
    ForecastApril float DEFAULT 0 NOT NULL,
    ForecastMay float DEFAULT 0 NOT NULL,
    ForecastJune float DEFAULT 0 NOT NULL,
    ForecastJuly float DEFAULT 0 NOT NULL,
    ForecastAugust float DEFAULT 0 NOT NULL,
    ForecastSeptember float DEFAULT 0 NOT NULL,
    ForecastOctober float DEFAULT 0 NOT NULL,
    ForecastNovember float DEFAULT 0 NOT NULL,
    ForecastDecember float DEFAULT 0 NOT NULL,
    FactPendiente decimal(38,3) DEFAULT 0 NOT NULL,
    ContPendiente decimal(38,3) DEFAULT 0 NOT NULL,
    RealSaleJanuary int DEFAULT 0 NOT NULL,
    RealSaleFebruary int DEFAULT 0 NOT NULL,
    RealSaleMarch int DEFAULT 0 NOT NULL,
    RealSaleApril int DEFAULT 0 NOT NULL,
    RealSaleMay int DEFAULT 0 NOT NULL,
    RealSaleJune int DEFAULT 0 NOT NULL,
    RealSaleJuly int DEFAULT 0 NOT NULL,
    RealSaleAugust int DEFAULT 0 NOT NULL,
    RealSaleSeptember int DEFAULT 0 NOT NULL,
    RealSaleOctober int DEFAULT 0 NOT NULL,
    RealSaleNovember int DEFAULT 0 NOT NULL,
    RealSaleDecember int DEFAULT 0 NOT NULL,
    CyO decimal(38,2) DEFAULT 0 NOT NULL
);
CREATE CLUSTERED INDEX sis_report_CampaignId_IDX ON dbo.sis_report (  CampaignId ASC  , CountryId ASC  , DsmUserId ASC  , DsmUsuario ASC  , TamUserId ASC  , TamUsuario ASC  , GmidId ASC  , GmidDescription ASC  , Ingredient ASC  , ClientId ASC  , ClientDescription ASC  )
    WITH (  PAD_INDEX = OFF ,FILLFACTOR = 100  ,SORT_IN_TEMPDB = OFF , IGNORE_DUP_KEY = OFF , STATISTICS_NORECOMPUTE = OFF , ONLINE = OFF , ALLOW_ROW_LOCKS = ON , ALLOW_PAGE_LOCKS = ON  )
    ON [PRIMARY ] ;
DROP PROCEDURE SP_Run_Sis_Report;

CREATE PROCEDURE [SP_Run_Sis_Report]
AS
    SET NOCOUNT ON;
    IF (SELECT COUNT(1)
        FROM sis_report_metadata WHERE code = 'is_running') > 0
        BEGIN
            SELECT 'IS RUNNING';
        END
    ELSE

        BEGIN
            INSERT INTO sis_report_metadata VALUES ('is_running', CURRENT_TIMESTAMP);
            DROP TABLE IF EXISTS #forecast_gmid_total;

            DROP TABLE IF EXISTS #country_abbr;
            DROP TABLE IF EXISTS #tmp_forecast_total_by_client_and_tradeproduct;
            DROP TABLE IF EXISTS #tmp_forecast_gmid_total;
            DROP TABLE IF EXISTS #forecastibp_by_client_product;

            DROP TABLE IF EXISTS #tmp_sis_report;

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
            SELECT
                ClientProduct.GmidId AS GmidId,
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
            GROUP BY ClientProduct.GmidId, campaign.CampaignId
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
                ForecastGmidTotal.GmidId,
                Forecast.CampaignId,
                CASE WHEN (AVG(ForecastGmidTotal.January)>0) THEN CONVERT(float, COALESCE(SUM(Forecast.January), 0))*100/CONVERT(float, AVG(ForecastGmidTotal.January)) ELSE 0 END AS January,
                CASE WHEN (AVG(ForecastGmidTotal.February)>0) THEN CONVERT(float, COALESCE(SUM(Forecast.February), 0))*100/CONVERT(float, AVG(ForecastGmidTotal.February)) ELSE 0 END AS February,
                CASE WHEN (AVG(ForecastGmidTotal.March)>0) THEN CONVERT(float, COALESCE(SUM(Forecast.March), 0))*100/CONVERT(float, AVG(ForecastGmidTotal.March)) ELSE 0 END AS March,
                CASE WHEN (AVG(ForecastGmidTotal.April)>0) THEN CONVERT(float, COALESCE(SUM(Forecast.April), 0))*100/CONVERT(float, AVG(ForecastGmidTotal.April)) ELSE 0 END AS April,
                CASE WHEN (AVG(ForecastGmidTotal.May)>0) THEN CONVERT(float, COALESCE(SUM(Forecast.May), 0))*100/CONVERT(float, AVG(ForecastGmidTotal.May)) ELSE 0 END AS May,
                CASE WHEN (AVG(ForecastGmidTotal.June)>0) THEN CONVERT(float, COALESCE(SUM(Forecast.June), 0))*100/CONVERT(float, AVG(ForecastGmidTotal.June)) ELSE 0 END AS June,
                CASE WHEN (AVG(ForecastGmidTotal.July)>0) THEN CONVERT(float, COALESCE(SUM(Forecast.July), 0))*100/CONVERT(float, AVG(ForecastGmidTotal.July)) ELSE 0 END AS July,
                CASE WHEN (AVG(ForecastGmidTotal.August)>0) THEN CONVERT(float, COALESCE(SUM(Forecast.August), 0))*100/CONVERT(float, AVG(ForecastGmidTotal.August)) ELSE 0 END AS August,
                CASE WHEN (AVG(ForecastGmidTotal.September)>0) THEN CONVERT(float, COALESCE(SUM(Forecast.September), 0))*100/CONVERT(float, AVG(ForecastGmidTotal.September)) ELSE 0 END AS September,
                CASE WHEN (AVG(ForecastGmidTotal.October)>0) THEN CONVERT(float, COALESCE(SUM(Forecast.October), 0))*100/CONVERT(float, AVG(ForecastGmidTotal.October)) ELSE 0 END AS October,
                CASE WHEN (AVG(ForecastGmidTotal.November)>0) THEN CONVERT(float, COALESCE(SUM(Forecast.November), 0))*100/CONVERT(float, AVG(ForecastGmidTotal.November)) ELSE 0 END AS November,
                CASE WHEN (AVG(ForecastGmidTotal.December)>0) THEN CONVERT(float, COALESCE(SUM(Forecast.December), 0))*100/CONVERT(float, AVG(ForecastGmidTotal.December)) ELSE 0 END AS December
            INTO #tmp_forecast_total_by_client_and_tradeproduct
            FROM
                forecast AS Forecast
                    INNER JOIN client_product AS ClientProduct
                               ON ClientProduct.ClientProductId = Forecast.ClientProductId
                    INNER JOIN #tmp_forecast_gmid_total ForecastGmidTotal
                               ON ForecastGmidTotal.GmidId = ClientProduct.GmidId AND Forecast.CampaignId = ForecastGmidTotal.CampaignId
            WHERE  (ForecastGmidTotal.January!=0 OR ForecastGmidTotal.February!=0 OR ForecastGmidTotal.March!=0 OR ForecastGmidTotal.April!=0 OR ForecastGmidTotal.May!=0 OR ForecastGmidTotal.June!=0 OR ForecastGmidTotal.July!=0 OR ForecastGmidTotal.August!=0 OR ForecastGmidTotal.September!=0 OR ForecastGmidTotal.October!=0 OR ForecastGmidTotal.November!=0 OR ForecastGmidTotal.December!=0)
            GROUP BY ClientProduct.ClientId, Forecast.CampaignId, ForecastGmidTotal.GmidId
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

            SELECT ForecastTotalClientTradeProduct.GmidId AS GmidId,
                   Campaign.CampaignId,
                   ForecastTotalClientTradeProduct.ClientId,
                   Country.CountryId,
                   FCASTIBP.January * (CONVERT(float, ForecastTotalClientTradeProduct.January)/100) AS January,
                   FCASTIBP.February * (CONVERT(float, ForecastTotalClientTradeProduct.February)/100) AS February,
                   FCASTIBP.March * (CONVERT(float, ForecastTotalClientTradeProduct.March)/100) AS March,
                   FCASTIBP.April * (CONVERT(float, ForecastTotalClientTradeProduct.April)/100) AS April,
                   FCASTIBP.May * (CONVERT(float, ForecastTotalClientTradeProduct.May)/100) AS May,
                   FCASTIBP.June * (CONVERT(float, ForecastTotalClientTradeProduct.June)/100) AS June,
                   FCASTIBP.July * (CONVERT(float, ForecastTotalClientTradeProduct.July)/100) AS July,
                   FCASTIBP.August * (CONVERT(float, ForecastTotalClientTradeProduct.August)/100) AS August,
                   FCASTIBP.September * (CONVERT(float, ForecastTotalClientTradeProduct.September)/100) AS September,
                   FCASTIBP.October * (CONVERT(float, ForecastTotalClientTradeProduct.October)/100) AS October,
                   FCASTIBP.November * (CONVERT(float, ForecastTotalClientTradeProduct.November)/100) AS November,
                   FCASTIBP.December * (CONVERT(float, ForecastTotalClientTradeProduct.December)/100) AS December
            INTO #forecastibp_by_client_product
            FROM FCASTIBP
                     INNER JOIN campaign Campaign ON Campaign.Name  = FCASTIBP.Año
                     INNER JOIN gmid Gmid ON CONVERT(INT, REPLACE(FCASTIBP.OldProductID , 'D','')) = Gmid.GmidId
                     INNER JOIN #tmp_forecast_total_by_client_and_tradeproduct AS ForecastTotalClientTradeProduct ON ForecastTotalClientTradeProduct.CampaignId = Campaign.CampaignId AND Gmid.GmidId = ForecastTotalClientTradeProduct.GmidId
                     INNER JOIN #country_abbr Country ON Country.abbr = FCASTIBP.ShipToCountry
            ;

            --     DELETE FROM sis_report WHERE 1=1;
            SELECT
                Campaign.CampaignId,
                COALESCE(Client.CountryId, 1) AS CountryId,
                Dsm.UserId AS DsmUserId,
                UPPER(Dsm.Fullname) AS DsmUsuario,
                Tam.UserId AS TamUserId,
                UPPER(Tam.Fullname) AS TamUsuario,
                Gmid.GmidId,
                Gmid.Description AS GmidDescription,
                COALESCE(Ingredient.Ingredient, 'S/N') AS Ingredient,
                Client.ClientId,
                Client.Description AS ClientDescription,
                COALESCE(SaleInput.January,0) AS SaleInputJanuary,
                COALESCE(SaleInput.February,0) AS SaleInputFebruary,
                COALESCE(SaleInput.March,0) AS SaleInputMarch,
                COALESCE(SaleInput.April,0) AS SaleInputApril,
                COALESCE(SaleInput.May,0) AS SaleInputMay,
                COALESCE(SaleInput.June,0) AS SaleInputJune,
                COALESCE(SaleInput.July,0) AS SaleInputJuly,
                COALESCE(SaleInput.August,0) AS SaleInputAugust,
                COALESCE(SaleInput.September,0) AS SaleInputSeptember,
                COALESCE(SaleInput.October,0) AS SaleInputOctober,
                COALESCE(SaleInput.November,0) AS SaleInputNovember,
                COALESCE(SaleInput.December,0) AS SaleInputDecember,

                COALESCE(Forecast.January,0) AS ForecastJanuary,
                COALESCE(Forecast.February,0) AS ForecastFebruary,
                COALESCE(Forecast.March,0) AS ForecastMarch,
                COALESCE(Forecast.April,0) AS ForecastApril,
                COALESCE(Forecast.May,0) AS ForecastMay,
                COALESCE(Forecast.June,0) AS ForecastJune,
                COALESCE(Forecast.July,0) AS ForecastJuly,
                COALESCE(Forecast.August,0) AS ForecastAugust,
                COALESCE(Forecast.September,0) AS ForecastSeptember,
                COALESCE(Forecast.October,0) AS ForecastOctober,
                COALESCE(Forecast.November,0) AS ForecastNovember,
                COALESCE(Forecast.December,0) AS ForecastDecember,

                COALESCE(FactPendiente.Total,0) AS FactPendiente,
                COALESCE(ContPendiente.Total,0) AS ContPendiente,
                COALESCE(RealSale.January,0) AS RealSaleJanuary,
                COALESCE(RealSale.February,0) AS RealSaleFebruary,
                COALESCE(RealSale.March,0) AS RealSaleMarch,
                COALESCE(RealSale.April,0) AS RealSaleApril,
                COALESCE(RealSale.May,0) AS RealSaleMay,
                COALESCE(RealSale.June,0) AS RealSaleJune,
                COALESCE(RealSale.July,0) AS RealSaleJuly,
                COALESCE(RealSale.August,0) AS RealSaleAugust,
                COALESCE(RealSale.September,0) AS RealSaleSeptember,
                COALESCE(RealSale.October,0) AS RealSaleOctober,
                COALESCE(RealSale.November,0) AS RealSaleNovember,
                COALESCE(RealSale.December,0) AS RealSaleDecember,
                COALESCE(CyO.Total,0) AS CyO
            INTO #tmp_sis_report
            FROM
                [user] AS Dsm
                    INNER JOIN pm_dsm AS DsmTam ON
                        DsmTam.DsmId = Dsm.UserId
                    INNER JOIN [user] AS Tam ON
                        Tam.ParentId = DsmTam.DsmId
                    INNER JOIN client_seller AS ClientSeller ON
                        ClientSeller.SellerId = Tam.UserId
                    INNER JOIN client AS Client ON
                        ClientSeller.ClientId = Client.ClientId
                    INNER JOIN gmid AS Gmid ON
                        1 = 1
                    LEFT JOIN vw_gmid_ingredient AS Ingredient ON
                        Ingredient.GmidId = Gmid.GmidId
                    INNER JOIN campaign AS Campaign ON
                        1 = 1
                    LEFT JOIN (
                    SELECT
                        Forecast.CampaignId,
                        ClientProduct.GmidId AS GmidId,
                        ClientProduct.ClientId AS ClientId,
                        SUM(COALESCE(January, 0)) AS January,
                        SUM(COALESCE(February, 0)) AS February,
                        SUM(COALESCE(March, 0)) AS March,
                        SUM(COALESCE(April, 0)) AS April,
                        SUM(COALESCE(May, 0)) AS May,
                        SUM(COALESCE(June, 0)) AS June,
                        SUM(COALESCE(July, 0)) AS July,
                        SUM(COALESCE(August, 0)) AS August,
                        SUM(COALESCE(September, 0)) AS September,
                        SUM(COALESCE(October, 0)) AS October,
                        SUM(COALESCE(November, 0)) AS November,
                        SUM(COALESCE(December, 0)) AS December
                    FROM
                        forecast AS Forecast
                            INNER JOIN client_product AS ClientProduct ON
                                ClientProduct.ClientProductId = Forecast.ClientProductId
                    GROUP BY
                        ClientProduct.ClientId,
                        ClientProduct.GmidId,
                        Forecast.CampaignId) AS SaleInput ON
                            Gmid.GmidId = SaleInput.GmidId
                        AND SaleInput.CampaignId = Campaign.CampaignId
                        AND ClientSeller.ClientId = SaleInput.ClientId
                    LEFT JOIN (
                    SELECT
                        GmidId,
                        CountryId,
                        ClientId,
                        CampaignId,
                        January,
                        February,
                        March,
                        April,
                        May,
                        June,
                        July,
                        August,
                        September,
                        October,
                        November,
                        December
                    FROM
                        #forecastibp_by_client_product
                ) AS Forecast ON
                            Gmid.GmidId = Forecast.GmidId
                        AND Forecast.CampaignId = Campaign.CampaignId
                        AND ClientSeller.ClientId = Forecast.ClientId
                        AND Client.CountryId = Forecast.CountryId
                    LEFT JOIN (
                    SELECT
                        CASE
                            WHEN IsNumeric(REPLACE(MaterialCode, 'D', ''))= 1 THEN CONVERT(INT,
                                    REPLACE(MaterialCode, 'D', ''))
                            ELSE null
                            END AS GmidId,
                        CASE
                            WHEN IsNumeric(SoldToCustNumber)= 1 THEN CONVERT(INT,
                                    SoldToCustNumber)
                            ELSE null
                            END AS ClientId,
                        SUM((CASE WHEN SalesDocType IN ('ZRE7', 'ZRE', 'ZARE', 'ZREF') THEN 1 ELSE -1 END)* DeliveryQ) as Total
                    FROM
                        DESPNOFC
                    GROUP BY
                        CASE
                            WHEN IsNumeric(REPLACE(MaterialCode, 'D', ''))= 1 THEN CONVERT(INT,
                                    REPLACE(MaterialCode, 'D', ''))
                            ELSE null
                            END,
                        CASE
                            WHEN IsNumeric(SoldToCustNumber)= 1 THEN CONVERT(INT,
                                    SoldToCustNumber)
                            ELSE null
                            END) AS FactPendiente ON
                            Gmid.GmidId = FactPendiente.GmidId
                        AND ClientSeller.ClientId = FactPendiente.ClientId
                    LEFT JOIN (
                    SELECT
                        CASE
                            WHEN IsNumeric(REPLACE(MaterialCode, 'D', ''))= 1 THEN CONVERT(INT,
                                    REPLACE(MaterialCode, 'D', ''))
                            ELSE null
                            END AS GmidId,
                        CASE
                            WHEN IsNumeric(SoldToPartyNumber)= 1 THEN CONVERT(INT,
                                    SoldToPartyNumber)
                            ELSE null
                            END AS ClientId,
                        SUM((CASE WHEN BillingType IN ( 'ZRR', 'ZRE', 'ZARE', 'ZRD' ) THEN 1 ELSE -1 END)* BilledQ) as Total
                    FROM
                        FCNOCONT
                    GROUP BY
                        CASE
                            WHEN IsNumeric(REPLACE(MaterialCode, 'D', ''))= 1 THEN CONVERT(INT,
                                    REPLACE(MaterialCode, 'D', ''))
                            ELSE null
                            END,
                        CASE
                            WHEN IsNumeric(SoldToPartyNumber)= 1 THEN CONVERT(INT,
                                    SoldToPartyNumber)
                            ELSE null
                            END) AS ContPendiente ON
                            Gmid.GmidId = ContPendiente.GmidId
                        AND ClientSeller.ClientId = ContPendiente.ClientId
                    LEFT JOIN (
                    SELECT
                        GmidId,
                        ClientId,
                        CampaignId,
                        (SELECT SUM(COALESCE(aux.Amount,0)) FROM sale AS aux WHERE aux.GmidId = sale.GmidId AND aux.ClientId = sale.ClientId AND aux.CampaignId = sale.CampaignId AND aux.Month = 1) AS January,
                        (SELECT SUM(COALESCE(aux.Amount,0)) FROM sale AS aux WHERE aux.GmidId = sale.GmidId AND aux.ClientId = sale.ClientId AND aux.CampaignId = sale.CampaignId AND aux.Month = 2) AS February,
                        (SELECT SUM(COALESCE(aux.Amount,0)) FROM sale AS aux WHERE aux.GmidId = sale.GmidId AND aux.ClientId = sale.ClientId AND aux.CampaignId = sale.CampaignId AND aux.Month = 3) AS March,
                        (SELECT SUM(COALESCE(aux.Amount,0)) FROM sale AS aux WHERE aux.GmidId = sale.GmidId AND aux.ClientId = sale.ClientId AND aux.CampaignId = sale.CampaignId AND aux.Month = 4) AS April,
                        (SELECT SUM(COALESCE(aux.Amount,0)) FROM sale AS aux WHERE aux.GmidId = sale.GmidId AND aux.ClientId = sale.ClientId AND aux.CampaignId = sale.CampaignId AND aux.Month = 5) AS May,
                        (SELECT SUM(COALESCE(aux.Amount,0)) FROM sale AS aux WHERE aux.GmidId = sale.GmidId AND aux.ClientId = sale.ClientId AND aux.CampaignId = sale.CampaignId AND aux.Month = 6) AS June,
                        (SELECT SUM(COALESCE(aux.Amount,0)) FROM sale AS aux WHERE aux.GmidId = sale.GmidId AND aux.ClientId = sale.ClientId AND aux.CampaignId = sale.CampaignId AND aux.Month = 7) AS July,
                        (SELECT SUM(COALESCE(aux.Amount,0)) FROM sale AS aux WHERE aux.GmidId = sale.GmidId AND aux.ClientId = sale.ClientId AND aux.CampaignId = sale.CampaignId AND aux.Month = 8) AS August,
                        (SELECT SUM(COALESCE(aux.Amount,0)) FROM sale AS aux WHERE aux.GmidId = sale.GmidId AND aux.ClientId = sale.ClientId AND aux.CampaignId = sale.CampaignId AND aux.Month = 9) AS September,
                        (SELECT SUM(COALESCE(aux.Amount,0)) FROM sale AS aux WHERE aux.GmidId = sale.GmidId AND aux.ClientId = sale.ClientId AND aux.CampaignId = sale.CampaignId AND aux.Month = 10) AS October,
                        (SELECT SUM(COALESCE(aux.Amount,0)) FROM sale AS aux WHERE aux.GmidId = sale.GmidId AND aux.ClientId = sale.ClientId AND aux.CampaignId = sale.CampaignId AND aux.Month = 11) AS November,
                        (SELECT SUM(COALESCE(aux.Amount,0)) FROM sale AS aux WHERE aux.GmidId = sale.GmidId AND aux.ClientId = sale.ClientId AND aux.CampaignId = sale.CampaignId AND aux.Month = 12) AS December
                    FROM
                        sale
                    GROUP BY
                        GmidId,
                        CampaignId,
                        ClientId) AS RealSale ON
                            Gmid.GmidId = RealSale.GmidId
                        AND ClientSeller.ClientId = RealSale.ClientId
                        AND RealSale.CampaignId = Campaign.CampaignId
                    LEFT JOIN (
                    SELECT
                        GmidId,
                        ClientId,
                        CampaignId,
                        SUM(InventoryBalance) as Total
                    FROM
                        cyo
                    GROUP BY
                        GmidId,CampaignId,
                        ClientId) AS CyO ON
                            Gmid.GmidId = CyO.GmidId
                        AND ClientSeller.ClientId = CyO.ClientId
                        AND CyO.CampaignId = Campaign.CampaignId
            WHERE
                        Tam.IsActive = 1
                    and Dsm.IsActive = 1
                    AND
                        (COALESCE(SaleInput.January,0) != 0 OR COALESCE(SaleInput.February,0) != 0 OR COALESCE(SaleInput.March,0) != 0 OR COALESCE(SaleInput.April,0) != 0 OR COALESCE(SaleInput.May,0) != 0 OR COALESCE(SaleInput.June,0) != 0 OR COALESCE(SaleInput.July,0) != 0 OR COALESCE(SaleInput.August,0) != 0 OR COALESCE(SaleInput.September,0) != 0 OR COALESCE(SaleInput.October,0) != 0 OR COALESCE(SaleInput.November,0) != 0 OR COALESCE(SaleInput.December,0) != 0 OR
                    COALESCE(Forecast.January,0) != 0 OR COALESCE(Forecast.February,0) != 0 OR COALESCE(Forecast.March,0) != 0 OR COALESCE(Forecast.April,0) != 0 OR COALESCE(Forecast.May,0) != 0 OR COALESCE(Forecast.June,0) != 0 OR COALESCE(Forecast.July,0) != 0 OR COALESCE(Forecast.August,0) != 0 OR COALESCE(Forecast.September,0) != 0 OR COALESCE(Forecast.October,0) != 0 OR COALESCE(Forecast.November,0) != 0 OR COALESCE(Forecast.December,0) != 0 OR
                    COALESCE(FactPendiente.Total,0) != 0 OR COALESCE(ContPendiente.Total,0) != 0
               OR COALESCE(RealSale.January,0) != 0 OR COALESCE(RealSale.February,0) != 0 OR COALESCE(RealSale.March,0) != 0 OR COALESCE(RealSale.April,0) != 0 OR COALESCE(RealSale.May,0) != 0 OR COALESCE(RealSale.June,0) != 0 OR COALESCE(RealSale.July,0) != 0 OR COALESCE(RealSale.August,0) != 0 OR COALESCE(RealSale.September,0) != 0 OR COALESCE(RealSale.October,0) != 0 OR COALESCE(RealSale.November,0) != 0 OR COALESCE(RealSale.December,0) != 0
               OR COALESCE(CyO.Total,0) != 0)
            ;

            INSERT INTO sis_report
            SELECT * from #tmp_sis_report tmp WHERE NOT EXISTS (SELECT 1 FROM sis_report WHERE
                    tmp.CampaignId = sis_report.CampaignId AND
                    tmp.CountryId = sis_report.CountryId AND
                    tmp.DsmUserId = sis_report.DsmUserId AND
                    tmp.TamUserId = sis_report.TamUserId AND
                    tmp.GmidId = sis_report.GmidId AND
                    tmp.Ingredient = sis_report.Ingredient AND
                    tmp.ClientId = sis_report.ClientId
                );

            UPDATE sis_report SET
                                  sis_report.DsmUsuario = #tmp_sis_report.DsmUsuario,
                                  sis_report.TamUsuario = #tmp_sis_report.TamUsuario,
                                  sis_report.GmidDescription = #tmp_sis_report.GmidDescription,
                                  sis_report.ClientDescription = #tmp_sis_report.ClientDescription,
                                  sis_report.SaleInputJanuary = #tmp_sis_report.SaleInputJanuary,
                                  sis_report.SaleInputFebruary = #tmp_sis_report.SaleInputFebruary,
                                  sis_report.SaleInputMarch = #tmp_sis_report.SaleInputMarch,
                                  sis_report.SaleInputApril = #tmp_sis_report.SaleInputApril,
                                  sis_report.SaleInputMay = #tmp_sis_report.SaleInputMay,
                                  sis_report.SaleInputJune = #tmp_sis_report.SaleInputJune,
                                  sis_report.SaleInputJuly = #tmp_sis_report.SaleInputJuly,
                                  sis_report.SaleInputAugust = #tmp_sis_report.SaleInputAugust,
                                  sis_report.SaleInputSeptember = #tmp_sis_report.SaleInputSeptember,
                                  sis_report.SaleInputOctober = #tmp_sis_report.SaleInputOctober,
                                  sis_report.SaleInputNovember = #tmp_sis_report.SaleInputNovember,
                                  sis_report.SaleInputDecember = #tmp_sis_report.SaleInputDecember,
                                  sis_report.ForecastJanuary = #tmp_sis_report.ForecastJanuary,
                                  sis_report.ForecastFebruary = #tmp_sis_report.ForecastFebruary,
                                  sis_report.ForecastMarch = #tmp_sis_report.ForecastMarch,
                                  sis_report.ForecastApril = #tmp_sis_report.ForecastApril,
                                  sis_report.ForecastMay = #tmp_sis_report.ForecastMay,
                                  sis_report.ForecastJune = #tmp_sis_report.ForecastJune,
                                  sis_report.ForecastJuly = #tmp_sis_report.ForecastJuly,
                                  sis_report.ForecastAugust = #tmp_sis_report.ForecastAugust,
                                  sis_report.ForecastSeptember = #tmp_sis_report.ForecastSeptember,
                                  sis_report.ForecastOctober = #tmp_sis_report.ForecastOctober,
                                  sis_report.ForecastNovember = #tmp_sis_report.ForecastNovember,
                                  sis_report.ForecastDecember = #tmp_sis_report.ForecastDecember,
                                  sis_report.FactPendiente = #tmp_sis_report.FactPendiente,
                                  sis_report.ContPendiente = #tmp_sis_report.ContPendiente,
                                  sis_report.RealSaleJanuary = #tmp_sis_report.RealSaleJanuary,
                                  sis_report.RealSaleFebruary = #tmp_sis_report.RealSaleFebruary,
                                  sis_report.RealSaleMarch = #tmp_sis_report.RealSaleMarch,
                                  sis_report.RealSaleApril = #tmp_sis_report.RealSaleApril,
                                  sis_report.RealSaleMay = #tmp_sis_report.RealSaleMay,
                                  sis_report.RealSaleJune = #tmp_sis_report.RealSaleJune,
                                  sis_report.RealSaleJuly = #tmp_sis_report.RealSaleJuly,
                                  sis_report.RealSaleAugust = #tmp_sis_report.RealSaleAugust,
                                  sis_report.RealSaleSeptember = #tmp_sis_report.RealSaleSeptember,
                                  sis_report.RealSaleOctober = #tmp_sis_report.RealSaleOctober,
                                  sis_report.RealSaleNovember = #tmp_sis_report.RealSaleNovember,
                                  sis_report.RealSaleDecember = #tmp_sis_report.RealSaleDecember,
                                  sis_report.CyO = #tmp_sis_report.CyO
            FROM #tmp_sis_report
            WHERE
                    #tmp_sis_report.CampaignId = sis_report.CampaignId AND
                    #tmp_sis_report.CountryId = sis_report.CountryId AND
                    #tmp_sis_report.DsmUserId = sis_report.DsmUserId AND
                    #tmp_sis_report.TamUserId = sis_report.TamUserId AND
                    #tmp_sis_report.GmidId = sis_report.GmidId AND
                    #tmp_sis_report.Ingredient = sis_report.Ingredient AND
                    #tmp_sis_report.ClientId = sis_report.ClientId;
            DELETE FROM sis_report
            WHERE NOT EXISTS (SELECT 1 FROM #tmp_sis_report tmp WHERE
                    tmp.CampaignId = sis_report.CampaignId AND
                    tmp.CountryId = sis_report.CountryId AND
                    tmp.DsmUserId = sis_report.DsmUserId AND
                    tmp.TamUserId = sis_report.TamUserId AND
                    tmp.GmidId = sis_report.GmidId AND
                    tmp.Ingredient = sis_report.Ingredient AND
                    tmp.ClientId = sis_report.ClientId
                );
            UPDATE sis_report_metadata SET value = CURRENT_TIMESTAMP WHERE code = 'last_exec';
            DELETE FROM sis_report_metadata WHERE code = 'is_running';
        END
;

EXEC [SP_Run_Sis_Report];
