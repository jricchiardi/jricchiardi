<?php

use yii\db\Migration;

class m200513_193614_update_table_pm_client extends Migration
{
    public function safeUp()
    {
        $this->execute("DROP TABLE pm_client");
        $this->execute("
create table pm_client
(
    ClientId int not null
        constraint FK_pm_client_client_marketing
            references client_marketing,
    UserId   int not null
        constraint FK_pm_client_user
            references [user],
    constraint PK_client_pm
        primary key (ClientId, UserId)
)       
        ");
    }

    public function safeDown()
    {
        $this->execute("DROP TABLE pm_client");
        $this->execute("
create table pm_client
(
    ClientId int not null
        constraint FK_pm_client_client
            references client,
    UserId   int not null
        constraint FK_pm_client_user
            references [user],
    constraint PK_client_pm
        primary key (ClientId, UserId)
)
        ");
    }
}
