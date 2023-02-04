<?php

use yii\db\Migration;

class m200317_142405_add_forecast_marketing_as_typeimport extends Migration
{
    public function safeUp()
    {
        $this->execute("SET IDENTITY_INSERT [dbo].[type_import] ON ;
                        INSERT INTO type_import(TypeImportId,Name) 
                        VALUES (16,'FORECAST_MARKETING')
                        SET IDENTITY_INSERT [dbo].[type_import] OFF;");
    }

    public function safeDown()
    {
        $this->execute("DELETE FROM type_import WHERE TypeImportId = 16");
    }
}
