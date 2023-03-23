<?php

namespace frontend\controllers;

use Yii;
use common\models\ClientSeller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;


/**
 * Site controller
 */
class PlanController extends \common\components\controllers\CustomController {

   


    public function actionIndex() {

        $clients = \common\models\Client::find()->select('client.ClientId,client.Description')
                ->joinWith('sellers')
                ->where(['SellerId' => \Yii::$app->user->identity->UserId, 'GroupId' => NULL])
                ->orderBy('client.Description ASC')
                ->asArray()
                ->all();
        $actualMonth = \Yii::$app->utilcomponents->getMonth((int) date("m"));
        // abreviature of actual month
        $actualAbrv = \Yii::$app->utilcomponents->getMonthAbr((int) date("m"));

        $campaigns = \common\models\Campaign::find()->asArray()->all();
        return $this->render('index', array('clients' => $clients,
                    'campaigns' => $campaigns,
                    'actual' => $actualMonth,
                    'actualAbrv' => $actualAbrv
                        )
        );
    }

    public function actionList($ClientId = NULL) {
        \Yii::$app->response->format = 'json';
        $connection = Yii::$app->db;

        $SellerId = \Yii::$app->user->identity->UserId;
        $CampaignId = \common\models\Campaign::getFutureCampaign()->CampaignId;

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

    public function actionSave() {
        \Yii::$app->response->format = 'json';

        if (isset($_POST['models']) && count($_POST['models'])) {
            $modelsPost = $_POST['models'];

            $models = [];
            
            foreach ($modelsPost as $item) {
                $model = \common\models\Plan::findOne(['ClientProductId' => $item['ClientProductId'], 'CampaignId' => $item['CampaignId']]);

                // custom setAttributes dynamic set values from actual month
                $model->_setAttributes($item);

                $model->save();
                $models[] = $model;
            }

             Yii::$app->auditcomponents->createAudit(['UserId'=>\Yii::$app->user->identity->UserId,
                                                      'ClientId'=>$models[0]->clientProduct->ClientId,
                                                      'TypeAuditId' => \common\models\TypeAudit::TYPE_SAVE_PLAN,                                                         
                                                        ]);
        } else
            return [];
    }

    

    public function _getAmountQuarter($month) {
        $quarter = 0;
        if ($month >= 4 && $month <= 6)
            $quarter = 1;
        elseif ($month >= 7 && $month <= 9)
            $quarter = 2;
        elseif ($month >= 10 && $month <= 12)
            $quarter = 3;
        return $quarter;
    }

    public function actionExportReportDetail($ClientId = NULL) {

        $title = Yii::t("app","Report Plan Detailed(") . date("Y-m-d") . ")";
        $seller = \Yii::$app->user->identity->Fullname;
        $SellerId = \Yii::$app->user->identity->UserId;
        $campaign = \common\models\Campaign::getFutureCampaign();
        $client = \common\models\Client::findOne(['ClientId' => (int) $ClientId]);

        $connection = \Yii::$app->db;
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
        $objSheet->setCellValue('A1', Yii::t("app","Plan Report  (Volume)"));
        $objSheet->setCellValue('A2', ($client) ? $client->Description : Yii::t("app",'All Clients'));
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

        $objSheet->setCellValue('A1', Yii::t("app","Plan Report  (USD)"));
        $objSheet->setCellValue('A2', ($client) ? $client->Description : Yii::t("app",'All Clients'));
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
    
   
    
    public function actionDownloadValidation() 
    {
        ini_set("memory_limit", - 1);
        ini_set("max_execution_time", "9200");
        $title = Yii::t("app","Template Plans Validation(") . date("Y-m-d") . ")";
        
        $isLock = \common\models\Campaign::getActualCampaign()->isSettingActive();
        if ($isLock) {
            Yii::$app->session->setFlash('danger', Yii::t("app", "The plan has setting dates differents at today!"));
            return $this->redirect(['import/setting']);
        }
        
      
   
        $CampaignId = \common\models\Campaign::getFutureCampaign()->CampaignId;

        $connection = \Yii::$app->db;
        $plans = $connection->createCommand("SELECT    ClientProductId,
                                                       Client,                                                       
                                                       PlanDescription,
                                                       CampaignId,
                                                       TradeProductId,
                                                       GmidId,
                                                       ValueCenter,
                                                       PerformanceCenter,
                                                       January,February,March,
                                                       April,May,June,
                                                       July,August,September,
                                                       October,November,December,
                                                       Q1,Q2,Q3,Q4,Total,
                                                       Country,
                                                       SellerDowId,
                                                       SellerName,
                                                       DSM,
                                                       DsmId,
                                                       ClientId,
                                                       ClientType
                                                FROM dbo.SaleWithPlan
                                                WHERE CampaignId = {$CampaignId} AND IsForecastable = 1 AND Total>0 
                                                ORDER BY PlanDescription ASC
                                              ")->queryAll();       

                   
        // Read the file
        $objReader = IOFactory::createReader('Xlsx');
        $objPHPExcel = $objReader->load('templates/Plan_Validation_Offline_Template.xlsx');
        $objPHPExcel->getProperties()->setTitle($title);
        $objSheet = $objPHPExcel->getActiveSheet();


        // WRITE VALUES IN TEMPLATE 
        
    
        
        
        // WRITE HEADER       
        $day = date("d");
        $month = date("m");
        $year = date("Y");

     
        
        $objSheet->setCellValue('B2', (int) $day);
        $objSheet->setCellValue('B3', (int) $month);
        $objSheet->setCellValue('B4',  (int) $year);
              
        // WRITE PRODUCTS    

        $row = 7;        
        foreach ($plans as $key => $item) {
            $charCol = 0; 
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["ClientProductId"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["Country"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["DsmId"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["DSM"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["SellerDowId"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["SellerName"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["ClientId"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["Client"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["ClientType"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["ValueCenter"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["PerformanceCenter"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["PlanDescription"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["January"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["February"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["March"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, '=SUM(M' . $row . ':O' . $row . ')');
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["April"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["May"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["June"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, '=SUM(Q' . $row . ':S' . $row . ')');
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["July"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["August"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["September"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, '=SUM(U' . $row . ':W' . $row . ')');
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["October"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["November"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, $item["December"]);
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, '=SUM(Y' . $row . ':AA' . $row . ')');
            $objSheet->setCellValueByColumnAndRow($charCol++ , $row, '=P' . $row . '+T' . $row . '+X' . $row . '+AB' . $row);
            
            if ($row % 2 == 0)
                $objSheet->getStyle("A$row:AC$row")->applyFromArray(
                        array('fill' => array('type' => Fill::FILL_SOLID,
                                'color' => array('rgb' => 'F2F2F2')
                            )
                ));

            $row++;
        }

        // SECURITY OF TEMPLATE 
        
        // RANGE OF LOCK
//        $enable = chr(70);
//        $objSheet->getProtection()->setSheet(true);        
//        $objSheet->getProtection()->setPassword('DAS@2015');
//        $objSheet->getStyle("I7:I$row")->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_PROTECTED); 
//        
//         $objSheet->getStyle("A7:E$row")->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_PROTECTED);
//       
//         // LOCK THE QUARTERS AND TOTAL         
//        $objSheet->getStyle("I7:I$row")->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_PROTECTED);
//        $objSheet->getStyle("M7:M$row")->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_PROTECTED);
//        $objSheet->getStyle("Q7:Q$row")->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_PROTECTED);
//        $objSheet->getStyle("U7:U$row")->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_PROTECTED);
//        $objSheet->getStyle("V7:V$row")->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_PROTECTED);
//      
        $row--;
        
        $objSheet->getStyle("P7:" . "P$row")->applyFromArray(
                array('fill' => array('type' => Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FDE9D9')
                    )
        ));

        $objSheet->getStyle("T7:" . "T$row")->applyFromArray(
                array('fill' => array('type' => Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FDE9D9')
                    )
        ));

        $objSheet->getStyle("X7:" . "X$row")->applyFromArray(
                array('fill' => array('type' => Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FDE9D9')
                    )
        ));

        $objSheet->getStyle("AB7:" . "AB$row")->applyFromArray(
                array('fill' => array('type' => Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FDE9D9')
                    )
        ));

        $objSheet->getStyle("AC7:" . "AC$row")->applyFromArray(
                array('fill' => array('type' => Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FABF8F')
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
    
    
    
    public function actionExport() 
    {
        ini_set("memory_limit", - 1);
        ini_set("max_execution_time", "4200");
        $title = Yii::t("app","Template Plan Import(") . date("Y-m-d") . ")";

        $CampaignId = \common\models\Campaign::getFutureCampaign()->CampaignId;
        
        $isLock = \Yii::$app->utilcomponents->isPlanEnable();
        if ($isLock) {
            Yii::$app->session->setFlash('danger', Yii::t("app", "The plan is locked!"));
            return $this->redirect(['index']);
        }
        
        $connection = \Yii::$app->db;
        $plans = \common\models\SaleWithPlan::find()
                                             ->where(['CampaignId'=>$CampaignId,
                                                      'IsForecastable'=>true,
                                                      'SellerId'=>  \Yii::$app->user->identity->UserId,
                                                      'GroupId'=>NULL])
                                             ->orderBy('Client,ValueCenter,PerformanceCenter,PlanDescription ASC')
                                             ->asArray()
                                             ->all();
                                                        

                // AUDIT 
         Yii::$app->auditcomponents->createAudit(['UserId'=>\Yii::$app->user->identity->UserId,                                                     
                                                   'TypeAuditId' => \common\models\TypeAudit::TYPE_EXPORT_PLAN_OFFLINE,                                                         
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
        
        $objSheet->setCellValue('C4', \Yii::$app->user->identity->UserId);
        $objSheet->setCellValue('C2', (int) $month);
        $objSheet->setCellValue('B2', \Yii::$app->utilcomponents->getMonthES((int) $month));
        $objSheet->setCellValue('B3', $year);
        $objSheet->setCellValue('B4', \Yii::$app->user->identity->Fullname);
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
        $objSheet->getProtection()->setPassword('DAS@2015');
    
//         // LOCK THE QUARTERS AND TOTAL    
        $objSheet->getStyle('F7:T' . $row)->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
        $objSheet->getStyle("A7:E$row")->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_PROTECTED);
        $objSheet->getStyle("I7:I$row")->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_PROTECTED);
        $objSheet->getStyle("M7:M$row")->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_PROTECTED);
        $objSheet->getStyle("Q7:Q$row")->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_PROTECTED);
        $objSheet->getStyle("U7:U$row")->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_PROTECTED);
        $objSheet->getStyle("V7:V$row")->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_PROTECTED);
        
        
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
}
