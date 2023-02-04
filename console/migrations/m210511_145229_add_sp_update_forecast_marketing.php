<?php

use yii\db\Migration;

class m210511_145229_add_sp_update_forecast_marketing extends Migration
{
    public function safeUp()
    {
        $this->execute("DROP PROCEDURE SP_Update_Forecast_Marketing");

        $this->execute("
CREATE PROCEDURE SP_Update_Forecast_Marketing @campaignId int AS
BEGIN
    SET
        NOCOUNT ON;
    MERGE forecast_marketing WITH (SERIALIZABLE) AS T
USING (SELECT tmp.DsmId,
              tmp.GmidId,
              tmp.TradeProductId,
              @campaignId,
              January,
              February,
              March,
              Q1,
              April,
              May,
              June,
              Q2,
              July,
              August,
              September,
              Q3,
              October,
              November,
              December,
              Q4,
              Total
       FROM TEMP_FORECAST_MARKETING tmp) AS U (DSMID, GMIDID,
                                                 TRADEPRODUCTID,
                                                 CAMPAIGNID, JANUARY,
                                                 FEBRUARY, MARCH, Q1,
                                                 APRIL, MAY,
                                                 JUNE, Q2, JULY,
                                                 AUGUST, SEPTEMBER,
                                                 Q3, OCTOBER,
                                                 NOVEMBER, DECEMBER,
                                                 Q4, TOTAL)
ON U.DSMID = T.DsmId AND U.CAMPAIGNID = T.CampaignId
WHEN MATCHED AND (u.GMIDID = T.GmidId AND u.TRADEPRODUCTID = T.TradeProductId) OR
                 (u.TRADEPRODUCTID = T.TradeProductId AND T.GmidId IS NULL AND U.GMIDID IS NULL) THEN
    UPDATE
    SET T.JANUARY   = U.JANUARY,
        T.FEBRUARY  = U.FEBRUARY,
        T.MARCH     = U.MARCH,
        T.Q1        = U.Q1,
        T.APRIL     = U.APRIL,
        T.MAY       = U.MAY,
        T.JUNE      = U.JUNE,
        T.Q2        = U.Q2,
        T.JULY      = U.JULY,
        T.AUGUST    = U.AUGUST,
        T.SEPTEMBER = U.SEPTEMBER,
        T.Q3        = U.Q3,
        T.OCTOBER   = U.OCTOBER,
        T.NOVEMBER  = U.NOVEMBER,
        T.DECEMBER  = U.DECEMBER,
        T.Q4        = U.Q4,
        T.TOTAL     = U.TOTAL
WHEN NOT MATCHED THEN
    INSERT (DSMID, GMIDID,
            TRADEPRODUCTID,
            CAMPAIGNID, JANUARY,
            FEBRUARY, MARCH, Q1,
            APRIL, MAY,
            JUNE, Q2, JULY,
            AUGUST, SEPTEMBER,
            Q3, OCTOBER,
            NOVEMBER, DECEMBER,
            Q4, TOTAL)
    VALUES (U.DSMID, U.GMIDID,
            U.TRADEPRODUCTID,
            U.CAMPAIGNID, U.JANUARY,
            U.FEBRUARY, U.MARCH, U.Q1,
            U.APRIL, U.MAY,
            U.JUNE, U.Q2, U.JULY,
            U.AUGUST, U.SEPTEMBER,
            U.Q3, U.OCTOBER,
            U.NOVEMBER, U.DECEMBER,
            U.Q4, U.TOTAL);
END
        ");
    }

    public function safeDown()
    {
        $this->execute("DROP PROCEDURE SP_Update_Forecast_Marketing");

        $this->execute("
CREATE PROCEDURE SP_Update_Forecast_Marketing @campaignId int AS BEGIN
SET
  NOCOUNT ON;
UPDATE forecast_marketing
SET
  January = TFM.January,
  February = TFM.February,
  March = TFM.March,
  Q1 = TFM.Q1,
  April = TFM.April,
  May = TFM.May,
  June = TFM.June,
  Q2 = TFM.Q2,
  July = TFM.July,
  August = TFM.August,
  September = TFM.September,
  Q3 = TFM.Q3,
  October = TFM.October,
  November = TFM.November,
  December = TFM.December,
  Q4 = TFM.Q4,
  Total = TFM.Total
FROM forecast_marketing
INNER JOIN TEMP_FORECAST_MARKETING TFM on forecast_marketing.ClientMarketingProductId = TFM.ClientMarketingProductId
  AND forecast_marketing.CampaignId = @campaignId
END
        ");
    }
}
