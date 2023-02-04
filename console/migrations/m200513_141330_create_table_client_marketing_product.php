<?php

use yii\db\Migration;

class m200513_141330_create_table_client_marketing_product extends Migration
{
    public function safeUp()
    {
        $this->execute("
create table client_marketing_product
(
    ClientMarketingProductId int identity
        constraint PK_client_marketing_product
            primary key,
    GmidId          varchar(20)
        constraint FK_client_marketing_product_gmid
            references gmid,
    TradeProductId  varchar(20)
        constraint FK_client_marketing_product_trade_product
            references trade_product,
    ClientMarketingId        int
        constraint FK_client_marketing_product_client
            references client_marketing,
    IsForecastable  bit
)
        ");
    }

    public function safeDown()
    {
        $this->execute("DROP TABLE client_marketing_product");
    }
}
