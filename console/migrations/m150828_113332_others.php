<?php

use yii\db\Schema;
use yii\db\Migration;

class m150828_113332_others extends Migration {

    public function up() {
        
        $this->execute("INSERT INTO client(ClientId,Description,IsGroup,CountryId,IsActive)
SELECT -u.UserId ,'OTROS',1,(SELECT TOP 1 CountryId 
							 FROM client cli
							 INNER JOIN client_seller cs 
							 ON cs.ClientId = cli.ClientId 
							 INNER JOIN [user] s 
							 ON s.UserId = cs.SellerId
							 WHERE u.UserId = s.UserId AND cli.CountryId IS NOT NULL
							 ) AS CountryId
							 ,1
FROM [user] u 
INNER JOIN auth_assignment asg
ON u.UserId = asg.user_id
WHERE asg.item_name = 'SELLER'");

        $this->execute("INSERT INTO client_seller(ClientId,SellerId)
SELECT -u.UserId,u.UserId 
FROM [user] u 
INNER JOIN auth_assignment asg
ON u.UserId = asg.user_id
WHERE asg.item_name = 'SELLER'");
    }

    public function down() {
        echo "m150828_113332_others cannot be reverted.\n";

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
