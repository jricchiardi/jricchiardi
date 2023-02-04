<?php

use yii\db\Migration;

class m200513_152019_update_table_forecast_marketing extends Migration
{
    public function safeUp()
    {
        $this->execute("DROP TABLE forecast_marketing");

        $this->execute("
create table forecast_marketing
(
    ClientMarketingProductId int not null
        constraint FK_forecast_marketing_client_marketing_product
            references client_marketing_product,
    CampaignId      int not null
        constraint FK_forecast_marketing_campaign
            references campaign,
    January         int,
    February        int,
    March           int,
    Q1              int,
    April           int,
    May             int,
    June            int,
    Q2              int,
    July            int,
    August          int,
    September       int,
    Q3              int,
    October         int,
    November        int,
    December        int,
    Q4              int,
    Total           int,
    constraint PK_forecast_marketing_1
        primary key (ClientMarketingProductId, CampaignId)
)
        ");
    }

    public function safeDown()
    {
        $this->execute("DROP TABLE forecast_marketing");
        $this->execute("
create table forecast_marketing
(
    ClientProductId int not null
        constraint FK_forecast_marketing_client_product
            references client_product,
    CampaignId      int not null
        constraint FK_forecast_marketing_campaign
            references campaign,
    January         int,
    February        int,
    March           int,
    Q1              int,
    April           int,
    May             int,
    June            int,
    Q2              int,
    July            int,
    August          int,
    September       int,
    Q3              int,
    October         int,
    November        int,
    December        int,
    Q4              int,
    Total           int,
    constraint PK_forecast_marketing_1
        primary key (ClientProductId, CampaignId)
)
        ");
    }
}
