<?php

use yii\db\Migration;

class m200426_175748_update_table_temp_pm_product extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE TEMP_PM_PRODUCT DROP COLUMN UserId");
        $this->execute("ALTER TABLE TEMP_PM_PRODUCT ADD Username VARCHAR (255)");
    }

    public function safeDown()
    {
        $this->execute("ALTER TABLE TEMP_PM_PRODUCT DROP COLUMN Username");
        $this->execute("ALTER TABLE TEMP_PM_PRODUCT ADD UserId INT");
    }
}
