<?php

use yii\db\Schema;
use yii\db\Migration;

class m150824_155030_tables_temporally extends Migration {

    public function up() {
        
        $this->execute("CREATE TABLE [dbo].[TEMP_CUSTOMER](
	[Country] [varchar](100) NULL,
	[Liable Customer] [varchar](200) NULL,
	[F3] [varchar](100) NULL,
	[Clasificacion] [varchar](100) NULL,
	[Field Seller] [varchar](100) NULL,
	[F6] [varchar](200) NULL,
	[Mail vendedor] [varchar](200) NULL,
	[DSM] [varchar](100) NULL,
	[F9] [varchar](200) NULL,
	[Mail DSM] [varchar](100) NULL,
	[RSM] [varchar](100) NULL,
	[F12] [varchar](200) NULL,
	[Mail RSM] [varchar](100) NULL
        );");
        
        $this->execute("CREATE TABLE [dbo].[TEMP_PRODUCT](
	[Country] [varchar](10) NULL,
	[F2] [varchar](50) NULL,
	[ValueCenter] [varchar](100) NULL,
	[F4] [varchar](50) NULL,
	[Performance Center] [varchar](50) NULL,
	[F7] [varchar](100) NULL,
	[Trade Product] [varchar](50) NULL,
	[F9] [varchar](150) NULL,
	[GMID] [varchar](100) NULL,
	[F11] [varchar](200) NULL,
	[Precio] [varchar](50) NULL,
	[Margen] [varchar](50) NULL
        );");
        
        
        $this->execute("CREATE TABLE [dbo].[TEMP_SALE](
	[Country] [varchar](60) NULL,
	[Liable Customer] [varchar](200) NULL,
	[F3] [varchar](50) NULL,
	[GMID] [varchar](100) NULL,
	[F5] [varchar](200) NULL,
	[Calendar month] [int] NULL,
	[Actual] [decimal](10, 2) NULL,
	[Total] [decimal](10, 2) NULL,
	[Actual2] [decimal](10, 2) NULL
        );");
        
        $this->execute("CREATE TABLE [dbo].[TEMP_FORECAST](
	[ClientProductId] [int] NULL,
	[NameClient] [varchar](150) NULL,
	[ValueCenter] [varchar](150) NULL,
	[PerformanceCenter] [varchar](150) NULL,
	[Description] [varchar](150) NULL,
	[January] [int] NULL,
	[February] [int] NULL,
	[March] [int] NULL,
	[Q1] [int] NULL,
	[April] [int] NULL,
	[May] [int] NULL,
	[June] [int] NULL,
	[Q2] [int] NULL,
	[July] [int] NULL,
	[August] [int] NULL,
	[September] [int] NULL,
	[Q3] [int] NULL,
	[October] [int] NULL,
	[November] [int] NULL,
	[December] [int] NULL,
	[Q4] [int] NULL,
	[Total] [int] NULL
);
");
        
        $this->execute("CREATE TABLE [dbo].[TEMP_PLAN](
	[ClientProductId] [int] NULL,
	[NameClient] [varchar](150) NULL,
	[ValueCenter] [varchar](150) NULL,
	[PerformanceCenter] [varchar](150) NULL,
	[Description] [varchar](150) NULL,
	[January] [int] NULL,
	[February] [int] NULL,
	[March] [int] NULL,
	[Q1] [int] NULL,
	[April] [int] NULL,
	[May] [int] NULL,
	[June] [int] NULL,
	[Q2] [int] NULL,
	[July] [int] NULL,
	[August] [int] NULL,
	[September] [int] NULL,
	[Q3] [int] NULL,
	[October] [int] NULL,
	[November] [int] NULL,
	[December] [int] NULL,
	[Q4] [int] NULL,
	[Total] [int] NULL
);");
    }

    public function down() {
        echo "m150826_165216_tables_temporally cannot be reverted.\n";

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
