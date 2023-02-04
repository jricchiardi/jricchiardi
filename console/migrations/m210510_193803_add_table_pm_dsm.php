<?php

use yii\db\Migration;

class m210510_193803_add_table_pm_dsm extends Migration
{
    public function safeUp()
    {
        $this->execute("
create table pm_dsm
(
    DsmId int not null
        constraint FK_pm_client_dsm_id
            references [user],
    PmId  int not null
        constraint FK_pm_dsm_pm_id
            references [user],
    constraint PK_pm_das
        primary key (DsmId, PmId)
)
        ");
    }

    public function safeDown()
    {
        $this->execute("DROP TABLE pm_dsm");
    }
}
