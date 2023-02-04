<?php

namespace frontend\controllers;

use common\components\controllers\CustomController;
use common\models\Import;
use common\models\sap\ManualImport;
use common\models\TypeImport;
use common\services\AutoSapImportDasCyoService;
use common\services\AutoSapImportDasSalesService;
use common\services\AutoSapImportDupontCyoService;
use common\services\AutoSapImportDupontSalesService;
use common\services\NullAutoServiceImportService;
use Exception;
use Yii;
use yii\web\Response;
use yii\web\UploadedFile;

class CheckAutoSapImportController extends CustomController
{
    public function actionIndex()
    {
        $manualModelImport = new ManualImport();

        if (Yii::$app->request->isPost) {
            $manualModelImport->load(Yii::$app->request->post());

            $manualModelImport->file = UploadedFile::getInstance($manualModelImport, 'file');

            try {
                $importService = $this->createAutomaticImportService($manualModelImport);
                $errors = $importService->doImport($manualModelImport->file->tempName);
            } catch (Exception $e) {
                Yii::$app->session->setFlash('danger', $e->getMessage());
                return $this->redirect(['index']);
            }

            if (empty($errors)) {
                Yii::$app->session->setFlash('success', "La importación manual finalizó correctamente");
            } else {
                Yii::$app->session->setFlash('danger', "La importación manual no finalizó correctamente");
            }

            return $this->redirect(['index']);
        }

        return $this->render('index', [
            'imports' => Import::getAutomaticSapImports(),
            'manualModelImport' => $manualModelImport,
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws Exception
     */
    public function actionDownload($id)
    {
        $import = Import::findOne($id);

        switch ($import->typeImport->TypeImportId) {
            case TypeImport::AUTOMATIC_DAS_SALE:
                $importedFilesFolder = AutoSapImportDasSalesService::ARCHIVOS_IMPORTADOS_FOLDER_PATH;
                break;
            case TypeImport::AUTOMATIC_DAS_CYO:
                $importedFilesFolder = AutoSapImportDasCyoService::ARCHIVOS_IMPORTADOS_FOLDER_PATH;
                break;
            case TypeImport::AUTOMATIC_DUPONT_SALE:
                $importedFilesFolder = AutoSapImportDupontSalesService::ARCHIVOS_IMPORTADOS_FOLDER_PATH;
                break;
            case TypeImport::AUTOMATIC_DUPONT_CYO:
                $importedFilesFolder = AutoSapImportDupontCyoService::ARCHIVOS_IMPORTADOS_FOLDER_PATH;
                break;
            default:
                throw new Exception("Type of this import not found!");
        }

        $path = Yii::getAlias($importedFilesFolder . DIRECTORY_SEPARATOR . $import['Name']);

        if (!file_exists($path)) {
            throw new Exception("File path not found!");
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($path));
        readfile($path);

        return $this->redirect(['index']);
    }

    public function actionErrors($id)
    {
        $import = Import::findOne($id);

        $validTypeImports = TypeImport::getValidTypeImportsForSapAutomaticImport();

        return $this->render('errors', [
            'import' => $import,
            'errors' => $import->getImportErrors()->asArray()->all(),
            'typeImportDescription' => $validTypeImports[$import->typeImport->TypeImportId],
        ]);
    }

    public function actionRunAgain()
    {
        try {
            $errors = (new AutoSapImportDasCyoService)->doImport();
            if (count($errors) > 0) {
                Yii::$app->session->setFlash('danger', 'Se produjo un error inesperado corriendo la importación automática...');
                return $this->redirect('/check-auto-sap-import');
            }

            $errors = (new AutoSapImportDasSalesService)->doImport();
            if (count($errors) > 0) {
                Yii::$app->session->setFlash('danger', 'Se produjo un error inesperado corriendo la importación automática...');
                return $this->redirect('/check-auto-sap-import');
            }

            $errors = (new AutoSapImportDupontCyoService)->doImport();
            if (count($errors) > 0) {
                Yii::$app->session->setFlash('danger', 'Se produjo un error inesperado corriendo la importación automática...');
                return $this->redirect('/check-auto-sap-import');
            }

            $errors = (new AutoSapImportDupontSalesService)->doImport();
            if (count($errors) > 0) {
                Yii::$app->session->setFlash('danger', 'Se produjo un error inesperado corriendo la importación automática...');
                return $this->redirect('/check-auto-sap-import');
            }

            Yii::$app->session->setFlash('success', Yii::t("app", 'The import was successful'));
            return $this->redirect('/check-auto-sap-import');
        } catch (Exception $e) {
            Yii::$app->session->setFlash('danger', 'Se produjo un error inesperado corriendo la importación automática...');
            return $this->redirect('/check-auto-sap-import');
        }
    }

    private function createAutomaticImportService(ManualImport $manualModelImport)
    {
        if ($manualModelImport->tipo === ManualImport::TIPO_VENTAS) {
            if ($manualModelImport->origen === ManualImport::ORIGEN_DAS) {
                return new AutoSapImportDasSalesService();
            }
            if ($manualModelImport->origen === ManualImport::ORIGEN_DUPONT) {
                return new AutoSapImportDupontSalesService();
            }
        }

        if ($manualModelImport->tipo === ManualImport::TIPO_CYOS) {
            if ($manualModelImport->origen === ManualImport::ORIGEN_DAS) {
                return new AutoSapImportDasCyoService();
            }
            if ($manualModelImport->origen === ManualImport::ORIGEN_DUPONT) {
                return new AutoSapImportDupontCyoService();
            }
        }

        return new NullAutoServiceImportService();
    }
}
