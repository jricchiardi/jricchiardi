<?php

use yii\db\Migration;

class m200317_170123_add_table_temp_pm_product extends Migration
{
    public function safeUp()
    {
        $this->execute("
create table TEMP_PM_PRODUCT
(
    GmidId         int,
    TradeProductId int,
    UserId         int
)
        ");
    }

    public function safeDown()
    {
        $this->execute("DROP TABLE TEMP_PM_PRODUCT");
    }
}
