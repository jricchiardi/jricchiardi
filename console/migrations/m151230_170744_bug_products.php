<?php

use yii\db\Migration;

class m151230_170744_bug_products extends Migration
{
    public function safeUp()
    {
$this->execute("ALTER VIEW [dbo].[GmidUnionTrade]
AS	
SELECT g.GmidId,	
	   TradeProductId = NULL,		
	   Description = g.Description,
	   tp.Description AS TradeProduct,
	   pc.Description AS PerformanceCenter ,
	   vc.Description AS ValueCenter,
	   g.Price,
	   g.Profit,
	   g.CountryId,
	   g.IsActive
FROM gmid g
INNER JOIN trade_product tp
ON g.TradeProductId = tp.TradeProductId
INNER JOIN performance_center pc 
ON pc.PerformanceCenterId = tp.PerformanceCenterId
INNER JOIN value_center vc 
ON vc.ValueCenterId = pc.ValueCenterId
WHERE g.IsForecastable = 1
UNION
SELECT GmidId = NULL,
	   tp.TradeProductId,
	   Description = tp.Description,
	   tp.Description AS TradeProduct,
	   pc.Description AS PerformanceCenter ,
	   vc.Description AS ValueCenter,
	   tp.Price,
	   tp.Profit,
	   NULL AS CountryId,
	   tp.IsActive
FROM trade_product tp
INNER JOIN gmid g 
ON g.TradeProductId = tp.TradeProductId
INNER JOIN performance_center pc 
ON pc.PerformanceCenterId = tp.PerformanceCenterId
INNER JOIN value_center vc 
ON vc.ValueCenterId = pc.ValueCenterId
WHERE tp.IsForecastable = 1
GROUP BY tp.TradeProductId,tp.Description,pc.Description,vc.Description,tp.Price,tp.Profit, tp.IsActive");
    }

    public function safeDown()
    {
        echo "m151230_170744_bug_products cannot be reverted.\n";

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
