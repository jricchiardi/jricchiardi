USE [dow]
GO
/****** Object:  StoredProcedure [dbo].[SP_ImportOpenOrders]    Script Date: 18/3/2022 12:06:22 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[SP_ImportOpenOrders]
AS
SET NOCOUNT ON;

/****************************************** VALIDATIONS *************************************************/
	
	CREATE TABLE #ERRORS
	(
	    OrderNo VARCHAR (20),
		MaterialCode VARCHAR(20),
		MaterialDescript VARCHAR(150),
		CAUSE VARCHAR(50)
	)

	-- VALIDATE Material Code DUPLICATES 
	INSERT #ERRORS(OrderNo, MaterialCode, MaterialDescript, CAUSE)
 	SELECT tmp.OrderNo, tmp.MaterialCode, tmp.MaterialDescript, 'MATERIAL DUPLICADO' AS Cause FROM TEMP_OPENORDERS tmp, OPENORDERS O
	where tmp.OrderNo = O.OrderNo AND tmp.MaterialCode = O.MaterialCode AND tmp.Item = O.Item AND tmp.SoldToCustNumber = O.SoldToCustNumber AND tmp.OrderQ = O.OrderQ
	GROUP BY tmp.OrderNo, tmp.MaterialCode, tmp.MaterialDescript
	HAVING COUNT(*)>1


	IF (SELECT COUNT(1) FROM #ERRORS)> 0 BEGIN 
		SELECT * FROM #ERRORS
	END	 
	ELSE BEGIN

	SELECT * FROM #ERRORS;
	

	/****************************************** OPENORDERS *************************************************/
	

	UPDATE OPENORDERS SET 
	
	CredBlock = tmp.CredBlock, 	DelivNo = tmp.DelivNo
	
	
	from temp_OPENORDERS Tmp  
    where OPENORDERS.SalesOrg = 'A131' OR OPENORDERS.SalesOrg = 'A133' OR OPENORDERS.SalesOrg = 'A127'AND OPENORDERS.OrderNo = Tmp.OrderNo AND tmp.CredBlock is not null AND tmp.DelivNo is not null


insert into OPENORDERS
SELECT * from temp_OPENORDERS Tmp
where not exists ( 
    select 1 from OPENORDERS O with (updlock) 
    where O.OrderNo = Tmp.OrderNo AND O.MaterialCode = Tmp.MaterialCode AND tmp.SalesOrg is null
)





DELETE OPENORDERS
from OPENORDERS O
where O.SalesOrg is null
  
DELETE FROM TEMP_OPENORDERS
DROP TABLE  #ERRORS
 
END
