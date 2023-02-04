USE [dow]
GO
/****** Object:  StoredProcedure [dbo].[SP_ImportDespNoFc]    Script Date: 18/3/2022 11:58:11 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

ALTER PROCEDURE [dbo].[SP_ImportDespNoFc]
AS
	SET NOCOUNT ON;


	/****************************************** VALIDATIONS *************************************************/
	
	CREATE TABLE #ERRORS
	(
	    MaterialCode VARCHAR(20),
		MaterialDescript VARCHAR(150),
		CAUSE VARCHAR(50)
	)

	-- VALIDATE Material Code DUPLICATES 
	INSERT #ERRORS(MaterialCode,MaterialDescript,CAUSE)
 	SELECT tmp.MaterialCode, tmp.MaterialDescript, 'MATERIAL DUPLICADO' AS Cause FROM TEMP_DESPNOFC tmp, DESPNOFC ff
	where tmp.SalesDoc = ff.SalesDoc AND tmp.SoldToCustName = ff.SoldToCustName
	GROUP BY tmp.MaterialCode, tmp.MaterialDescript
	HAVING COUNT(*)>1


	IF (SELECT COUNT(1) FROM #ERRORS)> 0 BEGIN 
		SELECT * FROM #ERRORS
	END	 
	ELSE BEGIN

	SELECT * FROM #ERRORS;	

/******************************************** DESPNOFC ***************************************************/

SET ANSI_NULLS ON
SET QUOTED_IDENTIFIER ON




	UPDATE DESPNOFC SET 	
	SalesDoc = tmp.SalesDoc, SalesItem = tmp.SalesItem, SalesDocType = tmp.SalesDocType, SoldToCustNumber = tmp.SoldToCustNumber,
	SoldToCustName = tmp.SoldToCustName, MaterialCode = tmp.MaterialCode, MaterialDescript = tmp.MaterialDescript, DeliveryQ = tmp.DeliveryQ,
	SalesUoM = tmp.SalesUoM	
	FROM TEMP_DESPNOFC Tmp --where exists (select 1 from DESPNOFC O with (updlock) 
    where DESPNOFC.SalesDoc = tmp.SalesDoc AND DESPNOFC.SoldToCustNumber = Tmp.SoldToCustNumber AND DESPNOFC.MaterialCode = tmp.MaterialCode

	INSERT INTO DESPNOFC
	SELECT * from TEMP_DESPNOFC Tmp
	where not exists ( 
    select 1 from DESPNOFC O with (updlock) 
    where O.SalesDoc = Tmp.SalesDoc AND O.SoldToCustNumber = Tmp.SoldToCustNumber AND O.MaterialCode = Tmp.MaterialCode)

	UPDATE DESPNOFC SET SalesDocType = OA.OrderType
	FROM OPENORDERS OA  
    where DESPNOFC.SalesDoc = OA.OrderNo AND OA.SalesOrg = 'F81C' --DESPNOFC.SalesDocType is null
	
	
	DELETE FROM TEMP_DESPNOFC
	DROP TABLE  #ERRORS
 
END

