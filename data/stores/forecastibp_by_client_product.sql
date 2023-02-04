-- Drop table

-- DROP TABLE dow.dbo.forecastibp_by_client_product;

CREATE TABLE dow.dbo.forecastibp_by_client_product (
    GmidId int NOT NULL,
    CampaignId int NOT NULL,
    ClientId int NOT NULL,
    CountryId int NOT NULL,
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
    December float DEFAULT 0 NOT NULL,
    CONSTRAINT forecastibp_by_client_product_PK PRIMARY KEY (GmidId,CampaignId,ClientId,CountryId),
    CONSTRAINT forecastibp_by_client_product_FK FOREIGN KEY (GmidId) REFERENCES dow.dbo.gmid(GmidId),
    CONSTRAINT forecastibp_by_client_product_FK_1 FOREIGN KEY (CampaignId) REFERENCES dow.dbo.campaign(CampaignId),
    CONSTRAINT forecastibp_by_client_product_FK_2 FOREIGN KEY (ClientId) REFERENCES dow.dbo.client(ClientId),
    CONSTRAINT forecastibp_by_client_product_FK_3 FOREIGN KEY (CountryId) REFERENCES dow.dbo.country(CountryId)
);
