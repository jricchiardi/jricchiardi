<?php

namespace frontend\controllers;

set_include_path(get_include_path() . PATH_SEPARATOR . "..");
include_once("xlsxwriter.class.php");

use common\components\controllers\CustomController;
use common\models\Campaign;
use common\models\Client;
use common\models\DownloadValidacionPlanForm;
use common\models\Plan;
use common\models\SaleWithPlan;
use common\models\TypeAudit;
use common\models\ValidacionPlanForm;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Protection;
use Yii;

/**
 * Site controller
 */
class PlanController extends CustomController
{
    public function actionIndex()
    {

        $clients = Client::find()->select('client.ClientId,client.Description')
            ->joinWith('sellers')
            ->where(['SellerId' => Yii::$app->user->identity->UserId, 'GroupId' => NULL])
            ->orderBy('client.Description ASC')
            ->asArray()
            ->all();
        $actualMonth = Yii::$app->utilcomponents->getMonth((int)date("m"));
        // abreviature of actual month
        $actualAbrv = Yii::$app->utilcomponents->getMonthAbr((int)date("m"));

        $campaigns = Campaign::find()->asArray()->all();
        return $this->render('index', array('clients' => $clients,
                'campaigns' => $campaigns,
                'actual' => $actualMonth,
                'actualAbrv' => $actualAbrv
            )
        );
    }

