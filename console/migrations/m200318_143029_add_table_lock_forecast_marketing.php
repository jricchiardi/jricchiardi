<?php

use yii\db\Migration;

class m200318_143029_add_table_lock_forecast_marketing extends Migration
{
    public function safeUp()
    {
        $this->execute("
create table lock_forecast_marketing
(
    LockId   int identity
        constraint PK_lock_forecast_marketing
            primary key,
    DateFrom datetime not null,
    DateTo   datetime not null
)                        
        ");
    }

    public function safeDown()
    {
        $this->execute("DROP TABLE lock_forecast_marketing");
    }
}
