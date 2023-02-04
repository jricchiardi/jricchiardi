<?php

use yii\db\Migration;

class m200513_150317_add_table_temp_client_marketing extends Migration
{
    public function safeUp()
    {
        $this->execute("
create table TEMP_CLIENT_MARKETING
(
    Country           varchar(100),
    [Liable Customer] varchar(200),
    F3                varchar(100),
    Clasificacion     varchar(100)
)
        ");
    }

    public function safeDown()
    {
        $this->execute("DROP TABLE TEMP_CLIENT_MARKETING");
    }
}
