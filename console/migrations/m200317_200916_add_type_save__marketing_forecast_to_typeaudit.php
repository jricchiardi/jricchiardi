<?php

use yii\db\Migration;

class m200317_200916_add_type_save__marketing_forecast_to_typeaudit extends Migration
{
    public function safeUp()
    {
        $this->execute("SET IDENTITY_INSERT [dbo].[type_audit] ON ;
                        INSERT INTO type_audit(TypeAuditId,Name,PublicName) 
                        VALUES (9,'Guardado de datos de marketing forecast', 'TYPE_SAVE_MARKETING_FORECAST')
                        SET IDENTITY_INSERT [dbo].[type_audit] OFF;");
    }

    public function safeDown()
    {
        $this->execute("DELETE FROM type_audit WHERE TypeAuditId = 9");
    }
}
