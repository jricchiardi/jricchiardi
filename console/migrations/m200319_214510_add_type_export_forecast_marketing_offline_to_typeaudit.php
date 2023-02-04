<?php

use yii\db\Migration;

class m200319_214510_add_type_export_forecast_marketing_offline_to_typeaudit extends Migration
{
    public function safeUp()
    {
        $this->execute("SET IDENTITY_INSERT [dbo].[type_audit] ON ;
                        INSERT INTO type_audit(TypeAuditId,Name,PublicName) 
                        VALUES (10,'Exportación Forecast Marketing Offline', 'TYPE_EXPORT_FORECAST_MARKETING_OFFLINE')
                        SET IDENTITY_INSERT [dbo].[type_audit] OFF;");
    }

    public function safeDown()
    {
        $this->execute("DELETE FROM type_audit WHERE TypeAuditId = 9");
    }
}
