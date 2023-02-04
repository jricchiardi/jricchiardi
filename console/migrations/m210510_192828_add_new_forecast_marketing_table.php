<?php

use yii\db\Migration;

class m210510_192828_add_new_forecast_marketing_table extends Migration
{
    public function safeUp()
    {
        $this->execute("DROP TABLE forecast_marketing");

        $this->execute("
create table forecast_marketing
(
    ForecastMarketingId int identity
        constraint PK_forecast_marketing
            primary key,
    DsmId                 int not null
        constraint FK_forecast_marketing_user
            references [user],
    GmidId                int
        constraint FK_forecast_marketing_gmid
            references gmid,
    TradeProductId        int
        constraint FK_forecast_marketing_trade_product
            references trade_product,
    CampaignId            int not null
        constraint FK_forecast_marketing_campaign
            references campaign,
    January               int,
    February              int,
    March                 int,
    Q1                    int,
    April                 int,
    May                   int,
    June                  int,
    Q2                    int,
    July                  int,
    August                int,
    September             int,
    Q3                    int,
    October               int,
    November              int,
    December              int,
    Q4                    int,
    Total                 int
)
        ");
    }

    public function safeDown()
    {
        $this->execute("DROP TABLE forecast_marketing");

        $this->execute("
create table forecast_marketing
(
    ClientMarketingProductId int not null
        constraint FK_forecast_marketing_client_marketing_product
            references client_marketing_product,
    CampaignId               int not null
        constraint FK_forecast_marketing_campaign
            references campaign,
    January                  int,
    February                 int,
    March                    int,
    Q1                       int,
    April                    int,
    May                      int,
    June                     int,
    Q2                       int,
    July                     int,
    August                   int,
    September                int,
    Q3                       int,
    October                  int,
    November                 int,
    December                 int,
    Q4                       int,
    Total                    int,
    constraint PK_forecast_marketing_1
        primary key (ClientMarketingProductId, CampaignId)
)
        ");
    }
}
