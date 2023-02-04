USE [dow]
GO
/****** Object:  StoredProcedure [dbo].[SP_ImportFcastIBP]    Script Date: 11/2/2022 12:02:18 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[SP_ImportFcastIBP]
AS
SET NOCOUNT ON;


	/****************************************** VALIDATIONS *************************************************/
	
	CREATE TABLE #ERRORS
	(
	    OldProductID VARCHAR(20),
		ProductDesc VARCHAR(150),
		CAUSE VARCHAR(50)
	)

	-- VALIDATE Material Code DUPLICATES 
	INSERT #ERRORS(OldProductID,ProductDesc,CAUSE)
 	SELECT tmp.OldProductID, tmp.ProductDesc, 'MATERIAL DUPLICADO' AS Cause FROM TEMP_FCASTIBP tmp, FCASTIBP ff
	where tmp.OldProductID = ff.OldProductID AND tmp.Año = ff.Año
	GROUP BY tmp.OldProductID, tmp.ProductDesc
	HAVING COUNT(*)>1


	IF (SELECT COUNT(1) FROM #ERRORS)> 0 BEGIN 
		SELECT * FROM #ERRORS
	END	 
	ELSE BEGIN

	SELECT * FROM #ERRORS;	

/******************************************** FCASTIBP ***************************************************/

SET ANSI_NULLS ON
SET QUOTED_IDENTIFIER ON




	UPDATE FCASTIBP SET 	
	ShipToCountry = tmp.ShipToCountry, OldProductID = tmp.OldProductID,
	ProductDesc = tmp.ProductDesc, KeyFigure = tmp.KeyFigure, January = tmp.January, February = tmp.February, March = tmp.March, April = tmp.April, May = tmp.May, June = tmp.June,
	July = tmp.July, August = tmp.August, September = tmp.September, October = tmp.October, November = tmp.November, December = tmp.December, TotalYear = tmp.TotalYear, Año = tmp.Año	
	FROM TEMP_FCASTIBP Tmp 
    where FCASTIBP.ShipToCountry = tmp.ShipToCountry AND FCASTIBP.OldProductID = Tmp.OldProductID AND FCASTIBP.TotalYear <> tmp.TotalYear AND FCASTIBP.Año = Tmp.Año

	INSERT INTO FCASTIBP
	SELECT * from TEMP_FCASTIBP Tmp
	WHERE 1 NOT IN (SELECT 1 FROM FCASTIBP as Aux WHERE Aux.OldProductID <> Tmp.OldProductID OR Aux.ShipToCountry = Tmp.ShipToCountry AND Aux.OldProductID <> Tmp.OldProductID OR Aux.Año <> Tmp.Año
	OR Aux.ShipToCountry <> Tmp.ShipToCountry AND Aux.OldProductID = Tmp.OldProductID);

	
	
	DELETE FROM TEMP_FCASTIBP
	DROP TABLE  #ERRORS	
	END