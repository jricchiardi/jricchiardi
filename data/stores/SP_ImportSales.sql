
-- ADD SERVER TEMPORALY FOR READ ALL ROWS

/***************************************** CLOSE SERVERS AND CONECTIONS *******************************************/


ALTER PROCEDURE SP_ImportSales

AS
	 SET NOCOUNT ON;
	
	-- VALIDATIONS SALES	
	CREATE TABLE #ERRORS
	(
		GMID VARCHAR(20),
		DESCRIPTION VARCHAR(150),
		[MONTH] INT ,
		CLIENT INT ,
		CAUSE VARCHAR(50)
	)

	INSERT #ERRORS(GMID,DESCRIPTION,[MONTH],CLIENT,CAUSE)
	SELECT  GMID,
			F5, 
			[Calendar month], 
			F3,
			'HAY VENTA DUPLICADA'
	FROM TEMP_SALE
	GROUP BY F3,F5,GMID,[Calendar month]
	HAVING COUNT(*) > 1;


	IF (SELECT COUNT(1) FROM #ERRORS)>0 BEGIN 
		SELECT * FROM #ERRORS;
	END	 
	ELSE BEGIN
	
	SELECT * FROM #ERRORS;	
	DECLARE @ActualCampaignId INT
	SET @ActualCampaignId = (SELECT TOP 1 CampaignId FROM campaign WHERE IsActual = 1)
		
	DELETE FROM sale WHERE CampaignId = @ActualCampaignId;
	
	INSERT INTO sale(ClientId,GmidId,Month,Amount,Total,CampaignId)
	SELECT F3,GMID,[Calendar month],Actual,Actual*Actual2,@ActualCampaignId
	FROM TEMP_SALE


	INSERT INTO sale(ClientId,GmidId,Month,Amount,Total,CampaignId)
	SELECT GroupId,
	   GmidId,
	   Month,
	   SUM(Amount) AS Amount,
	   (SUM(Total) + (SELECT isnull(SUM(sa.Total),0)
					  FROM sale sa
					  WHERE	sa.ClientId = c.GroupId)) AS Total,
		@ActualCampaignId
	FROM sale s
	INNER JOIN client c 
	ON c.ClientId = s.ClientId
	WHERE c.GroupId IS NOT NULL
	GROUP BY c.GroupId,s.GmidId,s.Month

	END;
	
	DELETE FROM TEMP_SALE;
GO


EXEC SP_ImportSales;


	
