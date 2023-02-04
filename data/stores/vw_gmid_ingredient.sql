CREATE VIEW vw_gmid_ingredient AS
SELECT Ingredient, CONVERT(INT, REPLACE(OldProductId, 'D','')) AS  GmidId
FROM FCASTIBP
GROUP BY Ingredient, OldProductId;