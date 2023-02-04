DROP TABLE TEMP_CUSTOMER;

CREATE TABLE TEMP_CUSTOMER
(
  [Country]	VARCHAR(100) NULL,
  [Liable Customer] VARCHAR(200) NULL,
  [F3] VARCHAR(100) NULL ,
  [Clasificacion] VARCHAR(100) NULL ,
  [Field Seller] VARCHAR(100) NULL,
  [F6] VARCHAR(200) NULL,   		  
  [Mail vendedor] VARCHAR(200),	
  [DSM] VARCHAR(100) ,	
  [F9] VARCHAR(200) ,	
  [Mail DSM] VARCHAR(100) ,	
  [RSM]  VARCHAR(100) ,	
  [F12] VARCHAR(200) ,	
  [Mail RSM] VARCHAR(100),	
 );
 GO

 DROP TABLE TEMP_PRODUCT;
 
CREATE TABLE TEMP_PRODUCT
(
  [Country]	VARCHAR(10)	,
  [F2] VARCHAR(50),
  [ValueCenter]	VARCHAR(100),		
  [F4] VARCHAR(100),
  [F5] VARCHAR(50),
  [Performance Center] VARCHAR(50),		
  [F7] VARCHAR(100),
  [Trade Product] VARCHAR(50),		
  [F9] VARCHAR(150),
  [GMID] VARCHAR(100),
  [F11] VARCHAR(200),		
  [Precio] VARCHAR(50),	
  [Margen] VARCHAR(50),	
 );



DROP TABLE TEMP_SALE;
 
CREATE TABLE TEMP_SALE
(
 [Country] VARCHAR(60),
 [Liable Customer] VARCHAR(200),
 [F3] VARCHAR(50),
 [GMID] VARCHAR(100),
 [F5] VARCHAR(200),
 [Calendar month] INT,	
 [Actual] DECIMAL(10,2),
 [Total] DECIMAL(10,2),
 [Actual2] DECIMAL(10,2)
 );
 
 
 
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





 
