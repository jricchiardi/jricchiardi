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
use common\services\AutoSapImportDupontOpenOrdersService;
use common\services\AutoSapImportDasOpenOrdersService;
use common\services\AutoSapImportDelivOpenOrdersService;
use common\services\AutoSapImportDupontFcNoContService;
use common\services\AutoSapImportDasFcNoContService;
use common\services\AutoSapImportDasShortFcNoContService;
use common\services\AutoSapImportDupontShortFcNoContService;
use common\services\AutoSapImportCredOpenOrdersService;
use common\services\AutoSapImportDupontDespNoFcService;
use common\services\AutoSapImportDasDespNoFcService;
use common\services\AutoSapImportFcastIBPService;
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
            $movedFileName = \Yii::$app->basePath.'/uploads/' . md5(time()) . '-' . $manualModelImport->file->name;
            $manualModelImport->file->saveAs($movedFileName);

            try {
                $importService = $this->createAutomaticImportService($manualModelImport);
                $errors = $importService->doImport($movedFileName);
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
            case TypeImport::AUTOMATIC_DUPONT_OPEN_ORDERS:
                $importedFilesFolder = AutoSapImportDupontOpenOrdersService::ARCHIVOS_IMPORTADOS_FOLDER_PATH;
                break;
			case TypeImport::AUTOMATIC_DAS_OPEN_ORDERS:
                $importedFilesFolder = AutoSapImportDasOpenOrdersService::ARCHIVOS_IMPORTADOS_FOLDER_PATH;
                break;
			case TypeImport::AUTOMATIC_DELIV_OPEN_ORDERS:
                $importedFilesFolder = AutoSapImportDelivOpenOrdersService::ARCHIVOS_IMPORTADOS_FOLDER_PATH;
                break;
			case TypeImport::AUTOMATIC_DUPONT_FC_NOCONT:
                $importedFilesFolder = AutoSapImportDupontFcNoContService::ARCHIVOS_IMPORTADOS_FOLDER_PATH;
                break;
			case TypeImport::AUTOMATIC_DAS_FC_NOCONT:
                $importedFilesFolder = AutoSapImportDasFcNoContService::ARCHIVOS_IMPORTADOS_FOLDER_PATH;
                break;
			case TypeImport::AUTOMATIC_DUPONT_SHORT_FC_NOCONT:
                $importedFilesFolder = AutoSapImportDupontShortFcNoContService::ARCHIVOS_IMPORTADOS_FOLDER_PATH;
                break;
			case TypeImport::AUTOMATIC_DAS_SHORT_FC_NOCONT:
                $importedFilesFolder = AutoSapImportDasShortFcNoContService::ARCHIVOS_IMPORTADOS_FOLDER_PATH;
                break;
			case TypeImport::AUTOMATIC_CRED_OPEN_ORDERS:
                $importedFilesFolder = AutoSapImportCredOpenOrdersService::ARCHIVOS_IMPORTADOS_FOLDER_PATH;
                break;
			case TypeImport::AUTOMATIC_DUPONT_DESP_NOFC:
                $importedFilesFolder = AutoSapImportDupontDespNoFcService::ARCHIVOS_IMPORTADOS_FOLDER_PATH;
                break;
			case TypeImport::AUTOMATIC_DAS_DESP_NOFC:
                $importedFilesFolder = AutoSapImportDasDespNoFcService::ARCHIVOS_IMPORTADOS_FOLDER_PATH;
                break;
			
			case TypeImport::AUTOMATIC_FCASTIBP:
                $importedFilesFolder = AutoSapImportFcastIBPService::ARCHIVOS_IMPORTADOS_FOLDER_PATH;
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
                Yii::$app->session->setFlash('danger', '[AutoSapImportDasCyoService] Se produjo un error inesperado corriendo la importación automática...');
                return $this->redirect(['/check-auto-sap-import']);
            }

            $errors = (new AutoSapImportDasSalesService)->doImport();
            if (count($errors) > 0) {
                Yii::$app->session->setFlash('danger', '[AutoSapImportDasSalesService] Se produjo un error inesperado corriendo la importación automática...');
                return $this->redirect(['/check-auto-sap-import']);
            }

            $errors = (new AutoSapImportDupontCyoService)->doImport();
            if (count($errors) > 0) {
                Yii::$app->session->setFlash('danger', '[AutoSapImportDupontCyoService] Se produjo un error inesperado corriendo la importación automática...');
                return $this->redirect(['/check-auto-sap-import']);
            }
            $errors = (new AutoSapImportDupontSalesService)->doImport();
            if (count($errors) > 0) {
                Yii::$app->session->setFlash('danger', '[AutoSapImportDupontSalesService] Se produjo un error inesperado corriendo la importación automática...');
                return $this->redirect(['/check-auto-sap-import']);
            }
            /*
            $errors = (new AutoSapImportDupontOpenOrdersService)->doImport();
            if (count($errors) > 0) {
                Yii::$app->session->setFlash('danger', '[AutoSapImportDupontOpenOrdersService] Se produjo un error inesperado corriendo la importación automática...');
                return $this->redirect(['/check-auto-sap-import']);
            }
			
			$errors = (new AutoSapImportDasOpenOrdersService)->doImport();
            if (count($errors) > 0) {
                Yii::$app->session->setFlash('danger', '[AutoSapImportDasOpenOrdersService] Se produjo un error inesperado corriendo la importación automática...');
                return $this->redirect(['/check-auto-sap-import']);
            }
			
			$errors = (new AutoSapImportDelivOpenOrdersService)->doImport();
            if (count($errors) > 0) {
                Yii::$app->session->setFlash('danger', '[AutoSapImportDelivOpenOrdersService] Se produjo un error inesperado corriendo la importación automática...');
                return $this->redirect(['/check-auto-sap-import']);
            }
			
			$errors = (new AutoSapImportCredOpenOrdersService)->doImport();
            if (count($errors) > 0) {
                Yii::$app->session->setFlash('danger', '[AutoSapImportCredOpenOrdersService] Se produjo un error inesperado corriendo la importación automática...');
                return $this->redirect(['/check-auto-sap-import']);
            }
			
			$errors = (new AutoSapImportDupontFcNoContService)->doImport();
            if (count($errors) > 0) {
                Yii::$app->session->setFlash('danger', '[AutoSapImportDupontFcNoContService] Se produjo un error inesperado corriendo la importación automática...');
                return $this->redirect(['/check-auto-sap-import']);
            }
			
			$errors = (new AutoSapImportDasFcNoContService)->doImport();
            if (count($errors) > 0) {
                Yii::$app->session->setFlash('danger', '[AutoSapImportDasFcNoContService] Se produjo un error inesperado corriendo la importación automática...');
                return $this->redirect(['/check-auto-sap-import']);
            }
			
			$errors = (new AutoSapImportDupontShortFcNoContService)->doImport();
            if (count($errors) > 0) {
                Yii::$app->session->setFlash('danger', '[AutoSapImportDupontShortFcNoContService] Se produjo un error inesperado corriendo la importación automática...');
                return $this->redirect(['/check-auto-sap-import']);
            }
			
			$errors = (new AutoSapImportDasShortFcNoContService)->doImport();
            if (count($errors) > 0) {
                Yii::$app->session->setFlash('danger', '[AutoSapImportDasShortFcNoContService] Se produjo un error inesperado corriendo la importación automática...');
                return $this->redirect(['/check-auto-sap-import']);
            }
			
			$errors = (new AutoSapImportDupontDespNoFcService)->doImport();
            if (count($errors) > 0) {
                Yii::$app->session->setFlash('danger', '[AutoSapImportDupontDespNoFcService] Se produjo un error inesperado corriendo la importación automática...');
                return $this->redirect(['/check-auto-sap-import']);
            }
			
			$errors = (new AutoSapImportDasDespNoFcService)->doImport();
            if (count($errors) > 0) {
                Yii::$app->session->setFlash('danger', '[AutoSapImportDasDespNoFcService] Se produjo un error inesperado corriendo la importación automática...');
                return $this->redirect(['/check-auto-sap-import']);
            }
			
			$errors = (new AutoSapImportFcastIBPService)->doImport();
            if (count($errors) > 0) {
                Yii::$app->session->setFlash('danger', '[AutoSapImportFcastIBPService] Se produjo un error inesperado corriendo la importación automática...');
                return $this->redirect(['/check-auto-sap-import']);
            }
			
			*/
            Yii::$app->session->setFlash('success', Yii::t("app", 'The import was successful'));
            return $this->redirect(['/check-auto-sap-import']);
        } catch (Exception $e) {
            Yii::$app->session->setFlash('danger', 'Se produjo un error inesperado corriendo la importación automática ('.$e->getMessage().')...');
            return $this->redirect(['/check-auto-sap-import']);
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
        
        if ($manualModelImport->tipo === ManualImport::TIPO_OPEN_ORDERS) {
            if ($manualModelImport->origen === ManualImport::ORIGEN_DAS) {
                return new AutoSapImportDasOpenOrdersService();
            }
            if ($manualModelImport->origen === ManualImport::ORIGEN_DUPONT) {
                return new AutoSapImportDupontOpenOrdersService();
            }
			if ($manualModelImport->origen === ManualImport::ORIGEN_DELIVORDS) {
                return new AutoSapImportDelivOpenOrdersService();
            }
			if ($manualModelImport->origen === ManualImport::ORIGEN_ORDERS_CRED) {
                return new AutoSapImportCredOpenOrdersService();
            }
			
		}	
		if ($manualModelImport->tipo === ManualImport::TIPO_FC_NOCONT) {
            if ($manualModelImport->origen === ManualImport::ORIGEN_DAS) {
                return new AutoSapImportDasFcNoContService();
            }
            if ($manualModelImport->origen === ManualImport::ORIGEN_DUPONT) {
                return new AutoSapImportDupontFcNoContService();
            }
			if ($manualModelImport->origen === ManualImport::ORIGEN_DAS_SHORT) {
                return new AutoSapImportDasShortFcNoContService();
            }
            if ($manualModelImport->origen === ManualImport::ORIGEN_DUPONT_SHORT) {
                return new AutoSapImportDupontShortFcNoContService();
            }	
        }
		
		if ($manualModelImport->tipo === ManualImport::TIPO_DESP_NOFC) {
            if ($manualModelImport->origen === ManualImport::ORIGEN_DAS) {
                return new AutoSapImportDasDespNoFcService();
            }
            if ($manualModelImport->origen === ManualImport::ORIGEN_DUPONT) {
                return new AutoSapImportDupontDespNoFcService();
            }
		}
			
		if ($manualModelImport->tipo === ManualImport::TIPO_FCASTIBP) {
            if ($manualModelImport->origen === ManualImport::ORIGEN_FCASTIBP) {
                return new AutoSapImportFcastIBPService();
            }	
		}
         

        return new NullAutoServiceImportService();
		
	}

    public function actionRunSis()
    {
        $running = Yii::$app->db->createCommand('SELECT value FROM sis_report_metadata WHERE code = \'is_running\'')->queryAll();

        if(count($running)>0){
            Yii::$app->session->setFlash('warning', 'El reporte SIS ya se encuentra en ejecucion...');
            return $this->redirect(['/check-auto-sap-import']);
        }

        Yii::$app->db->on('SP_Run_Sis_Report', function ($event) {
            try {
                Yii::$app->db->createCommand('EXEC [SP_Run_Sis_Report]')->execute();
                Yii::$app->session->setFlash('success', Yii::t("app", 'El reporte SIS ha sido actualizado'));
            } catch (Exception $e) {
                Yii::$app->session->setFlash('danger', 'Se produjo un error inesperado ejecutando el reporte SIS...');
                Yii::$app->db->createCommand('DELETE FROM sis_report_metadata WHERE code = \'is_running\'')->execute();
            }
        });
        Yii::$app->db->trigger('SP_Run_Sis_Report');
        Yii::$app->session->setFlash('warning', 'El reporte SIS ya se encuentra en ejecucion...');
        return $this->redirect(['/check-auto-sap-import']);
    }
	
	public function actionRunVaciadoOa()
    {
       try {
            Yii::$app->db->createCommand('DELETE FROM OPENORDERS')->execute();
			Yii::$app->db->createCommand('DELETE FROM TEMP_OPENORDERS')->execute();
            Yii::$app->session->setFlash('success', Yii::t("app", 'La tabla fue vaciada'));
            return $this->redirect(['/check-auto-sap-import']);
        } catch (Exception $e) {
            Yii::$app->session->setFlash('danger', 'Se produjo un error inesperado');
            return $this->redirect(['/check-auto-sap-import']);
        }
    }
	
	public function actionRunVaciadoFcNoCont()
    {
       try {
            Yii::$app->db->createCommand('DELETE FROM FCNOCONT')->execute();
			Yii::$app->db->createCommand('DELETE FROM TEMP_FCNOCONT')->execute();
            Yii::$app->session->setFlash('success', Yii::t("app", 'La tabla fue vaciada'));
            return $this->redirect(['/check-auto-sap-import']);
        } catch (Exception $e) {
            Yii::$app->session->setFlash('danger', 'Se produjo un error inesperado');
            return $this->redirect(['/check-auto-sap-import']);
        }
	}
	
	public function actionRunVaciadoDespNoFc()
    {
       try {
            Yii::$app->db->createCommand('DELETE FROM DESPNOFC')->execute();
			Yii::$app->db->createCommand('DELETE FROM TEMP_DESPNOFC')->execute();
            Yii::$app->session->setFlash('success', Yii::t("app", 'La tabla fue vaciada'));
            return $this->redirect(['/check-auto-sap-import']);
        } catch (Exception $e) {
            Yii::$app->session->setFlash('danger', 'Se produjo un error inesperado');
            return $this->redirect(['/check-auto-sap-import']);
        }
	}
}
