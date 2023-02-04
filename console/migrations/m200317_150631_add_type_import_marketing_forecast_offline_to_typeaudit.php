<?php

use yii\db\Migration;

class m200317_150631_add_type_import_marketing_forecast_offline_to_typeaudit extends Migration
{
    public function safeUp()
    {
        $this->execute("SET IDENTITY_INSERT [dbo].[type_audit] ON ;
                        INSERT INTO type_audit(TypeAuditId,Name,PublicName) 
                        VALUES (8,'Importación Marketing Forecast Offline', 'TYPE_IMPORT_MARKETING_FORECAST_OFFLINE')
                        SET IDENTITY_INSERT [dbo].[type_audit] OFF;");
    }

    public function safeDown()
    {
        $this->execute("DELETE FROM type_audit WHERE TypeAuditId = 8");
    }
}
