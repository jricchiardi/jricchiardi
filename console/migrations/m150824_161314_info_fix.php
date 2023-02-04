<?php

use yii\db\Schema;
use yii\db\Migration;

class m150824_161314_info_fix extends Migration {

    public function up() {
        $this->execute("INSERT [dbo].[type_import] ([Name]) VALUES ( N'PRODUCT')
                        INSERT [dbo].[type_import] ([Name]) VALUES ( N'CLIENT')
                        INSERT [dbo].[type_import] ([Name]) VALUES ( N'SALE')
                        INSERT [dbo].[type_import] ([Name]) VALUES ( N'FORECAST')
                        INSERT [dbo].[type_import] ([Name]) VALUES ( N'SETTING')                                             
                        ");

        $this->execute("INSERT [dbo].[notification_status] ( [Name]) VALUES ( N'Pendiente')
                        INSERT [dbo].[notification_status] ( [Name]) VALUES ( N'Finalizado')");

        $this->execute("INSERT [dbo].[campaign] ( [Name],[IsFuture], [IsActual], [IsActive]) VALUES ( N'2015',0, 1, 1)                        
                        ");

        $this->execute("INSERT [dbo].[auth_item] ([name], [type], [description], [rule_name], [data], [created_at], [updated_at]) VALUES (N'admin', 4, NULL, NULL, NULL, NULL, NULL)
                        INSERT [dbo].[auth_item] ([name], [type], [description], [rule_name], [data], [created_at], [updated_at]) VALUES (N'DSM', 1, NULL, NULL, NULL, NULL, NULL)
                        INSERT [dbo].[auth_item] ([name], [type], [description], [rule_name], [data], [created_at], [updated_at]) VALUES (N'RSM', 2, NULL, NULL, NULL, NULL, NULL)
                        INSERT [dbo].[auth_item] ([name], [type], [description], [rule_name], [data], [created_at], [updated_at]) VALUES (N'SELLER', 3, NULL, NULL, NULL, NULL, NULL)
                        INSERT [dbo].[auth_item] ([name], [type], [description], [rule_name], [data], [created_at], [updated_at]) VALUES (N'Director Comercial',5, NULL, NULL, NULL, NULL, NULL)                        
                        ");
        
        $this->execute("INSERT INTO [dbo].[user]
           (
            [DowUserId]
           ,[Username]
           ,[Fullname]
           ,[AuthKey]
           ,[PasswordHash]
           ,[PasswordResetToken]
           ,[Email]
           ,[ParentId]
           ,[CreatedAt]
           ,[UpdatedAt]
           ,[resetPassword]
           ,[IsActive])
     VALUES
           (NULL
           ,'admin'
           ,'Administrador'
           ,NULL
           ,'1c63129ae9db9c60c3e8aa94d3e00495'
           ,NULL
           ,'admin@dow.com'
           ,NULL
           ,NULL
           ,NULL
           ,0
           ,1)
");
        
        $this->execute("SET IDENTITY_INSERT [dbo].[type_audit] ON 
                        INSERT [dbo].[type_audit] ([TypeAuditId], [Name], [PublicName]) VALUES (1, N'Login', N'TYPE_LOGIN')
                        INSERT [dbo].[type_audit] ([TypeAuditId], [Name], [PublicName]) VALUES (2, N'Guardado de datos de forecast', N'TYPE_SAVE_FORECAST')
                        INSERT [dbo].[type_audit] ([TypeAuditId], [Name], [PublicName]) VALUES (3, N'Guardado de datos de plan', N'TYPE_SAVE_PLAN')
                        SET IDENTITY_INSERT [dbo].[type_audit] OFF
                       ");
    }

    public function down() {
        echo "m150824_161314_info_fix cannot be reverted.\n";

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
