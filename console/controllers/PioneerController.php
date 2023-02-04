<?php

namespace console\controllers;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use common\models\Client;
use common\models\User;
use common\services\ImportPioneerService;
use Exception;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

require_once Yii::$app->basePath . './../frontend/spout-3.1.0/src/Spout/Autoloader/autoload.php';

class PioneerController extends Controller
{
    // The command "yii pioneer/import-excel"
    public function actionImportExcel($file)
    {
        $pathFile = Yii::getAlias("@console/pioneer/$file.xlsx");

        try {
            $result = ImportPioneerService::importFromPathFile($pathFile);
        } catch (Exception $e) {
            $this->stdout($e->getMessage(), Console::FG_RED, Console::ITALIC);
            return self::EXIT_CODE_ERROR;
        }

        return self::EXIT_CODE_NORMAL;
    }

    // The command "yii pioneer/delete"
    public function actionDelete()
    {
        $pathFile = Yii::getAlias('@console/pioneer/file.xlsx');

        $reader = ReaderEntityFactory::createReaderFromFile($pathFile);
        $reader->open($pathFile);

        $dsmIds = [];
        $tamIds = [];
        $clientIds = [];

        try {
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $n => $row) {
                    if ($n === 1) {
                        continue;
                    }
                    $data = $row->toArray();

                    $dsmName = str_replace(' ', '.', str_replace('ñ', 'n', trim($data[0])));
                    $dsm = User::findByUsername($dsmName);
                    if ($dsm) {
                        $dsmIds[] = $dsm->getId();
                    }

                    $tamName = str_replace(' ', '.', str_replace('ñ', 'n', trim($data[2])));
                    $tam = User::findByUsername($tamName);
                    if ($tam) {
                        $tamIds[] = $tam->getId();
                    }

                    $clientId = trim($data[3]);
                    if (!empty($clientId)) {
                        $client = Client::findOne($clientId);
                        if ($client) {
                            $clientIds[] = $client->ClientId;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
            return self::EXIT_CODE_ERROR;
        }

        $reader->close();

        $dsmIdsString = implode(',', array_unique($dsmIds));
        $tamIdsString = implode(',', array_unique($tamIds));
        $clientIdsString = implode(',', array_unique($clientIds));

        Yii::$app->db->createCommand("DELETE FROM client_seller WHERE SellerId IN ($tamIdsString)")->execute();
        Yii::$app->db->createCommand("DELETE FROM client_seller WHERE ClientId IN ($clientIdsString)")->execute();

        Yii::$app->db->createCommand("DELETE FROM client WHERE ClientId IN ($clientIdsString)")->execute();
        Yii::$app->db->createCommand("DELETE FROM client_marketing WHERE ClientMarketingId IN ($clientIdsString)")->execute();

        Yii::$app->db->createCommand("DELETE FROM auth_assignment WHERE user_id IN ($tamIdsString)")->execute();
        Yii::$app->db->createCommand("DELETE FROM [user] WHERE UserId IN ($tamIdsString)")->execute();

        Yii::$app->db->createCommand("DELETE FROM auth_assignment WHERE user_id IN ($dsmIdsString)")->execute();
        Yii::$app->db->createCommand("DELETE FROM [user] WHERE UserId IN ($dsmIdsString)")->execute();

        return self::EXIT_CODE_NORMAL;
    }
}
