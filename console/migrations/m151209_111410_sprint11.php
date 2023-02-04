<?php

use yii\db\Migration;

class m151209_111410_sprint11 extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE [dbo].[campaign] ADD [DateBeginCampaign] [datetime] NULL;");
        
    }

    public function safeDown()
    {
        echo "m151209_111410_sprint11 cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
