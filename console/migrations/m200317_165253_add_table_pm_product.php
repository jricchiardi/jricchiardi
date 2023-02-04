<?php

use yii\db\Migration;

class m200317_165253_add_table_pm_product extends Migration
{
    public function safeUp()
    {
        $this->execute("
create table pm_product
(
    PmProductId    int identity
        constraint PK_pm_product
            primary key,
    TradeProductId varchar(20)
        constraint FK_pm_product_trade_product_id
            references trade_product,
    GmidId         varchar(20)
        constraint FK_pm_product_gmid_id
            references gmid,
    UserId         int
        constraint FK_pm_product_user
            references [user]
)
        ");
    }

    public function safeDown()
    {
        $this->execute("DROP TABLE pm_product");
    }
}
