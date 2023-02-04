<?php

use yii\db\Migration;

class m200317_142434_add_product_to_pm_as_typeimport extends Migration
{
    public function safeUp()
    {
        $this->execute("SET IDENTITY_INSERT [dbo].[type_import] ON ;
                        INSERT INTO type_import(TypeImportId,Name) 
                        VALUES (17,'ASSOCIATION_PM_PRODUCT')
                        SET IDENTITY_INSERT [dbo].[type_import] OFF;");
    }

    public function safeDown()
    {
        $this->execute("DELETE FROM type_import WHERE TypeImportId = 17");
    }
}
