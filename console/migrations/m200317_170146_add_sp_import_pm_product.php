<?php

use yii\db\Migration;

class m200317_170146_add_sp_import_pm_product extends Migration
{
    public function safeUp()
    {
        $this->execute("
CREATE PROCEDURE [dbo].[SP_Import_PM_Product]
AS
    SET NOCOUNT ON;

    -- VALIDATIONS SALES
    CREATE TABLE #ERRORS
    (
        GMID         VARCHAR(20),
        TRADEPRODUCT VARCHAR(20),
        USERID       VARCHAR(20),
        DESCRIPTION  VARCHAR(150),
        [MONTH]      INT,
        CLIENT       INT,
        CAUSE        VARCHAR(50)
    )

INSERT #ERRORS(USERID, CAUSE)
SELECT tpp.UserId, 'USERID INEXISTENTE'
FROM TEMP_PM_PRODUCT tpp
WHERE NOT EXISTS(SELECT 1 FROM [user] u WHERE u.UserId = tpp.UserId)
GROUP BY tpp.UserId
ORDER BY tpp.UserId

INSERT #ERRORS(TRADEPRODUCT, CAUSE)
SELECT tpp.TradeProductId, 'TRADEPRODUCT INEXISTENTE'
FROM TEMP_PM_PRODUCT tpp
WHERE NOT EXISTS(SELECT 1 FROM trade_product tp WHERE tp.TradeProductId = tpp.TradeProductId)
GROUP BY tpp.TradeProductId
ORDER BY tpp.TradeProductId

INSERT #ERRORS(GMID, CAUSE)
SELECT tpp.GmidId, 'GMID INEXISTENTE'
FROM TEMP_PM_PRODUCT tpp
WHERE NOT EXISTS(SELECT 1 FROM gmid g WHERE g.GmidId = tpp.GmidId)
GROUP BY tpp.GmidId
ORDER BY tpp.GmidId

INSERT #ERRORS(GMID, TRADEPRODUCT, CAUSE)
SELECT tpp.GmidId, tpp.TradeProductId, 'CLIENTPRODUCT INEXISTENTE'
FROM TEMP_PM_PRODUCT tpp
WHERE NOT EXISTS(SELECT 1 FROM client_product cp WHERE cp.TradeProductId = tpp.TradeProductId AND cp.GmidId = tpp.GmidId)
GROUP BY tpp.GmidId, tpp.TradeProductId
ORDER BY tpp.TradeProductId
    IF (SELECT COUNT(1)
        FROM #ERRORS) > 0
        BEGIN
            SELECT * FROM #ERRORS;
        END
    ELSE
        BEGIN
            SELECT * FROM #ERRORS;

            SELECT cp.ClientProductId, tpp.UserId
            INTO #associations
            FROM client_product cp
                     INNER JOIN TEMP_PM_PRODUCT tpp on cp.GmidId = tpp.GmidId AND cp.TradeProductId = tpp.TradeProductId;

            DELETE
            FROM pm_product;

            INSERT INTO pm_product(TradeProductId, GmidId, UserId)
            SELECT TradeProductId, GmidId, UserId
            FROM TEMP_PM_PRODUCT;
        END;
DELETE
FROM TEMP_PM_PRODUCT;
        ");
    }

    public function safeDown()
    {
        $this->execute("DROP PROCEDURE SP_Import_PM_Product");
    }
}
