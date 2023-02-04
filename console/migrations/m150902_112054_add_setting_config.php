<?php

use yii\db\Schema;
use yii\db\Migration;

class m150902_112054_add_setting_config extends Migration
{
    public function up()
    {
        $this->execute("SET IDENTITY_INSERT [dbo].[type_import] ON ;
                        INSERT INTO type_import(TypeImportId,Name) 
                        VALUES (7,'PLAN')
                        SET IDENTITY_INSERT [dbo].[type_import] OFF;");
    }

    public function down()
    {
        echo "m150902_112054_add_setting_config cannot be reverted.\n";

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
