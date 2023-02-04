<?php

namespace frontend\controllers;

use Yii;
use common\models\SnapshotForecast;
use common\models\SnapshotForecastSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SnapshotForecastController implements the CRUD actions for SnapshotForecast model.
 */
class SnapshotForecastController extends \common\components\controllers\CustomController {

    /**
     * Lists all SnapshotForecast models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SnapshotForecastSearch();
        if (\Yii::$app->user->can(\common\models\AuthItem::ROLE_RSM)) {
            $searchModel->RsmId = \Yii::$app->user->identity->UserId;
        }

        if (\Yii::$app->user->can(\common\models\AuthItem::ROLE_DSM)) {
            $searchModel->DsmId = \Yii::$app->user->identity->UserId;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all SnapshotForecast models.
     * @return mixed
     */
    public function actionComparativeReport() {

        if ((int) date("m") < 3)
            return false;

        $reportModel = new \common\models\ExportComparative();

        $reportModel->CampaignId = \common\models\Campaign::getActualCampaign()->CampaignId;

        if (\Yii::$app->user->can(\common\models\AuthItem::ROLE_RSM)) {
            $reportModel->RsmId = \Yii::$app->user->identity->UserId;
        }

        if (\Yii::$app->user->can(\common\models\AuthItem::ROLE_DSM)) {
            $reportModel->DsmId = \Yii::$app->user->identity->UserId;
        }

        $rows = $reportModel->getReport(\Yii::$app->request->queryParams);


        $title = Yii::t("app", "Comparative Report (") . date("Y-m-d") . ")";

        // Read the file
        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load('templates/Comparative_Report.xlsx');
        $objPHPExcel->getProperties()->setTitle($title);
        $objSheet = $objPHPExcel->getActiveSheet();


        $beforeMonth = \Yii::$app->utilcomponents->getMonth((int) date("m") - 2);
        $actualMonth = \Yii::$app->utilcomponents->getMonth((int) date("m") - 1);

        $objSheet->SetCellValue('N2', \Yii::$app->utilcomponents->getMonthES((int) date("m") - 2));
        $objSheet->SetCellValue('O2', \Yii::$app->utilcomponents->getMonthES((int) date("m") - 1));

        // WRITE PRODUCTS        
        $row = 3;
        foreach ($rows as $key => $item) {
            $charCol = 65;
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Country"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["DSM"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["NameDSM"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Seller"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["NameSeller"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["ClientId"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Client"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["TradeProductId"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["TradeProduct"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["PerformanceCenterId"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["PerformanceCenter"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["GmidId"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Gmid"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item[$beforeMonth]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item[$actualMonth]);
            $objSheet->SetCellValue(chr($charCol++) . $row, '=(O' . $row . '- N' . $row . ')');

            if ($row % 2 == 0)
                $objSheet->getStyle("A$row:V$row")->applyFromArray(
                        array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'F2F2F2')
                            )
                ));

            $row++;
        }
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

    /**
     * Displays a single SnapshotForecast model.
     * @param integer $CampaignId
     * @param integer $ClientProductId
     * @return mixed
     */
    public function actionView($CampaignId, $ClientProductId) {
        return $this->render('view', [
                    'model' => $this->findModel($CampaignId, $ClientProductId),
        ]);
    }

    /**
     * Creates a new SnapshotForecast model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new SnapshotForecast();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'CampaignId' => $model->CampaignId, 'ClientProductId' => $model->ClientProductId]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SnapshotForecast model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $CampaignId
     * @param integer $ClientProductId
     * @return mixed
     */
    public function actionUpdate($CampaignId, $ClientProductId) {
        $model = $this->findModel($CampaignId, $ClientProductId);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'CampaignId' => $model->CampaignId, 'ClientProductId' => $model->ClientProductId]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SnapshotForecast model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $CampaignId
     * @param integer $ClientProductId
     * @return mixed
     */
    public function actionDelete($CampaignId, $ClientProductId) {
        $this->findModel($CampaignId, $ClientProductId)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SnapshotForecast model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $CampaignId
     * @param integer $ClientProductId
     * @return SnapshotForecast the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($CampaignId, $ClientProductId) {
        if (($model = SnapshotForecast::findOne(['CampaignId' => $CampaignId, 'ClientProductId' => $ClientProductId])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
