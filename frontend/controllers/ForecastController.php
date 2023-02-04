<?php

namespace frontend\controllers;

use Yii;
use common\models\ClientSeller;

/**
 * Site controller
 */
class ForecastController extends \common\components\controllers\CustomController {

    public function actionExport() {
        ini_set("memory_limit", - 1);
        ini_set("max_execution_time", "9200");

        $title = Yii::t("app","Template Import Forecast")."(" . date("Y-m-d") . ")";

        $isLock = \Yii::$app->utilcomponents->isLock();
        if ($isLock) {
            Yii::$app->session->setFlash('danger', Yii::t("app", "The forecast is locked!"));
            return $this->redirect(['index']);
        }

        $month = date("m");
        $year = date("Y");
        $seller = \Yii::$app->user->identity->Fullname;
        $SellerId = \Yii::$app->user->identity->UserId;
        $CampaignId = \common\models\Campaign::getActualCampaign()->CampaignId;

        $connection = \Yii::$app->db;
        $forecasts = $connection->createCommand("SELECT ClientProductId,
                                                       Client,
                                                       ForecastDescription,
                                                       CampaignId,
                                                       TradeProductId,
                                                       GmidId,
                                                       ValueCenter,
                                                       PerformanceCenter,
                                                       January,February,March,
                                                       April,May,June,
                                                       July,August,September,
                                                       October,November,December,
                                                       Q1,Q2,Q3,Q4,Total
                                                FROM dbo.SaleWithForecast
                                                WHERE SellerId = {$SellerId} AND CampaignId = {$CampaignId} AND IsForecastable = 1 AND GroupId IS NULL
                                                ORDER BY Client,ValueCenter,PerformanceCenter,ForecastDescription ASC
                                              ")->queryAll();


        // Read the file
        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load('templates/Forecast_Offline_Template.xlsx');
        $objPHPExcel->getProperties()->setTitle($title);
        $objSheet = $objPHPExcel->getActiveSheet();

        // AUDIT
         Yii::$app->auditcomponents->createAudit(['UserId'=>\Yii::$app->user->identity->UserId,
                                                   'TypeAuditId' => \common\models\TypeAudit::TYPE_EXPORT_FORECAST_OFFLINE,
                                                     ]);


        // WRITE VALUES IN TEMPLATE

        // WRITE HEADER
        $monthEnableFrom = \common\models\Setting::getValue(\common\models\Setting::FORECAST_ENABLE_FROM);
        $objSheet->setCellValue('C4', $SellerId);
        $objSheet->setCellValue('C2', (int) $month);
        $objSheet->setCellValue('B2', \Yii::$app->utilcomponents->getMonthES((int) $month));
        $objSheet->setCellValue('B3', $year);
        $objSheet->setCellValue('B4', $seller);
        $objSheet->getStyle("C1:C5")->applyFromArray(
                array('font' => array(
                        'color' => array('rgb' => 'b8cce4')
                    )
        ));
        // WRITE PRODUCTS
        $row = 7;
        foreach ($forecasts as $key => $item) {
            $charCol = 65;
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["ClientProductId"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Client"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["ValueCenter"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["PerformanceCenter"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["ForecastDescription"]);
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
                        array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'F2F2F2')
                            )
                ));

