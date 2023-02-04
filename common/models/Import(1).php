<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import".
 *
 * @property integer $ImportId
 * @property string $CreatedAt
 * @property integer $TypeImportId
 *
 * @property TypeImport $typeImport
 */
class Import extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'import';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['Name', 'TypeImportId'], 'required'],
            [['TypeImportId'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ImportId' => Yii::t('app', 'Import ID'),
            'CreatedAt' => Yii::t('app', 'Created At'),
            'TypeImportId' => Yii::t('app', 'Type Import ID'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['CreatedAt'],
                ],
                'value' => new Expression('GETDATE()'),
            ],
        ];
    }

    public function importToDBCyO($cyos) {
        ini_set("memory_limit", - 1);
        ini_set("max_execution_time", "9200");
        $errors = array();
        try {
            $connection = \Yii::$app->db;
            $lots = array_chunk($cyos, 1000);
            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('TEMP_CYO', ['[ClientId]'
                            , '[GmidId]'
                            , '[CampaignId]'
                            , '[InventoryBalance]'
                                ], $lot)
                        ->execute();
            }
            $command = $connection->createCommand("EXEC SP_ImportCyO ");
            $errors = $command->queryAll();
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit();
        }
        return $errors;
    }

    public function importToDBPlan($settings) {
        ini_set("memory_limit", - 1);
        ini_set("max_execution_time", "9200");
        $errors = array();
        try {
            $connection = \Yii::$app->db;
            $lots = array_chunk($settings, 1000);
            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('TEMP_PLAN', ['[ClientProductId]',
                            '[Country]',
                            '[DSM]',
                            '[DSMName]',
                            '[SellerId]',
                            '[SellerName]',
                            '[ClientId]',
                            '[NameClient]',
                            '[ClientType]',
                            '[ValueCenter]',
                            '[PerformanceCenter]',
                            '[Description]',
                            '[January]',
                            '[February]',
                            '[March]',
                            '[Q1]',
                            '[April]',
                            '[May]',
                            '[June]',
                            '[Q2]',
                            '[July]',
                            '[August]',
                            '[September]',
                            '[Q3]',
                            '[October]',
                            '[November]',
                            '[December]',
                            '[Q4]',
                            '[Total]',
                                ], $lot)
                        ->execute();
            }

            $command = $connection->createCommand(" SELECT          rtrim(ltrim([ClientProductId])) AS  ClientProductId                                                                                                                                 
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
                                                                FROM TEMP_PLAN WHERE ClientProductId IS NOT NULL");
            $imports = $command->queryAll();
            $connection->createCommand("DELETE FROM TEMP_PLAN")->execute();

            foreach ($imports as $item) {
                $model = \common\models\Plan::findOne(['ClientProductId' => $item['ClientProductId'], 'CampaignId' => Campaign::getFutureCampaign()->CampaignId]);
                // custom setAttributes dynamic set values from actual month
                $model->_setAttributes($item);
                $model->save();
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit();
        }
        return $errors;
    }

    public function importPlanToDatabase($plans) {
        ini_set("memory_limit", - 1);
        ini_set("max_execution_time", "9200");
        $errors = array();
        try {
            $connection = \Yii::$app->db;
            $lots = array_chunk($plans, 1000);
            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('TEMP_PLAN', ['[ClientProductId]',
                            '[NameClient]',
                            '[ValueCenter]',
                            '[PerformanceCenter]',
                            '[Description]',
                            '[January]',
                            '[February]',
                            '[March]',
                            '[Q1]',
                            '[April]',
                            '[May]',
                            '[June]',
                            '[Q2]',
                            '[July]',
                            '[August]',
                            '[September]',
                            '[Q3]',
                            '[October]',
                            '[November]',
                            '[December]',
                            '[Q4]',
                            '[Total]',
                                ], $lot)
                        ->execute();
            }

            $command = $connection->createCommand(" SELECT          rtrim(ltrim([ClientProductId])) AS  ClientProductId                                                                                                                                 
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
                                                                FROM TEMP_PLAN WHERE ClientProductId IS NOT NULL");
            $imports = $command->queryAll();
            $connection->createCommand("DELETE FROM TEMP_PLAN")->execute();

            foreach ($imports as $item) {
                $model = \common\models\Plan::findOne(['ClientProductId' => $item['ClientProductId'], 'CampaignId' => Campaign::getFutureCampaign()->CampaignId]);
                // custom setAttributes dynamic set values from actual month
                $model->_setAttributes($item);
                $model->save();
            }

            // AUDIT 
            \Yii::$app->auditcomponents->createAudit(['UserId' => \Yii::$app->user->identity->UserId,
                'TypeAuditId' => \common\models\TypeAudit::TYPE_IMPORT_PLAN_OFFLINE,
            ]);
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit();
        }
        return $errors;
    }

    /* IMPORT FROM EXCEL TO DB PRODUCTS , PERFORMANCES , */

    public function importToDBProducts($products) {
        try {

            $connection = \Yii::$app->db;
            $lots = array_chunk($products, 1000);

            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('TEMP_PRODUCT', ['[Country]',
                            '[F2]',
                            '[ValueCenter]',
                            '[F4]',
                            '[Performance Center]',
                            '[F7]',
                            '[Trade Product]',
                            '[F9]',
                            '[GMID]',
                            '[F11]',
                            '[Precio]',
                            '[Margen]',
                                ], $lot)
                        ->execute();
            }
            $command = $connection->createCommand("EXEC SP_ImportProducts ");
            $errors = $command->queryAll();

//            $trades = TradeProduct::find()->where(['SendMail' => true])->asArray()->all();
//            $newTrades = "";
//            foreach ($trades as $trade) {
//                $newTrades[] = $trade["Description"];
//            }
//            if (count($trades) > 0) {
//                $roles = [\common\models\AuthItem::ROLE_RSM, \common\models\AuthItem::ROLE_DSM, \common\models\AuthItem::ROLE_SELLER];
//
//                foreach ($roles as $rol) {
//                    $options = ['Description' => 'Nuevos productos : ' . implode(",",$newTrades),
//                        'FromUserId' => \common\models\User::find()->where(['Username' => 'admin'])->one()->UserId,
//                        'ToProfileId' => $rol,
//                        'WithEmail' => true,
//                        'send_notification_mail' => true,
//                        'Subject' => 'PODIUM - Forecast'
//                    ];
//
//                    Yii::$app->notificationscomponents->createNotification($options);
//                }
//                TradeProduct::updateAll(['SendMail'=>false]);
//            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit();
        }
        return $errors;
    }

    /* IMPORT FROM EXCEL TO DB CLIENTS */

    public function importToDBClients($customers) {

        try {
            $connection = \Yii::$app->db;
            $lots = array_chunk($customers, 1000);
            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('TEMP_CUSTOMER', ['[Country]',
                            '[Liable Customer]',
                            '[F3]',
                            '[Clasificacion]',
                            '[Field Seller]',
                            '[F6]',
                            '[Mail vendedor]',
                            '[DSM]',
                            '[F9]',
                            '[Mail DSM]',
                            '[RSM]',
                            '[F12]',
                            '[Mail RSM]'
                                ], $lot)
                        ->execute();
            }
            $command = $connection->createCommand(" EXEC SP_ImportCustomer ");
            $errors = $command->queryAll();
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit();
        }
        return $errors;
    }

    /* IMPORT FROM EXCEL TO DB SALES */

    public function importToDBSales($sales) {

        ini_set("memory_limit", - 1);
        ini_set("max_execution_time", "9200");
        try {
            $connection = \Yii::$app->db;
            $lots = array_chunk($sales, 1000);
            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('TEMP_SALE', ['[Country]',
                            '[Liable Customer]',
                            '[F3]',
                            '[GMID]',
                            '[F5]',
                            '[Field Seller]',
                            '[F7]',
                            '[Calendar year]',
                            '[Calendar month]',
                            '[Actual]',
                            '[Total]',
                            '[Actual2]'
                                ], $lot)
                        ->execute();
            }
            $command = $connection->createCommand(" EXEC SP_ImportSales ");
            $errors = $command->queryAll();
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit();
        }
        return $errors;
    }

    /* IMPORT FROM EXCEL TO FORECAST TABLE */

    public function importToDBForecast($forecasts) {

        ini_set("memory_limit", - 1);
        ini_set("max_execution_time", "9200");
        $errors = array();
        try {
            $connection = \Yii::$app->db;
            $lots = array_chunk($forecasts, 1000);
            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('TEMP_FORECAST', ['[ClientProductId]',
                            '[NameClient]',
                            '[ValueCenter]',
                            '[PerformanceCenter]',
                            '[Description]',
                            '[January]',
                            '[February]',
                            '[March]',
                            '[Q1]',
                            '[April]',
                            '[May]',
                            '[June]',
                            '[Q2]',
                            '[July]',
                            '[August]',
                            '[September]',
                            '[Q3]',
                            '[October]',
                            '[November]',
                            '[December]',
                            '[Q4]',
                            '[Total]',
                                ], $lot)
                        ->execute();
            }

            $command = $connection->createCommand(" SELECT          rtrim(ltrim([ClientProductId])) AS  ClientProductId                                                                                                                                 
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
                                                                FROM TEMP_FORECAST WHERE ClientProductId IS NOT NULL");
            $imports = $command->queryAll();
            $connection->createCommand("DELETE FROM TEMP_FORECAST")->execute();

            foreach ($imports as $item) {
                $model = \common\models\Forecast::findOne(['ClientProductId' => $item['ClientProductId'], 'CampaignId' => Campaign::getActualCampaign()->CampaignId]);
                // custom setAttributes dynamic set values from actual month
                $model->_setAttributes($item);
                $model->save();
            }

            // AUDIT 
            \Yii::$app->auditcomponents->createAudit(['UserId' => \Yii::$app->user->identity->UserId,
                'TypeAuditId' => \common\models\TypeAudit::TYPE_IMPORT_FORECAST_OFFLINE,
            ]);
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit();
        }
        return $errors;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypeImport() {
        return $this->hasOne(TypeImport::className(), ['TypeImportId' => 'TypeImportId']);
    }

}