    public function actionList($ClientId = NULL)
    {
        Yii::$app->response->format = 'json';
        $connection = Yii::$app->db;

        $SellerId = Yii::$app->user->identity->UserId;
        $CampaignId = Campaign::getFutureCampaign()->CampaignId;

        if (is_null($ClientId) || $ClientId == "") {
            $forecast = $connection->createCommand("SELECT ClientProductId = NULL,
           CampaignId,
	   TradeProductId,
	   GmidId,
           PerformanceCenterId,
           ValueCenterId,
           RTRIM(LTRIM(PlanDescription)) AS PlanDescription,
	   PlanPrice,
           SUM(January) AS January,
	   SUM(February) AS February,
	   SUM(March) AS March,	   
           SUM(April) AS April,
	   SUM(May) AS  May,
	   SUM(June) AS  June,
           SUM(July) AS July,
	   SUM(August) AS August,
	   SUM(September) AS September,
           SUM(October) AS October,
	   SUM(November) AS November,
	   SUM(December) AS December,
           SUM(Q1) AS Q1,
	   SUM(Q2) AS Q2,
	   SUM(Q3) AS Q3,
	   SUM(Q4) AS Q4,
           Total = SUM(Q1) + SUM(Q2) +  SUM(Q3) + SUM(Q4)
FROM dbo.SaleWithPlan
WHERE SellerId = {$SellerId} AND CampaignId = {$CampaignId} AND IsForecastable = 1
GROUP BY CampaignId,SellerId,TradeProductId,GmidId,PerformanceCenterId,ValueCenterId,PlanDescription,PlanPrice
ORDER BY PlanDescription ASC 
                                              ");
        } else {
            $forecast = $connection->createCommand("SELECT ClientProductId,
                                                       CampaignId,
                                                       TradeProduct,
                                                       TradeProductId,
                                                       TradeProductPrice,
                                                       TradeProductProfit,
                                                       GmidId,
                                                       GmidDescription,
                                                       GmidPrice,
                                                       GmidProfit,
                                                       PerformanceCenterId,
                                                       ValueCenterId,
                                                       RTRIM(LTRIM(PlanDescription)) AS PlanDescription,
                                                       PlanPrice,
                                                       January,February,March,
                                                       April,May,June,
                                                       July,August,September,
                                                       October,November,December,
                                                       Q1,Q2,Q3,Q4,Total
                                                FROM dbo.SaleWithPlan
                                                WHERE SellerId = {$SellerId} AND ClientId = {$ClientId} AND CampaignId = {$CampaignId} AND IsForecastable = 1
                                                ORDER BY PlanDescription ASC
                                              ");
        }

        return $forecast->queryAll();
    }

    public function actionSave()
    {
        Yii::$app->response->format = 'json';

        if (isset($_POST['models']) && count($_POST['models'])) {
            $modelsPost = $_POST['models'];

            $models = [];

            foreach ($modelsPost as $item) {
                $model = Plan::findOne(['ClientProductId' => $item['ClientProductId'], 'CampaignId' => $item['CampaignId']]);

                // custom setAttributes dynamic set values from actual month
                $model->_setAttributes($item);

                $model->save();
                $models[] = $model;
            }

            Yii::$app->auditcomponents->createAudit(['UserId' => Yii::$app->user->identity->UserId,
                'ClientId' => $models[0]->clientProduct->ClientId,
                'TypeAuditId' => TypeAudit::TYPE_SAVE_PLAN,
            ]);
        } else
            return [];
    }

    public function _getAmountQuarter($month)
    {
        $quarter = 0;
        if ($month >= 4 && $month <= 6)
            $quarter = 1;
        elseif ($month >= 7 && $month <= 9)
            $quarter = 2;
        elseif ($month >= 10 && $month <= 12)
            $quarter = 3;
        return $quarter;
    }

    public function actionExportReportDetail($ClientId = NULL)
    {

        $title = Yii::t("app", "Report Plan Detailed(") . date("Y-m-d") . ")";
        $seller = Yii::$app->user->identity->Fullname;
        $SellerId = Yii::$app->user->identity->UserId;
        $campaign = Campaign::getFutureCampaign();
        $client = Client::findOne(['ClientId' => (int)$ClientId]);

        $connection = Yii::$app->db;
        if (is_null($ClientId) || $ClientId == "") {
            $forecasts = $connection->createCommand("SELECT ClientProductId = NULL,
                                                            CampaignId,
                                                            ClientId,
                                                            Client,
                                                            TradeProductId,
                                                            GmidId,
                                                            ValueCenter,
                                                            isnull(GmidId,TradeProductId) AS GMID,
                                                            PerformanceCenterId,
                                                            PerformanceCenter,
                                                            ValueCenterId,
                                                            PlanDescription,
                                                            PlanPrice,                                                            
                                                            SUM(January) AS January,
                                                            [JanuaryUSD] = SUM(January * PlanPrice),
                                                            SUM(February) AS February,
                                                            [FebruaryUSD] = SUM(February * PlanPrice),
                                                            SUM(March) AS March,	   
                                                            [MarchUSD] = SUM(March * PlanPrice),
                                                            SUM(April) AS April,
                                                            [AprilUSD] = SUM(April * PlanPrice),
                                                            SUM(May) AS  May,
                                                            [MayUSD] = SUM(May * PlanPrice),
                                                            SUM(June) AS  June,
                                                            [JuneUSD] = SUM(June * PlanPrice),
                                                            SUM(July) AS July,
                                                            [JulyUSD] = SUM(July * PlanPrice),
                                                            SUM(August) AS August,
                                                            [AugustUSD] = SUM(August * PlanPrice),
                                                            SUM(September) AS September,
                                                            [SeptemberUSD] = SUM(September * PlanPrice),
                                                            SUM(October) AS October,
                                                            [OctoberUSD] = SUM(October * PlanPrice),
                                                            SUM(November) AS November,
                                                            [NovemberUSD] = SUM(November * PlanPrice),
                                                            SUM(December) AS December,
                                                            [DecemberUSD] = SUM(December * PlanPrice),
                                                            SUM(Q1) AS Q1,
                                                            SUM(Q2) AS Q2,
                                                            SUM(Q3) AS Q3,
                                                            SUM(Q4) AS Q4,
                                                            Total = SUM(Q1) + SUM(Q2) +  SUM(Q3) + SUM(Q4)
                                                 FROM dbo.SaleWithPlan
                                                 WHERE SellerId = {$SellerId} AND CampaignId = {$campaign->CampaignId} AND Total >0
                                                 GROUP BY CampaignId,ValueCenter,ClientId,Client,SellerId,TradeProductId,GmidId,PerformanceCenter,PerformanceCenterId,ValueCenterId,PlanDescription,PlanPrice
                                                 ORDER BY Client,ValueCenter,PerformanceCenter,PlanDescription,GmidId ASC
                                              ")->queryAll();
        } else {


            $forecasts = $connection->createCommand("SELECT ClientProductId,
                                                       ClientId,      
                                                       Client,
                                                       CampaignId,
                                                       TradeProduct,
                                                       TradeProductId,
                                                       TradeProductPrice,
                                                       TradeProductProfit,
                                                       isnull(GmidId,TradeProductId) AS GMID,
                                                       GmidDescription,
                                                       GmidPrice,
                                                       GmidProfit,
                                                       PerformanceCenterId,
                                                       PerformanceCenter,
                                                       ValueCenterId,
                                                       ValueCenter,
                                                       PlanDescription,
                                                       PlanPrice,
                                                       January,
                                                       [JanuaryUSD] = January * PlanPrice,
                                                       February,
                                                       [FebruaryUSD] = February*PlanPrice,
                                                       March,
                                                       [MarchUSD] = March*PlanPrice,
                                                       April,
                                                       [AprilUSD] = April*PlanPrice,
                                                       May,
                                                       [MayUSD] = May*PlanPrice,
                                                       June,
                                                       [JuneUSD] = June*PlanPrice,
                                                       July,
                                                       [JulyUSD] = July*PlanPrice,
                                                       August,
                                                       [AugustUSD] = August*PlanPrice,
                                                       September,
                                                       [SeptemberUSD] = September*PlanPrice,
                                                       October,
                                                       [OctoberUSD] = October*PlanPrice,
                                                       November,
                                                       [NovemberUSD] = November*PlanPrice,
                                                       December,
                                                       [DecemberUSD] = December*PlanPrice,
                                                       Q1,
                                                       Q2,
                                                       Q3,
                                                       Q4,
                                                       Total
                                                FROM dbo.SaleWithPlan
                                                WHERE SellerId = {$SellerId} AND ClientId = {$client->ClientId} AND CampaignId = {$campaign->CampaignId} AND Total >0
                                                ORDER BY Client,ValueCenter,PerformanceCenter,PlanDescription,GmidId ASC
                                              ")->queryAll();
        }


        // Read the file
        $objReader = IOFactory::createReader('Xlsx');
        $objPHPExcel = $objReader->load('templates/Ventas_Forecast_Detallado_Template.xlsx');

        $objPHPExcel->setActiveSheetIndex(0);
        $objSheet = $objPHPExcel->getActiveSheet();

        // WRITE VALUES IN TEMPLATE
        // WRITE SHEET
        $objSheet->setCellValue('A1', Yii::t("app", "Plan Report  (Volume)"));
        $objSheet->setCellValue('A2', ($client) ? $client->Description : Yii::t("app", 'All Clients'));
        $objSheet->setCellValue('A3', $campaign->Name);
        $objSheet->setCellValue('A4', $seller);

        // WRITE PRODUCTS
        $row = 7;
        foreach ($forecasts as $key => $item) {
            $charCol = 65;
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["ClientId"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Client"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["ValueCenter"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["GMID"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["PlanDescription"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["January"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["February"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["March"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, '=SUM(F' . $row . ':H' . $row . ')');
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["April"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["May"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["June"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, '=SUM(J' . $row . ':L' . $row . ')');
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["July"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["August"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["September"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, '=SUM(N' . $row . ':P' . $row . ')');
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["October"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["November"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["December"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, '=SUM(R' . $row . ':T' . $row . ')');
            $objSheet->SetCellValue(chr($charCol++) . $row, '=I' . $row . '+M' . $row . '+Q' . $row . '+U' . $row);
            if ($row % 2 == 0)
                $objSheet->getStyle("A$row:W$row")->applyFromArray(
                    array('fill' => array('type' => Fill::FILL_SOLID,
                        'color' => array('rgb' => 'F2F2F2')
                    )
                    ));
            $row++;
        }


        $objPHPExcel->setActiveSheetIndex(1);
        $objSheet = $objPHPExcel->getActiveSheet();

        $objSheet->setCellValue('A1', Yii::t("app", "Plan Report  (USD)"));
        $objSheet->setCellValue('A2', ($client) ? $client->Description : Yii::t("app", 'All Clients'));
        $objSheet->setCellValue('A3', $campaign->Name);
        $objSheet->setCellValue('A4', $seller);

        // WRITE PRODUCTS
        $row = 7;
        foreach ($forecasts as $key => $item) {
            $charCol = 65;
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["ClientId"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Client"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["ValueCenter"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["GMID"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["PlanDescription"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["JanuaryUSD"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["FebruaryUSD"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["MarchUSD"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, '=SUM(F' . $row . ':H' . $row . ')');
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["AprilUSD"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["MayUSD"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["JuneUSD"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, '=SUM(J' . $row . ':L' . $row . ')');
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["JulyUSD"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["AugustUSD"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["SeptemberUSD"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, '=SUM(N' . $row . ':P' . $row . ')');
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["OctoberUSD"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["NovemberUSD"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["DecemberUSD"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, '=SUM(R' . $row . ':T' . $row . ')');
            $objSheet->SetCellValue(chr($charCol++) . $row, '=I' . $row . '+M' . $row . '+Q' . $row . '+U' . $row);
            if ($row % 2 == 0)
                $objSheet->getStyle("A$row:W$row")->applyFromArray(
                    array('fill' => array('type' => Fill::FILL_SOLID,
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
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public function actionExport()
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "4200");
        $title = Yii::t("app", "Template Plan Import(") . date("Y-m-d") . ")";

        $CampaignId = Campaign::getFutureCampaign()->CampaignId;

        $isLock = Yii::$app->utilcomponents->isPlanEnable();
        if ($isLock) {
            Yii::$app->session->setFlash('danger', Yii::t("app", "The plan is locked!"));
            return $this->redirect(['index']);
        }

        $connection = Yii::$app->db;
        $plans = SaleWithPlan::find()
            ->where(['CampaignId' => $CampaignId,
                'IsForecastable' => true,
                'SellerId' => Yii::$app->user->identity->UserId,
                'GroupId' => NULL])
            ->orderBy('Client,ValueCenter,PerformanceCenter,PlanDescription ASC')
            ->asArray()
            ->all();


        // AUDIT
        Yii::$app->auditcomponents->createAudit(['UserId' => Yii::$app->user->identity->UserId,
            'TypeAuditId' => TypeAudit::TYPE_EXPORT_PLAN_OFFLINE,
        ]);

        // Read the file
        $objReader = IOFactory::createReader('Xlsx');
        $objPHPExcel = $objReader->load('templates/Plan_Offline_Template.xlsx');
        $objPHPExcel->getProperties()->setTitle($title);
        $objSheet = $objPHPExcel->getActiveSheet();


        // WRITE VALUES IN TEMPLATE

        // WRITE HEADER
        $month = date("m");
        $year = date("Y");

        $objSheet->setCellValue('C4', Yii::$app->user->identity->UserId);
        $objSheet->setCellValue('C2', (int)$month);
        $objSheet->setCellValue('B2', Yii::$app->utilcomponents->getMonthES((int)$month));
        $objSheet->setCellValue('B3', $year);
        $objSheet->setCellValue('B4', Yii::$app->user->identity->Fullname);
        $objSheet->getStyle("C1:C5")->applyFromArray(
            array('font' => array(
                'color' => array('rgb' => 'b8cce4')
            )
            ));

        // WRITE PRODUCTS
        $row = 7;
        foreach ($plans as $key => $item) {
            $charCol = 65;
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["ClientProductId"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Client"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["ValueCenter"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["PerformanceCenter"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["PlanDescription"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["January"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["February"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["March"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, '=SUM(F' . $row . ':H' . $row . ')');
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["April"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["May"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["June"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, '=SUM(J' . $row . ':L' . $row . ')');
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["July"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["August"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["September"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, '=SUM(N' . $row . ':P' . $row . ')');
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["October"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["November"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["December"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, '=SUM(R' . $row . ':T' . $row . ')');
            $objSheet->SetCellValue(chr($charCol++) . $row, '=I' . $row . '+M' . $row . '+Q' . $row . '+U' . $row);

            if ($row % 2 == 0)
                $objSheet->getStyle("A$row:V$row")->applyFromArray(
                    array('fill' => array('type' => Fill::FILL_SOLID,
                        'color' => array('rgb' => 'F2F2F2')
                    )
                    ));
            $row++;
        }
        $objSheet->getProtection()->setSheet(true);
        $objSheet->getProtection()->setPassword('Corteva2020');

//         // LOCK THE QUARTERS AND TOTAL
        $objSheet->getStyle('F7:T' . $row)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
        $objSheet->getStyle("A7:E$row")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
        $objSheet->getStyle("I7:I$row")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
        $objSheet->getStyle("M7:M$row")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
        $objSheet->getStyle("Q7:Q$row")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
        $objSheet->getStyle("U7:U$row")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
        $objSheet->getStyle("V7:V$row")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);


        $objSheet->getStyle("M7:" . "M$row")->applyFromArray(
            array('fill' => array('type' => Fill::FILL_SOLID,
                'color' => array('rgb' => 'BDBDBD')
            )
            ));

        $objSheet->getStyle("I7:" . "I$row")->applyFromArray(
            array('fill' => array('type' => Fill::FILL_SOLID,
                'color' => array('rgb' => 'BDBDBD')
            )
            ));

        $objSheet->getStyle("Q7:" . "Q$row")->applyFromArray(
            array('fill' => array('type' => Fill::FILL_SOLID,
                'color' => array('rgb' => 'BDBDBD')
            )
            ));

        $objSheet->getStyle("U7:" . "U$row")->applyFromArray(
            array('fill' => array('type' => Fill::FILL_SOLID,
                'color' => array('rgb' => 'BDBDBD')
            )
            ));

        $objSheet->getStyle("V7:" . "V$row")->applyFromArray(
            array('fill' => array('type' => Fill::FILL_SOLID,
                'color' => array('rgb' => 'BDBDBD')
            )
            ));

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
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public function actionDownloadValidation()
    {
        $request = Yii::$app->request;

        if ($request->isPost) {
            $model = new DownloadValidacionPlanForm();
            $model->load($request->post());

            if ($model->validate()) {
                $totalComparision = ($model->incluirConVolumenCero === '1') ? '>=' : '>';

                if ($model->plan === ValidacionPlanForm::TIPO_PLAN_FUTURO) {
                    $this->downloadValidationFuturo($model->pais, $totalComparision);
                }

                if ($model->plan === ValidacionPlanForm::TIPO_PLAN_ACTUAL) {
                    $this->downloadValidationActual($model->pais, $totalComparision);
                }
            }
        } else {
            Yii::$app->session->setFlash('danger', "Se produjo un error");
            return $this->redirect(['import/setting']);
        }
    }

    private function downloadValidationFuturo($countryId, $totalComparision)
    {
        $isLock = Campaign::getActualCampaign()->isSettingActive();
        if ($isLock) {
            Yii::$app->session->setFlash('danger', Yii::t("app", "The plan has setting dates differents at today!"));
            return $this->redirect(['import/setting']);
        }

        $campaignId = Campaign::getFutureCampaign()->CampaignId;
        $this->downloadValidation($campaignId, $countryId, $totalComparision);
    }

    private function downloadValidationActual($countryId, $totalComparision)
    {
        $campaignId = Campaign::getActualCampaign()->CampaignId;
        $this->downloadValidation($campaignId, $countryId, $totalComparision);
    }

    private function downloadValidation($campaignId, $countryId, $totalComparision)
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        $title = Yii::t("app", "Template Plans Validation(") . date("Y-m-d") . ")";

        $connection = Yii::$app->db;

        $sql = "SELECT
                    ClientProductId,
                    Country,
                    DsmId,
                    DSM,
                    SellerDowId,
                    SellerName,
                    ClientId,
                    Client,
                    ClientType,
                    ValueCenter,
                    PerformanceCenter,
                    PlanDescription,
                    January,February,March,
                    Q1,
                    April,May,June,
                    Q2,
                    July,August,September,
                    Q3,
                    October,November,December,
                    Q4,
                    Total
                FROM dbo.SaleWithPlan sp
                INNER JOIN country coun ON coun.Description = sp.Country
                WHERE CampaignId = {$campaignId} AND IsForecastable = 1 AND Total {$totalComparision} 0 AND coun.CountryId = {$countryId}
                ORDER BY PlanDescription ASC";

        $plans = $connection->createCommand($sql)->queryAll();

        $writer = new \XLSXWriter();

        $styles1 = ['font' => 'Calibri', 'font-size' => 11, 'font-style' => 'bold,italic', 'fill' => '#dce6f2',];

        $arr = [];
        for ($i = 0; $i < 27; $i++) {
            $arr[] = '';
        }
        $row1 = array_merge(['Plantilla validación de plan', ''], $arr);
        $writer->writeSheetRow('Sheet1', $row1, $styles1);

        $row2 = array_merge(['Dia', (int)date("d")], $arr);
        $writer->writeSheetRow('Sheet1', $row2, $styles1);

        $row3 = array_merge(['Mes', (int)date("m")], $arr);
        $writer->writeSheetRow('Sheet1', $row3, $styles1);

        $row4 = array_merge(['Año', (int)date("Y")], $arr);
        $writer->writeSheetRow('Sheet1', $row4, $styles1);

        $row5 = array_merge(['', ''], $arr);
        $writer->writeSheetRow('Sheet1', $row5, $styles1);

        $styles2 = ['font' => 'Calibri', 'font-size' => 12, 'font-style' => 'bold', 'fill' => '#d6e3bc', 'halign' => 'center', 'border' => 'left,right,top,bottom'];
        $arr2 = [];
        for ($i = 0; $i < 29; $i++) {
            $arr2[] = $styles2;
        }
        $arr2[15] = ['font' => 'Calibri', 'font-size' => 12, 'font-style' => 'bold', 'fill' => '#fdeada', 'halign' => 'center', 'border' => 'left,right,top,bottom'];
        $arr2[19] = ['font' => 'Calibri', 'font-size' => 12, 'font-style' => 'bold', 'fill' => '#fdeada', 'halign' => 'center', 'border' => 'left,right,top,bottom'];
        $arr2[23] = ['font' => 'Calibri', 'font-size' => 12, 'font-style' => 'bold', 'fill' => '#fdeada', 'halign' => 'center', 'border' => 'left,right,top,bottom'];
        $arr2[27] = ['font' => 'Calibri', 'font-size' => 12, 'font-style' => 'bold', 'fill' => '#fdeada', 'halign' => 'center', 'border' => 'left,right,top,bottom'];
        $arr2[28] = ['font' => 'Calibri', 'font-size' => 12, 'font-style' => 'bold', 'fill' => '#fac090', 'halign' => 'center', 'border' => 'left,right,top,bottom'];
        $row6 = [
            '#',
            'País',
            'DSM',
            'Nombre DSM',
            'Vendedor',
            'Nombre Vendedor',
            'Cliente',
            'Nombre Cliente',
            'Clasificación',
            'Value Center',
            'Performance',
            'Descripción',
            'ENERO',
            'FEBRERO',
            'MARZO',
            'Q1',
            'ABRIL',
            'MAYO',
            'JUNIO',
            'Q2',
            'JULIO',
            'AGOSTO',
            'SEPTIEMBRE',
            'Q3',
            'OCTUBRE',
            'NOVIEMBRE',
            'DICIEMBRE',
            'Q4',
            'Total'
        ];
        $writer->writeSheetRow('Sheet1', $row6, $arr2);

        $arr3 = [];
        for ($i = 0; $i < 29; $i++) {
            $arr3[] = [];
        }

        $rowNum = 7;
        foreach ($plans as $row) {
            $row['Q1'] = '=SUM(M' . $rowNum . ':O' . $rowNum . ')';
            $row['Q2'] = '=SUM(Q' . $rowNum . ':S' . $rowNum . ')';
            $row['Q3'] = '=SUM(U' . $rowNum . ':W' . $rowNum . ')';
            $row['Q4'] = '=SUM(Y' . $rowNum . ':AA' . $rowNum . ')';
            $row['Total'] = '=P' . $rowNum . '+T' . $rowNum . '+X' . $rowNum . '+AB' . $rowNum;

            $styleOfRow = $arr3;

            if ($rowNum % 2 == 0) {
                $styleOfRow = [];
                for ($i = 0; $i < 29; $i++) {
                    $styleOfRow[] = ['fill' => '#f2f2f2',];
                }
            }

            $styleOfRow[15] = ['fill' => '#fde9d9',];
            $styleOfRow[19] = ['fill' => '#fde9d9',];
            $styleOfRow[23] = ['fill' => '#fde9d9',];
            $styleOfRow[27] = ['fill' => '#fde9d9',];
            $styleOfRow[28] = ['fill' => '#fabf8f',];

            $writer->writeSheetRow('Sheet1', $row, $styleOfRow);

            $rowNum++;
        }

        $file = \Yii::$app->basePath . "/web/uploads/" . $title . ".xlsx";
        $writer->writeToFile($file);

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            unlink($file);
        }
        return $this->redirect(['import/setting']);
    }
}
