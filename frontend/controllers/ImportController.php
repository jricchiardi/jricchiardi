<?php

namespace frontend\controllers;

use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use common\components\controllers\CustomController;
use common\models\Campaign;
use common\models\DownloadValidacionPlanForm;
use common\models\Import;
use common\models\PmProduct;
use common\models\sap\ClienteUnificado;
use common\models\TypeImport;
use common\models\UploadForm;
use common\models\UploadValidacionPlanForm;
use PHPExcel_IOFactory;
use Yii;
// use yii\base\Object;
use yii\web\UploadedFile;

require_once Yii::$app->basePath . '/spout-3.1.0/src/Spout/Autoloader/autoload.php';

/**
 * ImportController implements the CRUD actions for Client model.
 */
class ImportController extends CustomController
{
    public function actionCyo()
    {
        $model = new UploadForm();
        $import = new Import();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                $name = 'CyO.xls';
                $model->file->saveAs('uploads/' . $name);
                $import->Name = $name;
                $import->TypeImportId = TypeImport::CyO;
                $pathFile = 'uploads/' . $name;

                $objReader = PHPExcel_IOFactory::createReaderForFile($pathFile);
                $objReader->setReadDataOnly(true);
                $objPHPExcel = $objReader->load($pathFile);
                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $cyos = array();
                for ($row = 22; $row <= $highestRow; $row++) {
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                    $cyos[] = ['ClientId' => $rowData[0][3], 'GmidId' => $rowData[0][6], 'CampaignId' => Campaign::getActualCampaign()->CampaignId, 'InventoryBalance' => $rowData[0][9]];
                }

                $errors = $import->importToDBCyO($cyos);

                if (count($errors) == 0) {
                    if ($import->save()) {
                        Yii::$app->session->setFlash('success', Yii::t("app", 'The import was successful'));
                    }
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t("app", 'The import has errors'));
                    return $this->render('importCyO', ['model' => $model, 'errors' => $errors]);
                }
            }
        }
        return $this->render('importCyO', ['model' => $model]);
    }

    public function actionSetting()
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        $model = new UploadValidacionPlanForm();
        $modelDowload = new DownloadValidacionPlanForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            if ($model->plan === 'futuro') {
                if (Campaign::getActualCampaign()->isSettingActive()) {
                    Yii::$app->session->setFlash('danger', Yii::t("app", "The plan is locked because the setting dates is different to today!"));
                    return $this->render('importSetting', ['model' => $model, 'modelDownload' => $modelDowload, 'errors' => NULL]);
                }
            }

            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->validate()) {
                $name = 'Setting.xlsx';
                $model->file->saveAs('uploads/' . $name);

                $import = new Import();
                $import->Name = $name;
                $import->TypeImportId = TypeImport::SETTING;

                $pathFile = 'uploads/' . $name;

                $reader = ReaderEntityFactory::createReaderFromFile($pathFile);
                $reader->open($pathFile);

                $settings = [];
                foreach ($reader->getSheetIterator() as $sheet) {
                    foreach ($sheet->getRowIterator() as $n => $row) {
                        if ($n < 6) {
                            continue;
                        }

                        $data = $row->toArray();
                        $settings[] = [
                            intval($data[0]),
                            strval($data[1]),
                            strval($data[2]),
                            strval($data[3]),
                            strval($data[4]),
                            strval($data[5]),
                            strval($data[6]),
                            strval($data[7]),
                            strval($data[8]),
                            strval($data[9]),
                            strval($data[10]),
                            strval($data[11]),
                            intval($data[12]),
                            intval($data[13]),
                            intval($data[14]),
                            intval($data[15]),
                            intval($data[16]),
                            intval($data[17]),
                            intval($data[18]),
                            intval($data[19]),
                            intval($data[20]),
                            intval($data[21]),
                            intval($data[22]),
                            intval($data[23]),
                            intval($data[24]),
                            intval($data[25]),
                            intval($data[26]),
                            intval($data[27]),
                            intval($data[28]),
                        ];
                    }
                }

                $reader->close();

                if ($model->plan === 'actual') {
                    $campaignId = Campaign::getActualCampaign()->CampaignId;
                } else {
                    $campaignId = Campaign::getFutureCampaign()->CampaignId;
                }

                $errors = $import->importToDBPlan($settings, $campaignId);

                if (count($errors) == 0) {
                    if ($import->save()) {
                        Yii::$app->session->setFlash('success', Yii::t("app", 'The import was successful'));
                    }
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t("app", 'The import has errors'));
                    return $this->render('importSetting', ['model' => $model, 'modelDownload' => $modelDowload, 'errors' => $errors]);
                }
            }
        }

        return $this->render('importSetting', ['model' => $model, 'modelDownload' => $modelDowload]);
    }

    public function actionCustomer()
    {
        $model = new UploadForm();

        $import = new Import();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                $name = 'Customer.xlsx';
                $model->file->saveAs('uploads/' . $name);
                $import->Name = $name;
                $import->TypeImportId = TypeImport::CLIENT;
                $pathFile = 'uploads/' . $name;

                $objReader = PHPExcel_IOFactory::createReaderForFile($pathFile);
                $objReader->setReadDataOnly(true);
                $objPHPExcel = $objReader->load($pathFile);
                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $customers = array();
                for ($row = 2; $row <= $highestRow; $row++) {
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                    $customers[] = $rowData[0];
                }

                $errors = $import->importToDBClients($customers);

                if (count($errors) == 0) {
                    if ($import->save()) {
                        Yii::$app->session->setFlash('success', Yii::t("app", 'The import was successful'));
                    }
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t("app", 'The import has errors'));
                    return $this->render('importClient', ['model' => $model, 'errors' => $errors]);
                }
            }
        }
        return $this->render('importClient', ['model' => $model]);
    }

    public function actionProduct()
    {

        $model = new UploadForm();

        $import = new Import();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                $name = 'Productos.xlsx';
                $model->file->saveAs('uploads/' . $name);
                $import->Name = $name;
                $import->TypeImportId = TypeImport::PRODUCT;

                $pathFile = 'uploads/' . $name;

                $reader = ReaderEntityFactory::createReaderFromFile($pathFile);
                $reader->open($pathFile);

                $products = [];
                foreach ($reader->getSheetIterator() as $sheet) {
                    if ($sheet->getIndex() === 0) {
                        foreach ($sheet->getRowIterator() as $n => $row) {
                            if ($n === 1) {
                                continue;
                            }

                            $rowData = $row->toArray();

                            array_walk($rowData, function (&$value, $key) {
                                $value = ($key === 8) ? intval($value) : strval($value);
                            });

                            $products[] = $rowData;
                        }
                        break;
                    }
                }
                $reader->close();

                $errors = $import->importToDBProducts($products);

                if (count($errors) != 0) {
                    Yii::$app->session->setFlash('danger', Yii::t("app", 'The import has errors'));
                    return $this->render('importProduct', ['model' => $model, 'errors' => $errors]);
                }

                if ($import->save()) {
                    Yii::$app->session->setFlash('success', Yii::t("app", 'The import was successful'));
                }
            }
        }

        return $this->render('importProduct', ['model' => $model]);
    }

    public function actionSale()
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");
        $model = new UploadForm();

        $import = new Import();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                $name = 'Ventas.xlsx';
                $import->Name = $name;
                $import->TypeImportId = TypeImport::SALE;

                $reader = ReaderEntityFactory::createReaderFromFile($model->file->name);
                $reader->open($model->file->tempName);
                $sales = [];
                foreach ($reader->getSheetIterator() as $sheet) {
                    foreach ($sheet->getRowIterator() as $n => $row) {
                        if ($n < 2) {
                            continue;
                        }
                        $data = $row->toArray();
                        $data[4] = strval($data[4]);
                        $sales[] = $data;
                    }
                }
                $reader->close();

                $errors = $import->importToDBSales($sales);

                if (count($errors) == 0) {
                    $model->file->saveAs('uploads/' . $name);
                    if ($import->save()) {
                        Yii::$app->session->setFlash('success', Yii::t("app", 'The import was successful'));
                    }
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t("app", 'The import has errors'));
                    return $this->render('importSale', ['model' => $model, 'errors' => $errors]);
                }
            }
        }

        return $this->render('importSale', ['model' => $model]);
    }

    public function actionDownloadSale()
    {
        $path = Yii::getAlias('@webroot') . '/uploads';

        $file = $path . '/Ventas.xlsx';

        if (file_exists($file)) {
            Yii::$app->response->sendFile($file);
        }
    }

    public function actionOfflinePlan()
    {

        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        $model = new UploadForm();

        $import = new Import();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                $name = 'OfflinePlan.xlsx';
                $model->file->saveAs('uploads/' . $name);
                $import->Name = $name;
                $import->TypeImportId = TypeImport::PLAN;
                $pathFile = 'uploads/' . $name;

                $reader = ReaderEntityFactory::createReaderFromFile($pathFile);
                $reader->open($pathFile);

                $items = [];
                foreach ($reader->getSheetIterator() as $sheet) {
                    foreach ($sheet->getRowIterator() as $n => $row) {
                        if ($n === 2) {
                            // VALIDATE MONTH
                            $month = $row->getCells()[2]->getValue();
                            $actualMonth = date("m");
                            $isLock = Yii::$app->utilcomponents->isPlanEnable();
                            if ($isLock) {
                                Yii::$app->session->setFlash('danger', Yii::t("app", "The Plan is locked!"));
                                return $this->render('importPlanOffline', ['model' => $model, 'errors' => NULL]);
                            }
                            if ($month != $actualMonth) {
                                Yii::$app->session->setFlash('danger', Yii::t("app", "The Excel file is of a wrong month !"));
                                return $this->render('importPlanOffline', ['model' => $model, 'errors' => NULL]);
                            }
                        }

                        if ($n < 7) {
                            continue;
                        }

                        $data = $row->toArray();
                        $items[] = [
                            intval($data[0]),
                            strval($data[1]),
                            strval($data[2]),
                            strval($data[3]),
                            strval($data[4]),
                            intval($data[5]),
                            intval($data[6]),
                            intval($data[7]),
                            intval($data[8]),
                            intval($data[9]),
                            intval($data[10]),
                            intval($data[11]),
                            intval($data[12]),
                            intval($data[13]),
                            intval($data[14]),
                            intval($data[15]),
                            intval($data[16]),
                            intval($data[17]),
                            intval($data[18]),
                            intval($data[19]),
                            intval($data[20]),
                            intval($data[21]),
                        ];
                    }
                }

                $reader->close();

                $errors = $import->importPlanToDatabase($items);

                if (count($errors) == 0) {
                    if ($import->save()) {
                        Yii::$app->session->setFlash('success', Yii::t("app", 'The import was successful'));
                    }
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t("app", 'The import has errors'));
                    return $this->render('importPlanOffline', ['model' => $model, 'errors' => $errors]);
                }
            }
        }
        return $this->render('importPlanOffline', ['model' => $model]);
    }

    public function actionOffline()
    {
        $model = new UploadForm();

        $import = new Import();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                $name = 'Offline.xlsx';
                $model->file->saveAs('uploads/' . $name);
                $import->Name = $name;
                $import->TypeImportId = TypeImport::OFFLINE;
                $pathFile = 'uploads/' . $name;

                $objReader = PHPExcel_IOFactory::createReaderForFile($pathFile);
                $objReader->setReadDataOnly(true);
                $objPHPExcel = $objReader->load($pathFile);
                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $forecasts = array();

                // VALIDATE MONTH
                $month = $sheet->getCell("C2")->getValue();
                $actualMonth = date("m");

                $isLock = Yii::$app->utilcomponents->isLock();
                if ($isLock) {
                    Yii::$app->session->setFlash('danger', Yii::t("app", "The forecast is locked!"));
                    return $this->render('importOffline', ['model' => $model, 'errors' => NULL]);
                }
                if ($month != $actualMonth) {
                    Yii::$app->session->setFlash('danger', Yii::t("app", "The Excel file is of a wrong month !"));
                    return $this->render('importOffline', ['model' => $model, 'errors' => NULL]);
                }

                for ($row = 7; $row <= $highestRow; $row++) {
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                    $rowData[0][4] = strval($rowData[0][4]);
                    $forecasts[] = $rowData[0];
                }

                $errors = $import->importToDBForecast($forecasts);

                if (count($errors) == 0) {
                    if ($import->save()) {
                        Yii::$app->session->setFlash('success', Yii::t("app", 'The import was successful'));
                    }
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t("app", 'The import has errors'));
                    return $this->render('importOffline', ['model' => $model, 'errors' => $errors]);
                }
            }
        }
        return $this->render('importOffline', ['model' => $model]);
    }

    public function actionOpportunity()
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->validate()) {
                $name = 'Oportunidad.xlsx';
                $model->file->saveAs('uploads/' . $name);

                $import = new Import();
                $import->Name = $name;
                $import->TypeImportId = TypeImport::OPPORTUNITY;

                $pathFile = 'uploads/' . $name;

                $objReader = PHPExcel_IOFactory::createReaderForFile($pathFile);
                $objReader->setReadDataOnly(true);
                $objPHPExcel = $objReader->load($pathFile);
                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                $settings = [];
                for ($row = 7; $row <= $highestRow; $row++) {
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                    unset($rowData[0][12]);
                    reset($rowData[0]);
                    $settings[] = $rowData[0];
                }

                $errors = $import->importToDBOpportunity($settings);

                if (count($errors) == 0) {
                    if ($import->save()) {
                        Yii::$app->session->setFlash('success', Yii::t("app", 'The import was successful'));
                    }
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t("app", 'The import has errors'));
                    return $this->render('importOpportunity', ['model' => $model, 'errors' => $errors]);
                }
            }
        }

        return $this->render('importOpportunity', ['model' => $model]);
    }

    public function actionSellingOut()
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->validate()) {
                $name = 'SellingOut.xlsx';
                $model->file->saveAs('uploads/' . $name);

                $import = new Import();
                $import->Name = $name;
                $import->TypeImportId = TypeImport::SELLING_OUT;

                $pathFile = 'uploads/' . $name;

                $objReader = PHPExcel_IOFactory::createReaderForFile($pathFile);
                $objReader->setReadDataOnly(true);
                $objPHPExcel = $objReader->load($pathFile);
                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                $settings = [];
                for ($row = 7; $row <= $highestRow; $row++) {
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                    unset($rowData[0][12]);
                    reset($rowData[0]);
                    $settings[] = $rowData[0];
                }

                $errors = $import->importToDBSellingOut($settings);

                if (count($errors) == 0) {
                    if ($import->save()) {
                        Yii::$app->session->setFlash('success', Yii::t("app", 'The import was successful'));
                    }
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t("app", 'The import has errors'));
                    return $this->render('importSellingOut', ['model' => $model, 'errors' => $errors]);
                }
            }
        }

        return $this->render('importSellingOut', ['model' => $model]);
    }

    public function actionUnificacionCliente()
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->validate()) {
                $name = 'UnificacionCliente.xlsx';
                $model->file->saveAs('uploads/' . $name);

                $import = new Import();
                $import->Name = $name;
                $import->TypeImportId = TypeImport::UNIFICACION_CLIENTE;

                $pathFile = 'uploads/' . $name;

                $reader = ReaderEntityFactory::createReaderFromFile($pathFile);
                $reader->open($pathFile);

                $clientes = [];
                foreach ($reader->getSheetIterator() as $sheet) {
                    foreach ($sheet->getRowIterator() as $n => $row) {
                        if ($n < 2) {
                            continue;
                        }

                        $clientData = $row->toArray();

                        $clientes[] = [
                            strval($clientData[0]), // '[Country]',
                            intval($clientData[1]), // '[SoldToParty]',
                            strval($clientData[2]), // '[Customer]',
                            strval($clientData[3]), // '[FieldSeller]',
                            strval($clientData[4]), // '[DSM]',
                            intval($clientData[5]), // '[ConversionCode]',
                            strval($clientData[6]), // '[ConversionName]',
                            intval($clientData[7]), //'[CUIT]',
                        ];
                    }
                }

                $reader->close();

                $errors = $import->importToDBUnificacionClientes($clientes);

                if (count($errors) === 0) {
                    if ($import->save()) {
                        Yii::$app->session->setFlash('success', Yii::t("app", 'The import was successful'));
                    }
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t("app", 'The import has errors'));
                    return $this->render('importUnificacionCliente', ['model' => $model, 'errors' => $errors]);
                }
            }
        }

        return $this->render('importUnificacionCliente', ['model' => $model]);
    }

    public function actionUnificacionClienteDownload()
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        $clientes = ClienteUnificado::getArrayOfClientesUnificadosForExcelExport();

        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToBrowser("UnificacionCliente.xlsx");

        $border = (new BorderBuilder())
            ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN)
            ->setBorderTop(Color::BLACK, Border::WIDTH_THIN)
            ->setBorderLeft(Color::BLACK, Border::WIDTH_THIN)
            ->setBorderRight(Color::BLACK, Border::WIDTH_THIN)
            ->build();

        $styleTitle = (new StyleBuilder())
            ->setFontSize(10)
            ->setBackgroundColor("B8D7E4")
            ->setCellAlignment(CellAlignment::CENTER)
            ->setBorder($border)
            ->build();

        $singleRow = WriterEntityFactory::createRowFromArray(["Country", "Sold-To Party", "Customer", "Field Seller", "DSM", "Conversion Code", "Conversion Name", "CUIT"], $styleTitle);
        $writer->addRow($singleRow);

        $styleRow = (new StyleBuilder())
            ->setFontSize(10)
            ->setBackgroundColor("D7ECF4")
            ->setCellAlignment(CellAlignment::LEFT)
            ->setBorder($border)
            ->build();

        $rowsFromValues = array_map(function ($cliente) use ($styleRow) {
            return WriterEntityFactory::createRowFromArray($cliente, $styleRow);
        }, $clientes);
        $writer->addRows($rowsFromValues);

        $writer->close();
    }

    public function actionOfflineForecastMarketingOld()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {

            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->validate()) {
                $name = 'OfflineMarketing.xlsx';
                $model->file->saveAs('uploads/' . $name);
                $pathFile = 'uploads/' . $name;

                $import = new Import();
                $import->Name = $name;
                $import->TypeImportId = TypeImport::FORECAST_MARKETING;

                $reader = ReaderEntityFactory::createReaderFromFile($pathFile);
                $reader->open($pathFile);

                $forecasts = [];
                $allowedKeys = array_merge([0], range(4, 20));
                foreach ($reader->getSheetIterator() as $sheet) {
                    if ($sheet->getIndex() === 0) {
                        foreach ($sheet->getRowIterator() as $n => $row) {
                            if ($n === 1) {
                                continue;
                            }

                            if ($n === 2) {
                                // VALIDATE MONTH
                                $month = $row->getCellAtIndex(2);
                                $actualMonth = date("m");

                                $isLock = Yii::$app->utilcomponents->isMarketingForecastLocked();

                                if ($isLock) {
                                    Yii::$app->session->setFlash('danger', Yii::t("app", "The forecast is locked!"));
                                    return $this->render('importOfflineMarketing', ['model' => $model, 'errors' => NULL]);
                                }

                                if ($month != $actualMonth) {
                                    Yii::$app->session->setFlash('danger', Yii::t("app", "The Excel file is of a wrong month !"));
                                    return $this->render('importOfflineMarketing', ['model' => $model, 'errors' => NULL]);
                                }

                                continue;
                            }

                            if ($n < 6) {
                                continue;
                            }

                            $forecasts[] = array_intersect_key($row->toArray(), array_flip($allowedKeys));
                        }
                        break;
                    }
                }

                $reader->close();

                $errors = $import->importToDBForecastMarketing($forecasts, Campaign::getActualCampaign()->CampaignId);

                if (count($errors) == 0) {
                    if ($import->save()) {
                        Yii::$app->session->setFlash('success', Yii::t("app", 'The import was successful'));
                    }
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t("app", 'The import has errors'));
                    return $this->render('importOfflineMarketing', ['model' => $model, 'errors' => $errors]);
                }
            }
        }

        return $this->render('importOfflineMarketing', ['model' => $model]);
    }

    public function actionOfflineForecastMarketing()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {

            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->validate()) {
                $name = 'OfflineMarketing2.xlsx';
                $model->file->saveAs('uploads/' . $name);
                $pathFile = 'uploads/' . $name;

                $import = new Import();
                $import->Name = $name;
                $import->TypeImportId = TypeImport::FORECAST_MARKETING;

                $reader = ReaderEntityFactory::createReaderFromFile($pathFile);
                $reader->open($pathFile);

                $forecasts = [];
                $allowedKeys = array_merge([1], [4], [6], range(8, 24));
                foreach ($reader->getSheetIterator() as $sheet) {
                    if ($sheet->getIndex() === 0) {
                        foreach ($sheet->getRowIterator() as $n => $row) {
                            if ($n === 1) {
                                continue;
                            }

                            if ($n === 2) {
                                // VALIDATE MONTH
                                $month = $row->getCellAtIndex(2);
                                $actualMonth = date("m");

                                $isLock = Yii::$app->utilcomponents->isMarketingForecastLocked();

                                if ($isLock) {
                                    Yii::$app->session->setFlash('danger', Yii::t("app", "The forecast is locked!"));
                                    return $this->render('importOfflineMarketing', ['model' => $model, 'errors' => NULL]);
                                }

                                if ($month != $actualMonth) {
                                    Yii::$app->session->setFlash('danger', Yii::t("app", "The Excel file is of a wrong month !"));
                                    return $this->render('importOfflineMarketing', ['model' => $model, 'errors' => NULL]);
                                }

                                continue;
                            }

                            if ($n < 6) {
                                continue;
                            }

                            $forecast = array_intersect_key($row->toArray(), array_flip($allowedKeys));
                            // Si es semilla, nulleo el GMID (si no queda con valor 0)
                            if ($forecast[6] === "") {
                                $forecast[6] = null;
                            }

                            $forecasts[] = $forecast;
                        }
                        break;
                    }
                }

                $reader->close();

                $errors = $import->importToDBForecastMarketing($forecasts, Campaign::getActualCampaign()->CampaignId);

                if (count($errors) == 0) {
                    if ($import->save()) {
                        Yii::$app->session->setFlash('success', Yii::t("app", 'The import was successful'));
                    }
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t("app", 'The import has errors'));
                    return $this->render('importOfflineMarketing', ['model' => $model, 'errors' => $errors]);
                }
            }
        }

        return $this->render('importOfflineMarketing', ['model' => $model]);
    }

    public function actionAssociationPmProduct()
    {
        $model = new UploadForm();

        $import = new Import();

        if (Yii::$app->request->isPost) {

            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->validate()) {
                $name = 'AssociationPmProduct.xlsx';
                $model->file->saveAs('uploads/' . $name);

                $import->Name = $name;
                $import->TypeImportId = TypeImport::ASSOCIATION_PM_PRODUCT;

                $pathFile = 'uploads/' . $name;

                $reader = ReaderEntityFactory::createReaderFromFile($pathFile);
                $reader->open($pathFile);

                $asociaciones = [];
                foreach ($reader->getSheetIterator() as $sheet) {
                    foreach ($sheet->getRowIterator() as $n => $row) {
                        if ($n < 2) {
                            continue;
                        }

                        $data = $row->toArray();

                        $asociaciones[] = [
                            $data[1], // Trade product
                            $data[3], // Gmid
                            $data[5], // PM Username
                        ];
                    }
                }

                $reader->close();

                $errors = $import->importToDBAssociationPmProduct($asociaciones);

                if (count($errors) === 0) {
                    if ($import->save()) {
                        Yii::$app->session->setFlash('success', Yii::t("app", 'The import was successful'));
                    }
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t("app", 'The import has errors'));
                    return $this->render('importAssociationPmProduct', ['model' => $model, 'errors' => $errors]);
                }
            }
        }

        return $this->render('importAssociationPmProduct', ['model' => $model]);
    }

    public function actionAssociationPmProductDownload()
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        $relations = PmProduct::getArrayForExcelExport();

        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToBrowser("Asociacion Productos - PM.xlsx");

        $border = (new BorderBuilder())
            ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN)
            ->setBorderTop(Color::BLACK, Border::WIDTH_THIN)
            ->setBorderLeft(Color::BLACK, Border::WIDTH_THIN)
            ->setBorderRight(Color::BLACK, Border::WIDTH_THIN)
            ->build();

        $styleTitle = (new StyleBuilder())
            ->setFontSize(10)
            ->setBackgroundColor("B8D7E4")
            ->setCellAlignment(CellAlignment::LEFT)
            ->setBorder($border)
            ->build();

        $singleRow = WriterEntityFactory::createRowFromArray(["Country", "Trade Product", "", "GMID", "", "Product Manager Username"], $styleTitle);
        $writer->addRow($singleRow);

        $styleRow = (new StyleBuilder())
            ->setFontSize(10)
            ->setBackgroundColor("D7ECF4")
            ->setCellAlignment(CellAlignment::LEFT)
            ->setBorder($border)
            ->build();

        foreach ($relations as $relation) {
            $writer->addRow(WriterEntityFactory::createRowFromArray($relation, $styleRow));
        }

        $writer->close();
    }

    public function actionCustomerMarketing()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->validate()) {
                $name = 'Customer Marketing.xlsx';
                $model->file->saveAs('uploads/' . $name);
                $pathFile = 'uploads/' . $name;

                $import = new Import();
                $import->Name = $name;
                $import->TypeImportId = TypeImport::CLIENT_MARKETING;

                $reader = ReaderEntityFactory::createReaderFromFile($pathFile);
                $reader->open($pathFile);

                $customers = [];
                $allowedKeys = [0, 1, 2, 3];
                foreach ($reader->getSheetIterator() as $sheet) {
                    foreach ($sheet->getRowIterator() as $n => $row) {
                        if ($n < 2) {
                            continue;
                        }
                        $customers[] = array_intersect_key($row->toArray(), array_flip($allowedKeys));
                    }
                }

                $reader->close();

                $errors = $import->importToDBClientsMarketing($customers);

                if (count($errors) > 0) {
                    Yii::$app->session->setFlash('danger', Yii::t("app", 'The import has errors'));
                    return $this->render('importClientMarketing', ['model' => $model, 'errors' => $errors]);
                }

                if ($import->save()) {
                    Yii::$app->session->setFlash('success', Yii::t("app", 'The import was successful'));
                }
            }
        }

        return $this->render('importClientMarketing', ['model' => $model]);
    }
}
