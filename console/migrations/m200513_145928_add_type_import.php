<?php

use yii\db\Migration;

class m200513_145928_add_type_import extends Migration
{
    public function safeUp()
    {
        $this->execute("SET IDENTITY_INSERT [dbo].[type_import] ON ;
                        INSERT INTO type_import(TypeImportId,Name) 
                        VALUES (18,'CLIENT_MARKETING')
                        SET IDENTITY_INSERT [dbo].[type_import] OFF;");
    }

    public function safeDown()
    {
        $this->execute("DELETE FROM type_import WHERE TypeImportId = 18");
    }
}