            $row++;
        }

        $finish = ($monthEnableFrom - 1) + $this->_getAmountQuarter($monthEnableFrom - 1);
        $cellFinished = chr(70 + ($finish - 1));

        if (($monthEnableFrom - 1) > 1) {
            $objSheet->getStyle("F7:" . $cellFinished . "$row")->applyFromArray(
                    array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'BDBDBD')
                        )
            ));
        }
        // SECURITY OF TEMPLATE

        // RANGE OF LOCK
        $enable = chr(70 + ($finish));
        $objSheet->getProtection()->setSheet(true);
        $objSheet->getProtection()->setPassword('Corteva2020');
        $objSheet->getStyle($enable . '7:V' . $row)->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

        // LOCK THE QUARTERS AND TOTAL
        $objSheet->getStyle("I7:I$row")->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_PROTECTED);
        $objSheet->getStyle("M7:M$row")->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_PROTECTED);
        $objSheet->getStyle("Q7:Q$row")->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_PROTECTED);
        $objSheet->getStyle("U7:U$row")->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_PROTECTED);
        $objSheet->getStyle("V7:V$row")->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_PROTECTED);

        $objSheet->getStyle("M7:" . "M$row")->applyFromArray(
                array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'BDBDBD')
                    )
        ));

        $objSheet->getStyle("I7:" . "I$row")->applyFromArray(
                array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'BDBDBD')
                    )
        ));

        $objSheet->getStyle("Q7:" . "Q$row")->applyFromArray(
                array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'BDBDBD')
                    )
        ));

        $objSheet->getStyle("U7:" . "U$row")->applyFromArray(
                array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'BDBDBD')
                    )
        ));

        $objSheet->getStyle("V7:" . "V$row")->applyFromArray(
                array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
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
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public function actionGetPlan($ClientId = NULL) {
        \Yii::$app->response->format = 'json';
        $connection = Yii::$app->db;
        $SellerId = \Yii::$app->user->identity->UserId;
        $CampaignId = \common\models\Campaign::getActualCampaign()->CampaignId;

        $query = " 
     SELECT 
             SellerId
            ,[January] = isnull(SUM([January]),0)
            ,[JanuaryUSD] = isnull(SUM(January*cp.Price),0)
            ,[February] = isnull(SUM([February]),0)
            ,[FebruaryUSD] = isnull(SUM(February*cp.Price),0)
            ,[March] = isnull(SUM([March]),0)
            ,[MarchUSD] = isnull(SUM(March*cp.Price),0)
            ,[Q1] = isnull(SUM([Q1]),0)
            ,[Q1USD] = isnull(SUM(Q1*cp.Price),0)
            ,[April] = isnull(SUM([April]),0)
            ,[AprilUSD] = isnull(SUM(April*cp.Price),0)
            ,[May] = isnull(SUM([May]),0)
            ,[MayUSD] = isnull(SUM(May*cp.Price),0)
            ,[June] = isnull(SUM([June]),0)
            ,[JuneUSD] = isnull(SUM(June*cp.Price),0)
            ,[Q2] = isnull(SUM([Q2]),0)
            ,[Q2USD] = isnull(SUM(Q2*cp.Price),0)
            ,[July] = isnull(SUM([July]),0)
            ,[JulyUSD] = isnull(SUM(July*cp.Price),0)
            ,[August] = isnull(SUM([August]),0)
            ,[AugustUSD] = isnull(SUM(August*cp.Price),0)
            ,[September] = isnull(SUM([September]),0)
            ,[SeptemberUSD] = isnull(SUM(September*cp.Price),0)
            ,[Q3] = isnull(SUM([Q3]),0)
            ,[Q3USD] = isnull(SUM(Q3*cp.Price),0)
            ,[October] = isnull(SUM([October]),0)
            ,[OctoberUSD] = isnull(SUM(October*cp.Price),0)
            ,[November] = isnull(SUM([November]),0)
            ,[NovemberUSD] = isnull(SUM(November*cp.Price),0)
            ,[December] = isnull(SUM([December]),0)
            ,[DecemberUSD] = isnull(SUM(December*cp.Price),0)
            ,[Q4] = isnull(SUM([Q4]),0)
            ,[Q4USD] = isnull(SUM(Q4*cp.Price),0)
    FROM [plan] p
	INNER JOIN 
   (
	 SELECT         cp.ClientId,
                        cp.IsForecastable,
			cp.ClientProductId,
			tp.TradeProductId,
			g.GmidId,
			Price = CASE  tp.IsForecastable WHEN 1 THEN tp.Price
										ELSE  g.Price
								  END	
		FROM [plan] p
		INNER JOIN client_product cp 
		ON cp.ClientProductId = p.ClientProductId
		INNER JOIN client_seller cs 
		ON cs.ClientId = cp.ClientId
		INNER JOIN trade_product tp
		ON tp.TradeProductId = cp.TradeProductId 
		LEFT JOIN gmid g 
		ON g.GmidId = cp.GmidId
		WHERE CampaignId = {$CampaignId}
	) cp
	ON cp.ClientProductId = p.ClientProductId 
    INNER JOIN client_seller cs 
    ON cs.ClientId = cp.ClientId";

        if (!is_null($ClientId) && $ClientId != "")
            $query = $query . " WHERE cp.ClientId={$ClientId} AND cs.SellerId = {$SellerId} AND p.CampaignId = {$CampaignId} AND cp.IsForecastable = 1 GROUP BY cp.ClientId ,p.CampaignId,cs.SellerId";

        else {
            $query = $query . " WHERE p.CampaignId = {$CampaignId} AND cs.SellerId = {$SellerId} AND cp.IsForecastable = 1 GROUP BY p.CampaignId,cs.SellerId ";
        }

        $resume = $connection->createCommand($query);
        return $resume->queryOne();
    }


    public function actionGetSale($ClientId = NULL) {
        \Yii::$app->response->format = 'json';

        $SellerId = \Yii::$app->user->identity->UserId;
        $CampaignId = \common\models\Campaign::getActualCampaign()->CampaignId;
        $saleQuery = new \yii\db\Query();
           $sales = $saleQuery
                ->select(['SUM(Q1USD) AS Q1USD','SUM(Q2USD) AS Q2USD','SUM(Q3USD) AS Q3USD','SUM(Q4USD) AS Q4USD'])
                ->from('SaleFormat')
                ->innerJoin('[client_seller]'.' cs', 'cs.ClientId = SaleFormat.ClientId')
                ->andFilterWhere(['cs.SellerId' => $SellerId,
                                  'cs.ClientId' => $ClientId,
                                  'SaleFormat.CampaignId' => $CampaignId])

                ->one();




        return $sales;
    }


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
        $CampaignId = \common\models\Campaign::getActualCampaign()->CampaignId;

        if (is_null($ClientId) || $ClientId == "") {
            $forecast = $connection->createCommand("SELECT ClientProductId = NULL,
           CampaignId,
	   TradeProductId,
	   GmidId,
           PerformanceCenterId,
           ValueCenterId,
           RTRIM(LTRIM(ForecastDescription)) AS ForecastDescription,
	   ForecastPrice,
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
FROM dbo.SaleWithForecast
WHERE SellerId = {$SellerId} AND CampaignId = {$CampaignId} AND IsForecastable = 1 AND (CampaignSale ={$CampaignId} OR CampaignSale IS NULL)
GROUP BY CampaignId,SellerId,TradeProductId,GmidId,PerformanceCenterId,ValueCenterId,ForecastDescription,ForecastPrice
ORDER BY ForecastDescription ASC 
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
                                                       RTRIM(LTRIM(ForecastDescription)) AS ForecastDescription,
                                                       ForecastPrice,
                                                       January,February,March,
                                                       April,May,June,
                                                       July,August,September,
                                                       October,November,December,
                                                       Q1,Q2,Q3,Q4,Total
                                                FROM dbo.SaleWithForecast
                                                WHERE SellerId = {$SellerId} AND ClientId = {$ClientId} AND CampaignId = {$CampaignId} AND IsForecastable = 1 AND (CampaignSale ={$CampaignId} OR CampaignSale IS NULL)
                                                ORDER BY ForecastDescription ASC
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
                $model = \common\models\Forecast::findOne(['ClientProductId' => $item['ClientProductId'], 'CampaignId' => $item['CampaignId']]);
                // custom setAttributes dynamic set values from actual month
                $model->_setAttributes($item);

                $model->save();
                $models[] = $model;
            }
            Yii::$app->auditcomponents->createAudit(['UserId'=>\Yii::$app->user->identity->UserId,
                                                      'ClientId'=>$models[0]->clientProduct->ClientId,
                                                      'TypeAuditId' => \common\models\TypeAudit::TYPE_SAVE_FORECAST,
                                                     ]);
        } else
            return [];
    }

    public function actionExportReportConsolid() {

        ini_set("memory_limit", - 1);
        ini_set("max_execution_time", "9200");
        $title =  Yii::t("app","Report Forecast Consolid")."(" . date("Y-m-d") . ")";
        $report = new  \common\models\ExportConsolid();
        $campaign = \common\models\Campaign::getActualCampaign();

        // dynamic params
        $params = \Yii::$app->request->queryParams;

        // fixed params
        $report->CampaignId = $campaign->CampaignId;
        if(\Yii::$app->user->can(\common\models\AuthItem::ROLE_RSM))
            $report->RSMId = \Yii::$app->user->identity->UserId;

        if(\Yii::$app->user->can(\common\models\AuthItem::ROLE_DSM))
            $report->DSMId = \Yii::$app->user->identity->UserId;

        $consolidates = $report->search($params);

        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load('templates/Reporte_Real_Ventas_Forecast_Consolidado.xlsx');
        $objPHPExcel->setActiveSheetIndex(0);
        $objSheet = $objPHPExcel->getActiveSheet();

        // WRITE SHEET
        $objSheet->setCellValue('A1', Yii::t("app","Consolid Report"));
        $objSheet->setCellValue('A2',  Yii::t("app",'All Clients'));
        $objSheet->setCellValue('A3', Yii::t("app",'Year').': '.$campaign->Name);

        ini_set("memory_limit", - 1);
        ini_set("max_execution_time", "9200");
        // WRITE PRODUCTS
        $row = 5;

        foreach ($consolidates as $key => $item) {

            $quarter = \Yii::$app->utilcomponents->_getNumberQuarter($item["MES"]);

            $charCol = 65;
            //   $objSheet->SetCellValue(chr($charCol++) . $row, $item["RSM"]);
            //   $objSheet->SetCellValue(chr($charCol++) . $row, $item["Nombre RSM"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Pais"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["DSM"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Nombre DSM"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Vendedor"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Nombre Vendedor"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Cliente"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Nombre Cliente"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Clasificacion"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Value Center"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Trade Product"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Nombre Trade Product"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Performance"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Nombre Performance"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["GMID"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Nombre GMID"]);
            $objSheet->SetCellValue(chr($charCol++) . $row,  $item["MES"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $quarter);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Precio"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Volumen"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["USD"]);

           if ($row % 2 == 0)
                $objSheet->getStyle("A$row:V$row")->applyFromArray(
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

        $title = Yii::t("app","Report Forecast Detail")."(" . date("Y-m-d") . ")";
        $seller = \Yii::$app->user->identity->Fullname;
        $SellerId = \Yii::$app->user->identity->UserId;
        $campaign = \common\models\Campaign::getActualCampaign();
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
                                                            ForecastDescription,
                                                            ForecastPrice,                                                            
                                                            SUM(January) AS January,
                                                            [JanuaryUSD] = SUM(JanuarySaleForecastUSD),
                                                            SUM(February) AS February,
                                                            [FebruaryUSD] = SUM(FebruarySaleForecastUSD),
                                                            SUM(March) AS March,	   
                                                            [MarchUSD] = SUM(MarchSaleForecastUSD),
                                                            SUM(April) AS April,
                                                            [AprilUSD] = SUM(AprilSaleForecastUSD),
                                                            SUM(May) AS  May,
                                                            [MayUSD] = SUM(MaySaleForecastUSD),
                                                            SUM(June) AS  June,
                                                            [JuneUSD] = SUM(JuneSaleForecastUSD),
                                                            SUM(July) AS July,
                                                            [JulyUSD] = SUM(JulySaleForecastUSD),
                                                            SUM(August) AS August,
                                                            [AugustUSD] = SUM(AugustSaleForecastUSD),
                                                            SUM(September) AS September,
                                                            [SeptemberUSD] = SUM(SeptemberSaleForecastUSD),
                                                            SUM(October) AS October,
                                                            [OctoberUSD] = SUM(OctoberSaleForecastUSD),
                                                            SUM(November) AS November,
                                                            [NovemberUSD] = SUM(NovemberSaleForecastUSD),
                                                            SUM(December) AS December,
                                                            [DecemberUSD] = SUM(DecemberSaleForecastUSD),
                                                            SUM(Q1) AS Q1,
                                                            SUM(Q2) AS Q2,
                                                            SUM(Q3) AS Q3,
                                                            SUM(Q4) AS Q4,
                                                            Total = SUM(Q1) + SUM(Q2) +  SUM(Q3) + SUM(Q4)
                                                 FROM dbo.SaleWithForecast
                                                 WHERE SellerId = {$SellerId} AND CampaignId = {$campaign->CampaignId} AND Total>0
                                                 GROUP BY CampaignId,ValueCenter,ClientId,Client,SellerId,TradeProductId,GmidId,PerformanceCenter,PerformanceCenterId,ValueCenterId,ForecastDescription,ForecastPrice
                                                 ORDER BY Client,ValueCenter,PerformanceCenter,ForecastDescription,GmidId ASC
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
                                                       ForecastDescription,
                                                       ForecastPrice,
                                                       January,
                                                       [JanuaryUSD] = JanuarySaleForecastUSD,
                                                       February,
                                                       [FebruaryUSD] = FebruarySaleForecastUSD,
                                                       March,
                                                       [MarchUSD] = MarchSaleForecastUSD,
                                                       April,
                                                       [AprilUSD] = AprilSaleForecastUSD,
                                                       May,
                                                       [MayUSD] = MaySaleForecastUSD,
                                                       June,
                                                       [JuneUSD] = JuneSaleForecastUSD,
                                                       July,
                                                       [JulyUSD] = JulySaleForecastUSD,
                                                       August,
                                                       [AugustUSD] = AugustSaleForecastUSD,
                                                       September,
                                                       [SeptemberUSD] = SeptemberSaleForecastUSD,
                                                       October,
                                                       [OctoberUSD] = OctoberSaleForecastUSD,
                                                       November,
                                                       [NovemberUSD] = NovemberSaleForecastUSD,
                                                       December,
                                                       [DecemberUSD] = DecemberSaleForecastUSD,
                                                       Q1,
                                                       Q2,
                                                       Q3,
                                                       Q4,
                                                       Total
                                                FROM dbo.SaleWithForecast
                                                WHERE SellerId = {$SellerId} AND ClientId = {$client->ClientId} AND CampaignId = {$campaign->CampaignId} AND Total >0
                                                ORDER BY Client,ValueCenter,PerformanceCenter,ForecastDescription,GmidId ASC
                                              ")->queryAll();
        }



        // Read the file
        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load('templates/Ventas_Forecast_Detallado_Template.xlsx');

        $objPHPExcel->setActiveSheetIndex(0);
        $objSheet = $objPHPExcel->getActiveSheet();

        // WRITE VALUES IN TEMPLATE
        // WRITE SHEET
        $objSheet->setCellValue('A1', Yii::t("app","Report Real Sales + Forecast  (Volume)"));
        $objSheet->setCellValue('A2', ($client) ? $client->Description : Yii::t("app",'All'));
        $objSheet->setCellValue('A3', $campaign->Name);
        $objSheet->setCellValue('A4', $seller);

        // WRITE PRODUCTS
        $row = 7;
        foreach ($forecasts as $key => $item) {
            $charCol = 65;
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["ClientId"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Client"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["ValueCenter"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["PerformanceCenter"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["ForecastDescription"]);
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
                        array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'F2F2F2')
                            )
                ));
            $row++;
        }


            $objSheet->getStyle("M7:" . "M$row")->applyFromArray(
                array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FDE9D9')
                    )
        ));

        $objSheet->getStyle("I7:" . "I$row")->applyFromArray(
                array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FDE9D9')
                    )
        ));

        $objSheet->getStyle("Q7:" . "Q$row")->applyFromArray(
                array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FDE9D9')
                    )
        ));

        $objSheet->getStyle("U7:" . "U$row")->applyFromArray(
                array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FDE9D9')
                    )
        ));

        $objSheet->getStyle("V7:" . "V$row")->applyFromArray(
                array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FABF8F')
                    )
        ));

        $objPHPExcel->setActiveSheetIndex(1);
        $objSheet = $objPHPExcel->getActiveSheet();

        $objSheet->setCellValue('A1', Yii::t("app","Report Real Sales + Forecast  (USD)"));
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
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["PerformanceCenter"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["ForecastDescription"]);
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
                        array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'F2F2F2')
                            )
                ));
            $row++;
        }
   $objSheet->getStyle("M7:" . "M$row")->applyFromArray(
                array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FDE9D9')
                    )
        ));

        $objSheet->getStyle("I7:" . "I$row")->applyFromArray(
                array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FDE9D9')
                    )
        ));

        $objSheet->getStyle("Q7:" . "Q$row")->applyFromArray(
                array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FDE9D9')
                    )
        ));

        $objSheet->getStyle("U7:" . "U$row")->applyFromArray(
                array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FDE9D9')
                    )
        ));

        $objSheet->getStyle("V7:" . "V$row")->applyFromArray(
                array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FABF8F')
                    )
        ));
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet();
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
