<?php

use yii\db\Migration;

class m200612_174204_update_sp_update_forecast_marketing extends Migration
{
    public function safeUp()
    {
        $this->execute("DROP PROCEDURE SP_Update_Forecast_Marketing");
        $this->execute("
CREATE PROCEDURE [dbo].[SP_Update_Forecast_Marketing] @campaignId int
AS
BEGIN
    SET NOCOUNT ON;
    UPDATE forecast_marketing
    SET January   = TFM.January,
        February  = TFM.February,
        March     = TFM.March,
        Q1        = TFM.Q1,
        April     = TFM.April,
        May       = TFM.May,
        June      = TFM.June,
        Q2        = TFM.Q2,
        July      = TFM.July,
        August    = TFM.August,
        September = TFM.September,
        Q3        = TFM.Q3,
        October   = TFM.October,
        November  = TFM.November,
        December  = TFM.December,
        Q4        = TFM.Q4,
        Total     = TFM.Total
    FROM forecast_marketing
             INNER JOIN TEMP_FORECAST_MARKETING TFM on forecast_marketing.ClientMarketingProductId = TFM.ClientMarketingProductId
        AND forecast_marketing.CampaignId = @campaignId
END
        ");
    }

    public function safeDown()
    {
        $this->execute("DROP PROCEDURE SP_Update_Forecast_Marketing");
        $this->execute("
CREATE PROCEDURE [dbo].[SP_Update_Forecast_Marketing] @campaignId int
AS
BEGIN
    SET NOCOUNT ON;
    UPDATE forecast_marketing
    SET January   = TFM.January,
        February  = TFM.February,
        March     = TFM.March,
        Q1        = TFM.Q1,
        April     = TFM.April,
        May       = TFM.May,
        June      = TFM.June,
        Q2        = TFM.Q2,
        July      = TFM.July,
        August    = TFM.August,
        September = TFM.September,
        Q3        = TFM.Q3,
        October   = TFM.October,
        November  = TFM.November,
        December  = TFM.December,
        Q4        = TFM.Q4,
        Total     = TFM.Total
    FROM forecast_marketing
             INNER JOIN TEMP_FORECAST_MARKETING TFM on forecast_marketing.ClientProductId = TFM.ClientProductId
        AND forecast_marketing.CampaignId = @campaignId
END
        ");
    }
}
