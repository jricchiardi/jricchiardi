<?php

use yii\db\Migration;

class m160223_153206_forecast_new_campaign extends Migration
{
    public function safeUp()
    {
        $this->execute("INSERT INTO forecast([ClientProductId]
					  ,[CampaignId]
					  ,[January]
					  ,[February]
					  ,[March]
					  ,[Q1]
					  ,[April]
					  ,[May]
					  ,[June]
					  ,[Q2]
					  ,[July]
					  ,[August]
					  ,[September]
					  ,[Q3]
					  ,[October]
					  ,[November]
					  ,[December]
					  ,[Q4]
					  ,[Total])

                        SELECT			   [ClientProductId]					  
                                                          ,[CampaignId]
                                                          ,[January]
                                                          ,[February]
                                                          ,[March]
                                                          ,[Q1]
                                                          ,[April]
                                                          ,[May]
                                                          ,[June]
                                                          ,[Q2]
                                                          ,[July]
                                                          ,[August]
                                                          ,[September]
                                                          ,[Q3]
                                                          ,[October]
                                                          ,[November]
                                                          ,[December]
                                                          ,[Q4]
                                                          ,[Total]
                        FROM [plan] 
                        WHERE CampaignId = 7");
        
        $this->execute("UPDATE client_product set IsForecastable = 0 
                                            FROM client_product cp 
                                            inner join
                                            (
                                                    select cp.ClientProductId,g.TradeProductId 
                                                    from client_product cp
                                                    inner join trade_product tp 
                                                    on cp.TradeProductId = tp.TradeProductId and tp.IsForecastable = 1
                                                    inner join gmid g 
                                                    on tp.TradeProductId = g.TradeProductId
                                                    inner join client c
                                                    on c.ClientId = cp.ClientId AND c.CountryId <> g.CountryId
                                                    group by cp.ClientProductId,g.TradeProductId
                                            ) diff
                                            on diff.ClientProductId = cp.ClientProductId");
    }

    public function safeDown()
    {
        echo "m160223_153206_forecast_new_campaign cannot be reverted.\n";

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
