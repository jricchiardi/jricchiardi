<?php

use yii\db\Migration;

class m160316_114434_fix_snapshot extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER PROCEDURE [dbo].[CreateSnapshotForecast]
                        AS
                          BEGIN TRANSACTION 

                           -- VARS STATICS
                                DECLARE @CampaignId INT = (SELECT CampaignId FROM campaign WHERE IsActual = 1);	
                                DECLARE @ActualMonth INT = ( SELECT month(getdate()))-1

                                -- INSERT NEWS PRODUCTS FROM CLIENTS WITH SALES AND FORECAST >0
                                INSERT INTO snapshot_forecast(ClientProductId,CampaignId)
                                SELECT f.ClientProductId , @CampaignId		   
                                FROM SaleWithForecast f
                                LEFT JOIN snapshot_forecast sf
                                ON f.ClientProductId = sf.ClientProductId
                                WHERE f.Total <>0 AND f.CampaignId = @CampaignId AND sf.ClientProductId IS NULL

                                -- UPDATE ACTUAL MONTH TOTALS FROM FORECAST
                                UPDATE snapshot_forecast SET [January] = CASE WHEN @ActualMonth = 1 THEN f.Total END
                                                                                        ,[February] = CASE WHEN @ActualMonth = 2 THEN f.Total END
                                                                                        ,[March] = CASE WHEN @ActualMonth = 3 THEN f.Total END
                                                                                        ,[April] = CASE WHEN @ActualMonth = 4 THEN f.Total END
                                                                                        ,[May] = CASE WHEN @ActualMonth = 5 THEN f.Total END
                                                                                        ,[June] = CASE WHEN @ActualMonth = 6 THEN f.Total END
                                                                                        ,[July] = CASE WHEN @ActualMonth = 7 THEN f.Total END
                                                                                        ,[August] = CASE WHEN @ActualMonth = 8 THEN f.Total END
                                                                                        ,[September] = CASE WHEN @ActualMonth = 9 THEN f.Total END
                                                                                        ,[October] = CASE WHEN @ActualMonth = 10 THEN f.Total END
                                                                                        ,[November] = CASE WHEN @ActualMonth = 11 THEN f.Total END
                                                                                        ,[December] = CASE WHEN @ActualMonth = 0 THEN f.Total END -- cambiado a 0 por pedido de santiago tener en cuenta que es un posible bug 
                           FROM snapshot_forecast sf
                           INNER JOIN SaleWithForecast f 
                           ON sf.ClientProductId = f.ClientProductId AND sf.CampaignId = f.CampaignId 
                           WHERE sf.CampaignId = @CampaignId AND f.Total <>0

                          COMMIT TRANSACTION");
    }

    public function safeDown()
    {
        echo "m160316_114434_fix_snapshot cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
