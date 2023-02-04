<?php

namespace frontend\controllers;

use Yii;
use common\models\UploadForm;
use common\models\Import;
use yii\web\UploadedFile;

/**
 * ImportController implements the CRUD actions for Client model.
 */
class ImportController extends \common\components\controllers\CustomController {

     public function actionCyo() {
        $model = new UploadForm();
        $import = new Import();
        
        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) 
            {
                $name = 'CyO.xls';
                $model->file->saveAs('uploads/' . $name);
                $import->Name = $name;
                $import->TypeImportId = \common\models\TypeImport::CyO;
                $pathFile = 'uploads/' . $name;

                $objReader = \PHPExcel_IOFactory::createReaderForFile($pathFile);
                $objReader->setReadDataOnly(true);
                $objPHPExcel = $objReader->load($pathFile);
                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $cyos = array();
                for ($row = 22; $row <= $highestRow; $row++) {
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);     
                    $cyos[] = ['ClientId'=>$rowData[0][3],'GmidId'=>$rowData[0][6],'CampaignId'=>  \common\models\Campaign::getActualCampaign()->CampaignId,'InventoryBalance'=>$rowData[0][9]];                    
                }

                $errors = $import->importToDBCyO($cyos);

                if (count($errors) == 0) 
                {
                    if ($import->save()) 
                    {
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
    
    
    public function actionSetting() {
        
        ini_set("memory_limit", - 1);
        ini_set("max_execution_time", "9200");
        $model = new UploadForm();

        $import = new Import();
        
        
        if (Yii::$app->request->isPost) {
            $isLock = \common\models\Campaign::getActualCampaign()->isSettingActive();
             if ($isLock) {
                    Yii::$app->session->setFlash('danger', Yii::t("app", "The plan is locked because the setting dates  is different to today!"));
                    return $this->render('importSetting', ['model' => $model, 'errors' => NULL]);
                }
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                $name = 'Setting.xlsx';
                $model->file->saveAs('uploads/' . $name);
                $import->Name = $name;
                $import->TypeImportId = \common\models\TypeImport::SETTING;
                $pathFile = 'uploads/' . $name;

                $objReader = \PHPExcel_IOFactory::createReaderForFile($pathFile);
                $objReader->setReadDataOnly(true);
                $objPHPExcel = $objReader->load($pathFile);
                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $settings = array();
                
                for ($row = 7; $row <= $highestRow; $row++) {
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                    $settings[] = $rowData[0];
                }

                $errors = $import->importToDBPlan($settings);

                if (count($errors) == 0) {
                    if ($import->save()) {
                        Yii::$app->session->setFlash('success', Yii::t("app", 'The import was successful'));
                    }
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t("app", 'The import has errors'));
                    return $this->render('importSettingPlan', ['model' => $model, 'errors' => $errors]);
                }
            }
        }
        return $this->render('importSetting', ['model' => $model]);
    }
    
    
    public function actionCustomer() {
        ini_set("memory_limit", - 1);
        ini_set("max_execution_time", "9200");
        $model = new UploadForm();

        $import = new Import();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                $name = 'Customer.xlsx';
                $model->file->saveAs('uploads/' . $name);
                $import->Name = $name;
                $import->TypeImportId = \common\models\TypeImport::CLIENT;
                $pathFile = 'uploads/' . $name;

                $objReader = \PHPExcel_IOFactory::createReaderForFile($pathFile);
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

    public function actionProduct() {
        ini_set("memory_limit", - 1);
        ini_set("max_execution_time", "9200");

        $model = new UploadForm();

        $import = new Import();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                $name = 'Productos.xlsx';
                $model->file->saveAs('uploads/' . $name);
                $import->Name = $name;
                $import->TypeImportId = \common\models\TypeImport::PRODUCT;

                $pathFile = 'uploads/' . $name;

                $objReader = \PHPExcel_IOFactory::createReaderForFile($pathFile);
                $objReader->setReadDataOnly(true);
                $objPHPExcel = $objReader->load($pathFile);
                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $products = array();
                for ($row = 2; $row <= $highestRow; $row++) {
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                    $products[] = $rowData[0];
                }
                $errors = $import->importToDBProducts($products);

                if (count($errors) == 0) {
                    if ($import->save()) {
                        Yii::$app->session->setFlash('success', Yii::t("app", 'The import was successful'));
                    }
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t("app", 'The import has errors'));
                    return $this->render('importProduct', ['model' => $model, 'errors' => $errors]);
                }
            }
        }

        return $this->render('importProduct', ['model' => $model]);
    }

    public function actionSale() {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");
        $model = new UploadForm();

        $import = new Import();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                $name = 'Ventas.xlsx';
                $model->file->saveAs('uploads/' . $name);
                $import->Name = $name;
                $import->TypeImportId = \common\models\TypeImport::SALE;

                $pathFile = 'uploads/' . $name;

                $objReader = \PHPExcel_IOFactory::createReaderForFile($pathFile);
                $objReader->setReadDataOnly(true);
                $objPHPExcel = $objReader->load($pathFile);
                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $sales = array();
                for ($row = 3; $row <= $highestRow; $row++) {
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                    $sales[] = $rowData[0];
                }

                $errors = $import->importToDBSales($sales);

                if (count($errors) == 0) {
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

    
    
    
    public function actionOfflinePlan() {
        
        ini_set("memory_limit", - 1);
        ini_set("max_execution_time", "9200");
        
        $model = new UploadForm();

        $import = new Import();
        
        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                $name = 'OfflinePlan.xlsx';
                $model->file->saveAs('uploads/' . $name);
                $import->Name = $name;
                $import->TypeImportId = \common\models\TypeImport::PLAN;
                $pathFile = 'uploads/' . $name;

                $objReader = \PHPExcel_IOFactory::createReaderForFile($pathFile);
                $objReader->setReadDataOnly(true);
                $objPHPExcel = $objReader->load($pathFile);
                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $forecasts = array();

                // VALIDATE MONTH 
                $month = $sheet->getCell("C2")->getValue();
                $actualMonth = date("m");

                $isLock = \Yii::$app->utilcomponents->isPlanEnable();
                if ($isLock) 
                {
                    Yii::$app->session->setFlash('danger', Yii::t("app", "The Plan is locked!"));
                    return $this->render('importPlanOffline', ['model' => $model, 'errors' => NULL]);
                }
                if ($month != $actualMonth) {
                    Yii::$app->session->setFlash('danger', Yii::t("app", "The Excel file is of a wrong month !"));
                    return $this->render('importPlanOffline', ['model' => $model, 'errors' => NULL]);
                }

                for ($row = 7; $row <= $highestRow; $row++) {
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                    $items[] = $rowData[0];
                }

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
    
    public function actionOffline() {
        $model = new UploadForm();

        $import = new Import();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                $name = 'Offline.xlsx';
                $model->file->saveAs('uploads/' . $name);
                $import->Name = $name;
                $import->TypeImportId = \common\models\TypeImport::OFFLINE;
                $pathFile = 'uploads/' . $name;

                $objReader = \PHPExcel_IOFactory::createReaderForFile($pathFile);
                $objReader->setReadDataOnly(true);
                $objPHPExcel = $objReader->load($pathFile);
                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $forecasts = array();

                // VALIDATE MONTH 
                $month = $sheet->getCell("C2")->getValue();
                $actualMonth = date("m");

                $isLock = \Yii::$app->utilcomponents->isLock();
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

}
