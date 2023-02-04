<?php

namespace common\models;

use Exception;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "import".
 *
 * @property integer $ImportId
 * @property string $CreatedAt
 * @property integer $TypeImportId
 * @property boolean $FinishedCorrectly
 * @property boolean $WithErrors
 *
 * @property TypeImport $typeImport
 */
class Import extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'import';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'TypeImportId'], 'required'],
            [['TypeImportId'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ImportId' => Yii::t('app', 'Import ID'),
            'CreatedAt' => Yii::t('app', 'Created At'),
            'TypeImportId' => Yii::t('app', 'Type Import ID'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
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

    /**
     * @return array
     */
    public static function getAutomaticSapImports()
    {
        $validTypeImports = [
            TypeImport::AUTOMATIC_DAS_SALE => "DAS Sales",
            TypeImport::AUTOMATIC_DAS_CYO => "DAS CyOs",
            TypeImport::AUTOMATIC_DUPONT_SALE => "DUPONT Sales",
            TypeImport::AUTOMATIC_DUPONT_CYO => "DUPONT CyOs",
	        TypeImport::AUTOMATIC_DUPONT_OPEN_ORDERS => "DUPONT OpenOrders",
			TypeImport::AUTOMATIC_DAS_OPEN_ORDERS => "DAS OpenOrders",
			TypeImport::AUTOMATIC_DELIV_OPEN_ORDERS => "DELIV OpenOrders",
			TypeImport::AUTOMATIC_CRED_OPEN_ORDERS => "CRED OpenOrders",
			TypeImport::AUTOMATIC_DUPONT_FC_NOCONT => "DUPONT FcNoCont",
			TypeImport::AUTOMATIC_DAS_FC_NOCONT => "DAS FcNoCont",
			TypeImport::AUTOMATIC_DUPONT_SHORT_FC_NOCONT => "DUPONT Short FcNoCont",
			TypeImport::AUTOMATIC_DAS_SHORT_FC_NOCONT => "DAS Short FcNoCont",
			TypeImport::AUTOMATIC_DUPONT_DESP_NOFC => "DUPONT DespNoFc",
			TypeImport::AUTOMATIC_DAS_DESP_NOFC => "DAS DespNoFc",
			TypeImport::AUTOMATIC_FCASTIBP => "Forecast IBP",
			
        ];

        $whereCondition = "typeImportId IN (" . implode(",", array_keys($validTypeImports)) . ")";

        $imports = self::find()
            ->andWhere($whereCondition)
            ->orderBy(['createdAt' => SORT_DESC])
            ->all();

        return array_map(function (Import $import) use ($validTypeImports) {
            $newImport = $import->getAttributes();
            $newImport['TypeImportName'] = $validTypeImports[$import['TypeImportId']];
            return $newImport;
        }, $imports);
    }

    /**
     * @return string
     */
    public static function getLastDateAutomaticDASSale()
    {
        return self::getLastDateAutomaticSap(TypeImport::AUTOMATIC_DAS_SALE);
    }

    /**
     * @return string
     */
    public static function getLastDateAutomaticDupontSale()
    {
        return self::getLastDateAutomaticSap(TypeImport::AUTOMATIC_DUPONT_SALE);
    }

    /**
     * @return string
     */
    public static function getLastDateAutomaticDASCyo()
    {
        return self::getLastDateAutomaticSap(TypeImport::AUTOMATIC_DAS_CYO);
    }

    /**
     * @return string
     */
    public static function getLastDateAutomaticDupontCyo()
    {
        return self::getLastDateAutomaticSap(TypeImport::AUTOMATIC_DUPONT_CYO);
    }
	
	/**
     * @return string
     */
    public static function getLastDateAutomaticDupontOpenOrders()
    {
        return self::getLastDateAutomaticSap(TypeImport::AUTOMATIC_DUPONT_OPEN_ORDERS);
    }
	
	public static function getLastDateAutomaticDasOpenOrders()
    {
        return self::getLastDateAutomaticSap(TypeImport::AUTOMATIC_DAS_OPEN_ORDERS);
    }
	
	public static function getLastDateAutomaticDelivOpenOrders()
    {
        return self::getLastDateAutomaticSap(TypeImport::AUTOMATIC_DELIV_OPEN_ORDERS);
    }
	
	public static function getLastDateAutomaticCredOpenOrders()
    {
        return self::getLastDateAutomaticSap(TypeImport::AUTOMATIC_CRED_OPEN_ORDERS);
    }
	
	public static function getLastDateAutomaticDupontFcNocont()
    {
        return self::getLastDateAutomaticSap(TypeImport::AUTOMATIC_DUPONT_FC_NOCONT);
    }
	public static function getLastDateAutomaticDasFcNoCont()
    {
        return self::getLastDateAutomaticSap(TypeImport::AUTOMATIC_DAS_FC_NOCONT);
    }
	public static function getLastDateAutomaticDupontShortFcNocont()
    {
        return self::getLastDateAutomaticSap(TypeImport::AUTOMATIC_DUPONT_SHORT_FC_NOCONT);
    }
	public static function getLastDateAutomaticDasShortFcNoCont()
    {
        return self::getLastDateAutomaticSap(TypeImport::AUTOMATIC_DAS_SHORT_FC_NOCONT);
    }
	
	public static function getLastDateAutomaticDupontDespNoFc()
    {
        return self::getLastDateAutomaticSap(TypeImport::AUTOMATIC_DUPONT_DESP_NOFC);
    }
	
	public static function getLastDateAutomaticDasDespNoFc()
    {
        return self::getLastDateAutomaticSap(TypeImport::AUTOMATIC_DAS_DESP_NOFC);
    }
	
	public static function getLastDateAutomaticFCASTIBP()
    {
        return self::getLastDateAutomaticSap(TypeImport::AUTOMATIC_FCASTIBP);
    }

    /**
     * @param string $typeImportId
     * @return string|null
     */
    private static function getLastDateAutomaticSap($typeImportId)
    {
        $import = Import::find()
            ->select(['CreatedAt'])
            ->where(['TypeImportId' => $typeImportId])
            ->orderBy(['CreatedAt' => SORT_DESC])
            ->one();

        if ($import !== null) {
            return $import->CreatedAt;
        }

        return null;
    }

    /**
     * @return ActiveQuery
     */
    public function getImportErrors()
    {
        return $this->hasMany(ImportError::className(), ['ImportId' => 'ImportId']);
    }

    public function importToDBCyO($cyos)
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");
        $errors = array();
        try {
            $connection = Yii::$app->db;
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
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }
        return $errors;
    }

    public function importToDBCyOFromAutomaticImport($cyos)
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        try {
            $connection = Yii::$app->db;

            $lots = array_chunk($cyos, 1000);
            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('TEMP_AUTOMATIC_CYO', [
                    '[ClientId]',
                    '[GmidId]',
                    '[CampaignId]',
                    '[InventoryBalance]',
                    '[ImportId]',
                ], $lot)->execute();
            }
			
            $command = $connection->createCommand("EXEC SP_Automatic_Import_Cyos");
            $errors = $command->queryAll();
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return $errors;
    }

    public function importToDBPlan($settings, $campaignId)
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        $errors = array();

        try {
            $connection = Yii::$app->db;
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
                ], $lot)->execute();
            }

            $connection->createCommand("EXEC BulkUpdatePlan :campaignId;")
                ->bindValue(':campaignId', $campaignId)
                ->execute();

            $connection->createCommand("DELETE FROM TEMP_PLAN")->execute();
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }
        return $errors;
    }

    public function importToDBOpportunity($settings)
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        $errors = array();

        try {
            $connection = Yii::$app->db;
            $lots = array_chunk($settings, 1000);
            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('TEMP_OPPORTUNITY', ['[ClientProductId]',
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
                    '[Amount]',
                ], $lot)->execute();
            }

            $command = $connection->createCommand(" SELECT rtrim(ltrim([ClientProductId])) AS  ClientProductId, [Amount] FROM TEMP_OPPORTUNITY WHERE ClientProductId IS NOT NULL");
            $imports = $command->queryAll();

            $connection->createCommand("DELETE FROM TEMP_OPPORTUNITY")->execute();

            foreach ($imports as $item) {
                $model = Opportunity::findOne(['ClientProductId' => $item['ClientProductId'], 'CampaignId' => Campaign::getActualCampaign()->CampaignId]);

                if ($model === null) {
                    $model = new Opportunity();
                    $model->ClientProductId = $item['ClientProductId'];
                    $model->Amount = $item['Amount'];
                    $model->CampaignId = Campaign::getActualCampaign()->CampaignId;
                } else {
                    $model->_setAttributes($item);
                }

                $model->save();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }
        return $errors;
    }

    public function importToDBSellingOut($settings)
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        $errors = array();

        try {
            $connection = Yii::$app->db;
            $lots = array_chunk($settings, 1000);
            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('TEMP_SELLING_OUT', ['[ClientProductId]',
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
                    '[Amount]',
                ], $lot)->execute();
            }

            $command = $connection->createCommand(" SELECT rtrim(ltrim([ClientProductId])) AS  ClientProductId, [Amount] FROM TEMP_SELLING_OUT WHERE ClientProductId IS NOT NULL");
            $imports = $command->queryAll();

            $connection->createCommand("DELETE FROM TEMP_SELLING_OUT")->execute();

            foreach ($imports as $item) {
                $model = SellingOut::findOne(['ClientProductId' => $item['ClientProductId'], 'CampaignId' => Campaign::getActualCampaign()->CampaignId]);

                if ($model === null) {
                    $model = new SellingOut();
                    $model->ClientProductId = $item['ClientProductId'];
                    $model->Amount = $item['Amount'];
                    $model->CampaignId = Campaign::getActualCampaign()->CampaignId;
                } else {
                    $model->_setAttributes($item);
                }

                $model->save();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }
        return $errors;
    }

    public function importPlanToDatabase($plans)
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");
        $errors = array();
        try {
            $connection = Yii::$app->db;
            $lots = array_chunk($plans, 1000);
            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('TEMP_PLAN', [
                    '[ClientProductId]',
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
                $model = Plan::findOne(['ClientProductId' => $item['ClientProductId'], 'CampaignId' => Campaign::getFutureCampaign()->CampaignId]);
                // custom setAttributes dynamic set values from actual month
                $model->_setAttributes($item);
                $model->save();
            }

            // AUDIT
            Yii::$app->auditcomponents->createAudit(['UserId' => Yii::$app->user->identity->UserId,
                'TypeAuditId' => TypeAudit::TYPE_IMPORT_PLAN_OFFLINE,
            ]);
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }
        return $errors;
    }

    /* IMPORT FROM EXCEL TO DB PRODUCTS , PERFORMANCES , */
    public function importToDBProducts($products)
    {
        try {

            $connection = Yii::$app->db;
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
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }
        return $errors;
    }

    /* IMPORT FROM EXCEL TO DB CLIENTS */
    public function importToDBClients($customers)
    {

        try {
            $connection = Yii::$app->db;
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
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }
        return $errors;
    }

    /* IMPORT FROM EXCEL TO DB SALES */
    public function importToDBSales($sales)
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");
        try {
            $connection = Yii::$app->db;
            $lots = array_chunk($sales, 1000);
            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('TEMP_SALE', [
                    '[Country]',
                    '[Liable Customer]',
                    '[F3]',
                    '[GMID]',
                    '[F5]',
                    '[Calendar year]',
                    '[Calendar month]',
                    '[Actual]',
                    '[Total]',
                    '[Actual2]',
                ], $lot)
                    ->execute();
            }
            $command = $connection->createCommand("EXEC SP_ImportSales :typeImportId;")
                ->bindValue(':typeImportId', $this->TypeImportId);
            $errors = $command->queryAll();
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }
        return $errors;
    }

    public function importToDBSalesFromAutomaticImport($sales)
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        try {
            $connection = Yii::$app->db;

            $lots = array_chunk($sales, 1000);
            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('TEMP_AUTOMATIC_SALE', [
                    '[Country]',
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
                    '[Actual2]',
                    '[ImportId]',
                ], $lot)->execute();
            }
            $command = $connection->createCommand("EXEC SP_Automatic_Import_Sales");
            $errors = $command->queryAll();
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }

        return $errors;
    }

    public function importToDBSalesFromAutomaticImportDupont($sales)
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        try {
            $connection = Yii::$app->db;

            $lots = array_chunk($sales, 1000);
            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('TEMP_AUTOMATIC_SALE', [
                    '[Country]',
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
                    '[Actual2]',
                    '[ImportId]',
                ], $lot)->execute();
            }
            $command = $connection->createCommand("EXEC SP_Automatic_Import_Dupont_Sales");
            $errors = $command->queryAll();
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }

        return $errors;
    }

    /* IMPORT FROM EXCEL TO FORECAST TABLE */
    public function importToDBForecast($forecasts)
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");
        $errors = array();
        try {
            $connection = Yii::$app->db;
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
                $model = Forecast::findOne(['ClientProductId' => $item['ClientProductId'], 'CampaignId' => Campaign::getActualCampaign()->CampaignId]);
                if ($model !== null) {
                    // custom setAttributes dynamic set values from actual month
                    $model->_setAttributes($item);
                    $model->save();
                }
            }

            // AUDIT
            Yii::$app->auditcomponents->createAudit(['UserId' => Yii::$app->user->identity->UserId,
                'TypeAuditId' => TypeAudit::TYPE_IMPORT_FORECAST_OFFLINE,
            ]);
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }

        return $errors;
    }

    /* IMPORT FROM EXCEL TO FORECAST MARKETING TABLE */
    public function importToDBForecastMarketingOLD($forecasts, $campaignId)
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        $errors = [];

        /*
         * REEMPLAZAR ESTO CON EL SP QUE UPDATEA AUTOMATICAMENTE DESDE EL TEMP
         * (VER COMO SE HIZO EL BULKPLAN)
        */
        try {
            $connection = Yii::$app->db;

            $lots = array_chunk($forecasts, 1000);

            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('TEMP_FORECAST_MARKETING', [
                    '[ClientMarketingProductId]',
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
                ], $lot)->execute();
            }

            $connection->createCommand("EXEC SP_Update_Forecast_Marketing :campaignId;")
                ->bindValue(':campaignId', $campaignId)
                ->execute();

            $connection->createCommand("DELETE FROM TEMP_FORECAST_MARKETING")->execute();

            // AUDIT
            Yii::$app->auditcomponents->createAudit([
                'UserId' => Yii::$app->user->identity->UserId,
                'TypeAuditId' => TypeAudit::TYPE_IMPORT_MARKETING_FORECAST_OFFLINE,
            ]);
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }

        return $errors;
    }

    public function importToDBForecastMarketing($forecasts, $campaignId)
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        $errors = [];

        try {
            $connection = Yii::$app->db;

            $lots = array_chunk($forecasts, 1000);

            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('TEMP_FORECAST_MARKETING', [
                    '[DSMId]',
                    '[TradeProductId]',
                    '[GmidId]',
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
                ], $lot)->execute();
            }

            $connection->createCommand("EXEC SP_Update_Forecast_Marketing :campaignId;")
                ->bindValue(':campaignId', $campaignId)
                ->execute();

            $connection->createCommand("DELETE FROM TEMP_FORECAST_MARKETING")->execute();

            // AUDIT
            Yii::$app->auditcomponents->createAudit([
                'UserId' => Yii::$app->user->identity->UserId,
                'TypeAuditId' => TypeAudit::TYPE_IMPORT_MARKETING_FORECAST_OFFLINE,
            ]);
        } catch (Exception $e) {
            $connection->createCommand("DELETE FROM TEMP_FORECAST_MARKETING")->execute();
            echo $e->getMessage();
            exit();
        }

        return $errors;
    }

    public function importToDBAssociationPmProduct($items)
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        try {
            $connection = Yii::$app->db;
            $lots = array_chunk($items, 1000);
            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('TEMP_PM_PRODUCT', [
                    '[TradeProductId]',
                    '[GmidId]',
                    '[Username]',
                ], $lot)->execute();
            }

            return $connection->createCommand("EXEC SP_Import_PM_Product")->queryAll();
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }
    }

    /**
     * @return ActiveQuery
     */
    public function getTypeImport()
    {
        return $this->hasOne(TypeImport::className(), ['TypeImportId' => 'TypeImportId']);
    }

    public function importToDBUnificacionClientes($clientes)
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        $errors = [];

        try {
            $connection = Yii::$app->db;

            $connection->createCommand("DELETE FROM unificacion_cliente")->execute();

            $lots = array_chunk($clientes, 1000);
            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('unificacion_cliente', [
                    '[Country]',
                    '[SoldToParty]',
                    '[Customer]',
                    '[FieldSeller]',
                    '[DSM]',
                    '[ConversionCode]',
                    '[ConversionName]',
                    '[CUIT]',
                ], $lot)->execute();
            }
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }

        return $errors;
    }

    /* IMPORT FROM EXCEL TO DB CLIENTS MARKETING*/
    public function importToDBClientsMarketing($customers)
    {
        try {
            $connection = Yii::$app->db;

            $lots = array_chunk($customers, 1000);

            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('TEMP_CLIENT_MARKETING', [
                    '[Country]',
                    '[Liable Customer]',
                    '[F3]',
                    '[Clasificacion]',
                ], $lot)->execute();
            }

            $command = $connection->createCommand("EXEC SP_ImportClientsMarketing");

            $errors = $command->queryAll();
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }

        return $errors;
    }
    
    /* IMPORT FROM EXCEL TO DB OPEN ORDERS*/
    
    
    
    public function importToDBOpenOrdersFromAutomaticImport($openOrders)
    {
        try {
            $connection = Yii::$app->db;

            $lots = array_chunk($openOrders, 1000);  

            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('TEMP_OPENORDERS', [
                     '[SalesOrg]',
                     '[Item]' ,
                     '[OrderNo]',
                     '[DelivNo]' ,
                     '[CredBlock]' ,
                     '[OrderType]'  ,	
                     '[SoldToCustNumber]',
                     '[SoldToCustName]' ,
                     '[MaterialCode]',
                     '[MaterialDescript]' ,  
                     '[PlantCode]' , 
                     '[OpenQConfirmedQ]', 
                     '[OrderQ]',
                     '[SalesUoM]',
                     '[ConfirmedDelvDate]',
                     '[ShipToCustNumber]', 
                     '[ShipToCustName]', 
                     '[CustPurchaseOrdNo]',
                     '[ConfirmedShipDate]',
                ], $lot)->execute();
            }

            $command = $connection->createCommand("EXEC SP_ImportOpenOrders");
            $errors = $command->queryAll();
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }

        return $errors;
    }
	
	/* IMPORT FROM EXCEL TO DB FC NO CONT*/
	
	public function importToDBFcNoContFromAutomaticImport($FcNoCont)
    {
        try {
            $connection = Yii::$app->db;

            $lots = array_chunk($FcNoCont, 1000);
            
            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('TEMP_FCNOCONT', [
                     '[SalesOrg]',
                     '[BillingNo]' ,
                     '[BillingType]',
                     '[SoldToPartyNumber]',
                     '[SoldToPartyName]' ,
					 '[Item]' ,
                     '[MaterialCode]',
                     '[MaterialDescript]' ,                       
                     '[BilledQ]',
                     '[BaseUoM]',                     
                     '[BillingDate]',
                ], $lot)->execute();
            }

            $command = $connection->createCommand("EXEC SP_ImportFcNoCont");

          $errors = $command->queryAll();
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }

        return $errors;
    }
	
	/* IMPORT FROM EXCEL TO DB DESP NO FC */
	
	public function importToDBDespNoFcFromAutomaticImport($DespNoFc)
    {
        try {
            $connection = Yii::$app->db;

            $lots = array_chunk($DespNoFc, 1000);
            
            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('TEMP_DESPNOFC', [
                     '[SalesDoc]',
                     '[SalesItem]' ,
                     '[SalesDocType]',
                     '[SoldToCustNumber]',
                     '[SoldToCustName]' ,
                     '[MaterialCode]',
                     '[MaterialDescript]' ,                       
                     '[DeliveryQ]',
                     '[SalesUoM]', 
                ], $lot)->execute();
            }

           $command = $connection->createCommand("EXEC SP_ImportDespNoFc");

           $errors = $command->queryAll();
        } catch (Exception $e) {
          echo $e->getMessage();
            exit();
        }
		
		
        return $errors;
    }
	
	
	/* IMPORT FROM EXCEL TO DB FORECAST IBP */
	
	public function importToDBFcastIBPFromAutomaticImport($FcastIBP)
    {
        try {
            $connection = Yii::$app->db;
            $connection->createCommand("DELETE FROM TEMP_FCASTIBP")->execute();

            $lots = array_chunk($FcastIBP, 1000);
            
            foreach ($lots as $lot) {
                $connection->createCommand()->batchInsert('TEMP_FCASTIBP', [
                    '[ShipToCountry]',
                    '[Portfolio]' ,
                    '[Ingredient]',
                    '[OldProductID]',
                    '[ProductDesc]' ,
                    '[KeyFigure]',
					'[January]',
                    '[February]',
                    '[March]',
                    '[April]',
                    '[May]',
                    '[June]',
                    '[July]',
                    '[August]',
                    '[September]',
                    '[October]',
                    '[November]',
                    '[December]',					 
                    '[TotalYear]' ,                       
                    '[Año]',
                   
                ], $lot)->execute();
            }

           $command = $connection->createCommand("EXEC SP_ImportFcastIBP");

           $errors = $command->queryAll();
        } catch (Exception $e) {
          echo $e->getMessage();
		  var_dump($e);
            exit();
        }
		
		
        return $errors;
    }
	
	
	
	
	
	
	
}
