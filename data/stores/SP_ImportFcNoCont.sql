USE [dow]
GO
/****** Object:  StoredProcedure [dbo].[SP_ImportFcNoCont]    Script Date: 18/3/2022 12:04:21 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[SP_ImportFcNoCont]
AS
SET NOCOUNT ON;

/****************************************** VALIDATIONS *************************************************/
	
	CREATE TABLE #ERRORS
	(
	    BillingNo VARCHAR(20),
		CAUSE VARCHAR(50)
	)

	-- VALIDATE Billing Doc DUPLICATES 
	INSERT #ERRORS(BillingNo,CAUSE)
 	SELECT tmp.BillingNo, 'Documento Duplicado' AS Cause FROM TEMP_FCNOCONT tmp, FCNOCONT nn
	where tmp.BillingNo = nn.BillingNo AND tmp.BilledQ = nn.BilledQ
	GROUP BY tmp.BillingNo
	HAVING COUNT(*)>1


	IF (SELECT COUNT(1) FROM #ERRORS)> 0 BEGIN 
		SELECT * FROM #ERRORS
	END	 
	ELSE BEGIN

	SELECT * FROM #ERRORS;	

/******************************************** FCNOCONT ***************************************************/
SET ANSI_NULLS ON
SET QUOTED_IDENTIFIER ON

UPDATE  FCNOCONT SET SalesOrg = tmp.SalesOrg, BillingType = tmp.BillingType, SoldToPartyNumber = tmp.SoldToPartyNumber, 
SoldToPartyName = tmp.SoldToPartyName, BillingDate = tmp.BillingDate from TEMP_FCNOCONT Tmp, FCNOCONT nn
where nn.BillingNo = Tmp.BillingNo AND nn.SalesOrg is null


/*UPDATE  FCNOCONT SET Item = tmp.Item, MaterialCode = tmp.MaterialCode, MaterialDescript = tmp.MaterialDescript, 
BilledQ = tmp.BilledQ, BaseUoM = tmp.BaseUoM from TEMP_FCNOCONT Tmp, FCNOCONT nn
where nn.BillingNo = Tmp.BillingNo AND nn.Item is null*/


insert into FCNOCONT
SELECT * from TEMP_FCNOCONT Tmp
where not exists ( 
    select 1 from FCNOCONT O with (updlock) 
    where O.BillingNo = Tmp.BillingNo OR O.BillingNo <> Tmp.BillingNo AND Tmp.SalesOrg is not null
)

  
DELETE FROM TEMP_FCNOCONT

DROP TABLE  #ERRORS
 
END
