
 
DROP TABLE TEMP_OPENORDERS;
 
CREATE TABLE TEMP_OPENORDERS
(
 [SalesOrg] VARCHAR(10),
 [Item] INT,
 [OrderNo] VARCHAR (50),
 [DelivNo] VARCHAR(50),
 [CredBlock] VARCHAR(50),
 [OrderType] VARCHAR (10) ,	
 [SoldToCustNumber] VARCHAR (50),
 [SoldToCustName] VARCHAR (50),
 [MaterialCode] VARCHAR (50),
 [MaterialDescript] VARCHAR (50),  
 [PlantCode] VARCHAR (10), 
 [OpenQConfirmedQ] DECIMAL (10,3), 
 [OrderQ] DECIMAL (10,3),
 [SalesUoM] VARCHAR (10),
 [ConfirmedDelvDate] VARCHAR (50),
 [ShipToCustNumber] VARCHAR (50), 
 [ShipToCustName] VARCHAR (50), 
 [CustPurchaseOrdNo] VARCHAR(50),
 [ConfirmedShipDate] VARCHAR (50),
 );
 
  
DROP TABLE TEMP_FCNOCONT;
 
CREATE TABLE TEMP_FCNOCONT
(
 [SalesOrg] VARCHAR(10), 
 [BillingNo] INT,
 [BillingType] VARCHAR (10) ,	
 [SoldToPartyNumber] VARCHAR (50),
 [SoldToPartyName] VARCHAR (50),
 [Item] INT,
 [MaterialCode] VARCHAR (50),
 [MaterialDescript] VARCHAR (50),  
 [BilledQ] DECIMAL (10,3), 
 [BaseUoM] VARCHAR (10),
 [BillingDate] VARCHAR (50),
 );
 
 DROP TABLE TEMP_DESPNOFC;
 
CREATE TABLE TEMP_DESPNOFC
(
 [SalesDoc] VARCHAR(50), 
 [SalesItem] INT,
 [SalesDocType] VARCHAR (10) ,	
 [SoldToCustNumber] VARCHAR (50),
 [SoldToCustName] VARCHAR (50), 
 [MaterialCode] VARCHAR (50),
 [MaterialDescript] VARCHAR (50),  
 [DeliveryQ] DECIMAL (10,3), 
 [SalesUoM] VARCHAR (10), 
 );

DROP TABLE TEMP_FCASTIBP

CREATE TABLE TEMP_FCASTIBP
(
 [ShipToCountry] VARCHAR(10),
 [Portfolio] VARCHAR(50),
 [Ingredient] VARCHAR(50),
 [OldProductID] VARCHAR(50),
 [ProductDesc] VARCHAR(50),
 [KeyFigure] VARCHAR(50),
 [January] INT,
 [February] INT,
 [March] INT,
 [April] INT,
 [May] INT,
 [June] INT,
 [July] INT,
 [August] INT,
 [September] INT,
 [October] INT,
 [November] INT,
 [December] INT,
 [TotalYear] INT,
 [Año] VARCHAR(10),
 
 
 );
 
 
 
DROP TABLE OPENORDERS;
 
CREATE TABLE OPENORDERS
(
 [SalesOrg] VARCHAR(10),
 [Item] INT,
 [OrderNo] VARCHAR (50),
 [DelivNo] VARCHAR(50),
 [CredBlock] VARCHAR(50),
 [OrderType] VARCHAR (10) ,	
 [SoldToCustNumber] VARCHAR (50),
 [SoldToCustName] VARCHAR (50),
 [MaterialCode] VARCHAR (50),
 [MaterialDescript] VARCHAR (50),  
 [PlantCode] VARCHAR (10), 
 [OpenQConfirmedQ] DECIMAL (10,3), 
 [OrderQ] DECIMAL (10,3),
 [SalesUoM] VARCHAR (10),
 [ConfirmedDelvDate] VARCHAR (50),
 [ShipToCustNumber] VARCHAR (50), 
 [ShipToCustName] VARCHAR (50), 
 [CustPurchaseOrdNo] VARCHAR(50),
 [ConfirmedShipDate] VARCHAR (50),
 );
 
  
DROP TABLE FCNOCONT;
 
CREATE TABLE FCNOCONT
(
 [SalesOrg] VARCHAR(10), 
 [BillingNo] INT,
 [BillingType] VARCHAR (10) ,	
 [SoldToPartyNumber] VARCHAR (50),
 [SoldToPartyName] VARCHAR (50),
 [Item] INT,
 [MaterialCode] VARCHAR (50),
 [MaterialDescript] VARCHAR (50),  
 [BilledQ] DECIMAL (10,3), 
 [BaseUoM] VARCHAR (10),
 [BillingDate] VARCHAR (50),
 );
 
 DROP TABLE DESPNOFC;
 
CREATE TABLE DESPNOFC
(
 [SalesDoc] VARCHAR(50), 
 [SalesItem] INT,
 [SalesDocType] VARCHAR (10) ,	
 [SoldToCustNumber] VARCHAR (50),
 [SoldToCustName] VARCHAR (50), 
 [MaterialCode] VARCHAR (50),
 [MaterialDescript] VARCHAR (50),  
 [DeliveryQ] DECIMAL (10,3), 
 [SalesUoM] VARCHAR (10), 
 );

DROP TABLE FCASTIBP

CREATE TABLE FCASTIBP
(
 [ShipToCountry] VARCHAR(10),
 [Portfolio] VARCHAR(50),
 [Ingredient] VARCHAR(50),
 [OldProductID] VARCHAR(50),
 [ProductDesc] VARCHAR(50),
 [KeyFigure] VARCHAR(50),
 [January] INT,
 [February] INT,
 [March] INT,
 [April] INT,
 [May] INT,
 [June] INT,
 [July] INT,
 [August] INT,
 [September] INT,
 [October] INT,
 [November] INT,
 [December] INT,
 [TotalYear] INT,
 [Año] VARCHAR(10),
 
 
 );





 






 
