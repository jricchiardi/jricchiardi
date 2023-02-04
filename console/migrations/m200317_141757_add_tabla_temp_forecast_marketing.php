<?php

use yii\db\Migration;

class m200317_141757_add_tabla_temp_forecast_marketing extends Migration
{
    public function safeUp()
    {
        $this->execute("
        CREATE TABLE [dbo].[TEMP_FORECAST_MARKETING]
        (
            ClientProductId   int,
            NameClient        varchar(150),
            ValueCenter       varchar(150),
            PerformanceCenter varchar(150),
            Description       varchar(150),
            January           int,
            February          int,
            March             int,
            Q1                int,
            April             int,
            May               int,
            June              int,
            Q2                int,
            July              int,
            August            int,
            September         int,
            Q3                int,
            October           int,
            November          int,
            December          int,
            Q4                int,
            Total             int
        )");
    }

    public function safeDown()
    {
        $this->execute("DROP TABLE [dbo].[TEMP_FORECAST_MARKETING]");
    }
}
