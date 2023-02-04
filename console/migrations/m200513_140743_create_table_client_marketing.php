<?php

use yii\db\Migration;

class m200513_140743_create_table_client_marketing extends Migration
{
    public function safeUp()
    {
        $this->execute("
create table client_marketing
(
    ClientMarketingId     int                            not null
        constraint PK_client_marketing
            primary key
        constraint FK_client_marketing_client_marketing
            references client_marketing,
    ClientTypeId int
        constraint FK_client_marketing_client_type
            references client_type,
    GroupId      int,
    CountryId    int
        constraint FK_client_marketing_country
            references country,
    Description  varchar(150)                   not null,
    IsGroup      bit,
    IsActive     bit
        constraint DF_client_marketing_IsActive default 1 not null
)
        ");
    }

    public function safeDown()
    {
        $this->execute("DROP TABLE client_marketing");
    }
}
