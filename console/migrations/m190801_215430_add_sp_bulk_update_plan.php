<?php

use yii\db\Migration;

class m190801_215430_add_sp_bulk_update_plan extends Migration
{
    public function safeUp()
    {
        $sql = "
        CREATE PROCEDURE [dbo].[BulkUpdatePlan]
    @campaignId int
AS
BEGIN

    SET NOCOUNT ON;

    UPDATE dbo.[plan]
    SET
    dbo.[plan].January = tvp.January,
    dbo.[plan].February = tvp.February,
    dbo.[plan].March = tvp.March,
    dbo.[plan].Q1 = tvp.Q1,
    dbo.[plan].April = tvp.April,
    dbo.[plan].May = tvp.May,
    dbo.[plan].June = tvp.June,
    dbo.[plan].Q2 = tvp.Q2,
    dbo.[plan].July = tvp.July,
    dbo.[plan].August = tvp.August,
    dbo.[plan].September = tvp.September,
    dbo.[plan].Q3 = tvp.Q3,
    dbo.[plan].October = tvp.October,
    dbo.[plan].November = tvp.November,
    dbo.[plan].December = tvp.December,
    dbo.[plan].Q4 = tvp.Q4,
    dbo.[plan].Total = tvp.Total
    FROM
    dbo.[plan]
    INNER JOIN
--     @tvpPlans AS tvp
    dbo.[TEMP_PLAN] as tvp
    ON
    dbo.[plan].ClientProductId = tvp.ClientProductId
    AND
    dbo.[plan].CampaignId = @campaignId
END
go
        ";

        $this->execute($sql);
    }

    public function safeDown()
    {
        echo "m190801_215430_add_sp_bulk_update_plan cannot be reverted.\n";

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
