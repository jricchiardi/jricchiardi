<?php

use yii\db\Migration;

class m200612_173627_update_table_temp_forecast_marketing extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE TEMP_FORECAST_MARKETING DROP COLUMN ClientProductId");
        $this->execute("ALTER TABLE TEMP_FORECAST_MARKETING ADD ClientMarketingProductId int");
    }

    public function safeDown()
    {
        $this->execute("ALTER TABLE TEMP_FORECAST_MARKETING DROP COLUMN ClientMarketingProductId");
        $this->execute("ALTER TABLE TEMP_FORECAST_MARKETING ADD ClientProductId INT");
    }
}
