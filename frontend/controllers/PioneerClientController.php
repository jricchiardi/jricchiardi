<?php

namespace frontend\controllers;

use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use common\components\controllers\CustomController;
use common\models\Import;
use common\models\TypeImport;
use common\models\UploadForm;
use common\services\ImportPioneerService;
use Yii;
use yii\web\UploadedFile;

require_once Yii::$app->basePath . '/spout-3.1.0/src/Spout/Autoloader/autoload.php';

class PioneerClientController extends CustomController
{
    public function actionDownload()
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        $clients = Yii::$app->db->createCommand("
        
            SELECT 
                dsm.Fullname         AS 'dsm',
                seller.Fullname      AS 'tam',
                cm.ClientMarketingId AS 'client_id',
                cm.Description       AS 'client'
            FROM client_marketing cm
            INNER JOIN client_type ct ON ct.ClientTypeId = cm.ClientTypeId
            INNER JOIN client_seller cs ON cs.ClientId = cm.ClientMarketingId
            INNER JOIN [user] seller ON seller.UserId = cs.SellerId
            INNER JOIN [user] dsm ON dsm.UserId = seller.ParentId
            WHERE ct.Description = 'AGENCIA'
            ORDER BY dsm

        ")->queryAll();

        $writer = WriterEntityFactory::createXLSXWriter();

        $fileName = "Clientes Pioneer (" . date("Y-m-d") . ").xls";
        $writer->openToBrowser($fileName);

        $header = [
            'DSM',
            'TAM',
            'CLIENTE ID',
            'CLIENTE',
        ];
        $headerBorder = (new BorderBuilder())
            ->setBorderBottom()
            ->setBorderTop()
            ->setBorderLeft()
            ->setBorderRight()
            ->build();
        $headerStyle = (new StyleBuilder())
            ->setFontName('Calibri')
            ->setFontBold()
            ->setFontSize(12)
            ->setCellAlignment(CellAlignment::CENTER)
            ->setBorder($headerBorder)
            ->build();
        $headerRow = WriterEntityFactory::createRowFromArray($header, $headerStyle);
        $writer->addRow($headerRow);

        $clientsBorder = (new BorderBuilder())
            ->setBorderLeft()
            ->setBorderRight()
            ->build();
        $clientsStyle = (new StyleBuilder())
            ->setFontName('Calibri')
            ->setFontSize(11)
            ->setCellAlignment(CellAlignment::CENTER)
            ->setBackgroundColor('FFF2CC')
            ->setBorder($clientsBorder)
            ->build();

        foreach ($clients as $client) {
            $clientRow = WriterEntityFactory::createRowFromArray(array_values($client), $clientsStyle);
            $writer->addRow($clientRow);
        }

        $writer->close();
    }

    public function actionIndex()
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->validate()) {
                $name = 'ClientesPioneer.xlsx';
                $model->file->saveAs('uploads/' . $name);

                $import = new Import();
                $import->Name = $name;
                $import->TypeImportId = TypeImport::CLIENT_PIONEER;

                $pathFile = 'uploads/' . $name;

                $errors = ImportPioneerService::importFromPathFile($pathFile);

                if (count($errors) === 0) {
                    if ($import->save()) {
                        Yii::$app->session->setFlash('success', Yii::t("app", 'The import was successful'));
                    }
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t("app", 'The import has errors'));
                    return $this->render('index', ['model' => $model, 'errors' => $errors]);
                }
            }
        }

        return $this->render('index', ['model' => $model]);
    }
}
