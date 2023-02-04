<?php

namespace frontend\controllers;

use Yii;
use common\models\Audit;
use common\models\AuditSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuditController implements the CRUD actions for Audit model.
 */
class AuditController extends \common\components\controllers\CustomController {

    /**
     * Lists all Audit models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new AuditSearch();
        if (isset(Yii::$app->request->queryParams['export'])) {
            $searchModel->scenario = AuditSearch::SCENE_EXPORT;

            if ($searchModel->load(Yii::$app->request->queryParams) && $searchModel->validate()) {
                $items = $searchModel->export();

                // Read the file
                $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
                $objPHPExcel = $objReader->load('templates/Audit.xlsx');
                $objPHPExcel->setActiveSheetIndex(0);
                $objSheet = $objPHPExcel->getActiveSheet();
                $title = 'Auditoria';
                // WRITE PRODUCTS        
                $row = 7;
                foreach ($items as $key => $item) {
                    $charCol = 65;
                    $objSheet->SetCellValue(chr($charCol++) . $row, $item["AuditId"]);
                    $objSheet->SetCellValue(chr($charCol++) . $row, $item["TypeAudit"]);
                    $objSheet->SetCellValue(chr($charCol++) . $row, $item["User"]);
                    $objSheet->SetCellValue(chr($charCol++) . $row, $item["Client"]);
                    $objSheet->SetCellValue(chr($charCol++) . $row, $item["Date"]);

                    if ($row % 2 == 0)
                        $objSheet->getStyle("A$row:W$row")->applyFromArray(
                                array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                                        'color' => array('rgb' => 'F2F2F2')
                                    )
                        ));
                    $row++;
                }
                // EXPORT EXCEL TO IMPORT    
                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header("Content-Disposition: attachment;filename=\"$title.xlsx\"");
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');
                // If you're serving to IE over SSL, then the following may be needed
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('php://output');
            }
        }
        else {

            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Displays a single Audit model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Audit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Audit();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->AuditId]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Audit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->AuditId]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Audit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Audit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Audit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Audit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
