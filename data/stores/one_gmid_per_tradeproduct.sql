CREATE TABLE one_gmid_per_tradeproduct (
    TradeProductId int NOT NULL,
    GmidId int NULL,
    CONSTRAINT one_gmid_per_tradeproduct_PK PRIMARY KEY (TradeProductId),
    CONSTRAINT one_gmid_per_tradeproduct_FK FOREIGN KEY (GmidId) REFERENCES dow.dbo.gmid(GmidId),
    CONSTRAINT one_gmid_per_tradeproduct_FK_1 FOREIGN KEY (TradeProductId) REFERENCES dow.dbo.trade_product(TradeProductId)
);

