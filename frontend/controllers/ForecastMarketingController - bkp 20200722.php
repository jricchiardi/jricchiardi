<?php

namespace frontend\controllers;

use common\components\controllers\CustomController;
use common\models\Campaign;
use common\models\ForecastMarketing;
use common\models\Setting;
use common\models\TypeAudit;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Protection;
use Yii;
use yii\db\Query;

/**
 * Site controller
 */
class ForecastMarketingController extends CustomController
{
    public function actionIndex()
    {
        return $this->render('index', []);
    }

    public function actionExport()
    {
        $isLock = Yii::$app->utilcomponents->isMarketingForecastLocked();
        if ($isLock) {
            Yii::$app->session->setFlash('danger', Yii::t("app", "The forecast is locked!"));
            return $this->redirect(['index']);
        }

        $title = Yii::t("app", "Template Import Forecast") . "(" . date("Y-m-d") . ")";

        $month = date("m");
        $year = date("Y");
        $seller = Yii::$app->user->identity->Fullname;
        $SellerId = Yii::$app->user->identity->UserId;
        $CampaignId = Campaign::getActualCampaign()->CampaignId;

        $connection = Yii::$app->db;
        $forecasts = $connection->createCommand("SELECT ClientMarketingProductId,
                                                       Client,
                                                       ForecastDescription,
                                                       CampaignId,
                                                       dbo.SaleWithForecastMarketing.TradeProductId,
                                                       dbo.SaleWithForecastMarketing.GmidId,
                                                       c.Description AS Country,
                                                       January,February,March,
                                                       April,May,June,
                                                       July,August,September,
                                                       October,November,December,
                                                       Q1,Q2,Q3,Q4,Total
                                                FROM dbo.SaleWithForecastMarketing
                                                INNER JOIN pm_product pm ON pm.TradeProductId = dbo.SaleWithForecastMarketing.TradeProductId AND pm.GmidId = dbo.SaleWithForecastMarketing.GmidId
                                                INNER JOIN pm_client pmc ON pmc.ClientId = dbo.SaleWithForecastMarketing.ClientMarketingId
                                                INNER JOIN country c ON c.CountryId = dbo.SaleWithForecastMarketing.CountryId
                                                WHERE pm.UserId = {$SellerId} AND CampaignId = {$CampaignId} AND IsForecastable = 1 AND GroupId IS NULL
                                                ORDER BY Client,Country,ForecastDescription ASC
                                              ")->queryAll();

        // Read the file
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load('templates/Forecast_Marketing_Offline_Template.xlsx');
        $objPHPExcel->getProperties()->setTitle($title);
        $objSheet = $objPHPExcel->getActiveSheet();

        // AUDIT
        Yii::$app->auditcomponents->createAudit(['UserId' => Yii::$app->user->identity->UserId,
            'TypeAuditId' => TypeAudit::TYPE_EXPORT_FORECAST_MARKETING_OFFLINE,
        ]);

        // WRITE VALUES IN TEMPLATE

        // WRITE HEADER
        $monthEnableFrom = Setting::getValue(Setting::FORECAST_ENABLE_FROM);
        $objSheet->setCellValue('C4', $SellerId);
        $objSheet->setCellValue('C2', (int)$month);
        $objSheet->setCellValue('B2', Yii::$app->utilcomponents->getMonthES((int)$month));
        $objSheet->setCellValue('B3', $year);
        $objSheet->setCellValue('B4', $seller);
        $objSheet->getStyle("C1:C5")->applyFromArray([
            'font' => [
                'color' => [
                    'rgb' => 'b8cce4'
                ],
            ],
        ]);

        // WRITE PRODUCTS
        $row = 6;
        foreach ($forecasts as $key => $item) {
            $row++;
            $charCol = 65;
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["ClientMarketingProductId"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Client"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Country"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["ForecastDescription"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["January"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["February"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["March"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, '=SUM(E' . $row . ':G' . $row . ')');
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["April"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["May"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["June"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, '=SUM(I' . $row . ':K' . $row . ')');
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["July"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["August"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["September"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, '=SUM(M' . $row . ':O' . $row . ')');
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["October"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["November"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["December"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, '=SUM(Q' . $row . ':S' . $row . ')');
            $objSheet->SetCellValue(chr($charCol++) . $row, '=H' . $row . '+L' . $row . '+P' . $row . '+T' . $row);

            if ($row % 2 == 0) {
                $objSheet->getStyle("A$row:U$row")->applyFromArray([
                    'fill' => [
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => [
                            'rgb' => 'F2F2F2'
                        ]
                    ]
                ]);
            }
        }

        $finish = ($monthEnableFrom - 1) + $this->_getAmountQuarter($monthEnableFrom - 1);
        $cellFinished = chr(69 + ($finish - 1));

        if (($monthEnableFrom - 1) > 1) {
            $objSheet->getStyle("E7:" . $cellFinished . "$row")->applyFromArray([
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => 'BDBDBD'
                    ],
                ],
            ]);
        }

        // SECURITY OF TEMPLATE

        // RANGE OF LOCK
        $enable = chr(69 + ($finish));
        $objSheet->getProtection()->setSheet(true)->setPassword('Corteva2020');
        $objSheet->getStyle($enable . '7:V' . $row)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

        // LOCK THE QUARTERS AND TOTAL
        $solidColor = [
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => [
                    'rgb' => 'BDBDBD'
                ]
            ]
        ];

        $columns = ['H', 'L', 'P', 'T', 'U'];
        foreach ($columns as $column) {
            $objSheet
                ->getStyle("{$column}7:{$column}{$row}")
                ->getProtection()
                ->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);

            $objSheet->getStyle("{$column}7:{$column}{$row}")->applyFromArray($solidColor);
        }

        $this->downloadExcel($objPHPExcel, $title);
    }

    public function actionGetClients($CountryId, $TradeProductId = null, $GmidId = null)
    {
        Yii::$app->response->format = 'json';

        $query = $this->getSQLQueryForGetClients(
            Yii::$app->user->identity->UserId,
            Campaign::getActualCampaign()->CampaignId,
            $CountryId,
            $TradeProductId,
            $GmidId
        );

        return $query->all();
    }

    public function actionGetEmptyProducts()
    {
        Yii::$app->response->format = 'json';

        return [];
    }

    public function actionGetProducts($CountryId)
    {
        Yii::$app->response->format = 'json';

        $UserId = Yii::$app->user->identity->UserId;

        $query = new Query();
        $query->select([
            "cp.TradeProductId",
            "cp.GmidId",
            "'Description' = IIF(vc.ValueCenterId = 10111, tp.Description, g.Description)"
        ]);
        $query->from("client_marketing_product cp");
        $query->innerJoin("pm_product pp", "cp.TradeProductId = pp.TradeProductId AND cp.GmidId = pp.GmidId");
        $query->innerJoin("gmid g", "cp.GmidId = g.GmidId");
        $query->innerJoin("trade_product tp", "cp.TradeProductId = tp.TradeProductId");
        $query->innerJoin("performance_center pc", "tp.PerformanceCenterId = pc.PerformanceCenterId");
        $query->innerJoin("value_center vc", "pc.ValueCenterId = vc.ValueCenterId");
        $query->innerJoin("pm_client cpm", "cpm.ClientId = cp.ClientMarketingId");
        $query->where("cp.IsForecastable = 1");
        $query->andWhere('pp.UserId = :UserId', [":UserId" => $UserId]);
        $query->andWhere("g.CountryId = :CountryId", [":CountryId" => $CountryId]);
        $query->andWhere('cpm.UserId = :PmId', [":PmId" => $UserId]);
        $query->groupBy([
            "IIF(vc.ValueCenterId = 10111, tp.Description, g.Description)",
            "cp.TradeProductId",
            "cp.GmidId"
        ]);

        return $query->all();
    }

    public function actionSave()
    {
        Yii::$app->response->format = 'json';

        $models = [];

        $modelsPost = Yii::$app->request->post('models', []);

        foreach ($modelsPost as $item) {
            $model = ForecastMarketing::findOne([
                'ClientMarketingProductId' => $item['ClientMarketingProductId'],
                'CampaignId' => $item['CampaignId']
            ]);

            // custom setAttributes dynamic set values from actual month
            $model->_setAttributes($item);

            $model->save();
            $models[] = $model;
        }

        if (!empty($models)) {
            Yii::$app->auditcomponents->createAudit(['UserId' => Yii::$app->user->identity->UserId,
                'ClientId' => $models[0]->ClientMarketingProductId->ClientId,
                'TypeAuditId' => TypeAudit::TYPE_SAVE_MARKETING_FORECAST,
            ]);
        }

        return $models;
    }

    public function actionExportReportConsolid()
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        $campaign = Campaign::getActualCampaign();
		
		$connection = Yii::$app->db;
        $consolidates = $connection->createCommand(
"SELECT * FROM ExportConsolidMarketing2
WHERE CampaignId = {$campaign->CampaignId} AND (Volumen <> 0 OR USD <> 0)
ORDER BY [Pais], [Nombre Product Manager], [Nombre Cliente],[Value Center], [Nombre Trade Product], [Nombre Performance], [Nombre GMID], MES ASC
OPTION (FORCE ORDER)"
        )->queryAll();

        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load('templates/Reporte_Real_Ventas_Forecast_Marketing_Consolidado.xlsx');
        $objPHPExcel->setActiveSheetIndex(0);
        $objSheet = $objPHPExcel->getActiveSheet();

        // WRITE SHEET
        $objSheet->setCellValue('A1', Yii::t("app", "Consolid Report"));
        $objSheet->setCellValue('A2', Yii::t("app", 'All Clients'));
        $objSheet->setCellValue('A3', Yii::t("app", 'Year') . ': ' . $campaign->Name);

        // WRITE PRODUCTS
        $row = 5;
        foreach ($consolidates as $key => $item) {
            $charCol = 65;
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Pais"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Product Manager"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["Nombre Product Manager"]);
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
            $objSheet->SetCellValue(chr($charCol++) . $row, $item["MES"]);
            $objSheet->SetCellValue(chr($charCol++) . $row, Yii::$app->utilcomponents->_getNumberQuarter($item["MES"]));
            $objSheet->SetCellValue(chr($charCol) . $row, $item["Volumen"]);

            if ($row % 2 == 0) {
                $objSheet->getStyle("A$row:P$row")->applyFromArray([
                    'fill' => [
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => [
                            'rgb' => 'F2F2F2'
                        ]
                    ]
                ]);
            }

            $row++;
        }

        $title = Yii::t("app", "Report Forecast Marketing Consolid") . "(" . date("Y-m-d") . ")";
        $this->downloadExcel($objPHPExcel, $title);
    }

    private function _getAmountQuarter($month)
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

    private function getSQLQueryForGetClients($userId, $CampaignId, $CountryId, $TradeProductId = null, $GmidId = null)
    {
        $query = new Query();
        $query->addSelect([
            "swfm.ClientMarketingProductId",
            "Client",
            "CampaignId",
            "swfm.TradeProductId",
            "swfm.GmidId",
            "PerformanceCenterId",
            "ValueCenterId",
            "ForecastDescription",
        ]);
        $query->from("SaleWithForecastMarketing swfm");
        $query->innerJoin("pm_product pm", "pm.TradeProductId = swfm.TradeProductId AND pm.GmidId = swfm.GmidId");
        $query->innerJoin("pm_client pmc", "pmc.ClientId = swfm.ClientMarketingId");
        $query->where([
            "CampaignId" => $CampaignId,
            "IsForecastable" => "1",
            "pm.UserId" => $userId,
            "CountryId" => $CountryId,
            "pmc.UserId" => $userId,
        ]);
        $query->andWhere("CampaignSale = :CampaignId OR CampaignSale IS NULL", [":CampaignId" => $CampaignId]);

        if (empty($TradeProductId) && empty($GmidId)) {
            $query->addSelect([
                "SUM(January) AS January",
                "SUM(February) AS February",
                "SUM(March) AS March",
                "SUM(April) AS April",
                "SUM(May) AS May",
                "SUM(June) AS June",
                "SUM(July) AS July",
                "SUM(August) AS August",
                "SUM(September) AS September",
                "SUM(October) AS October",
                "SUM(November) AS November",
                "SUM(December) AS December",
                "SUM(Q1) AS Q1",
                "SUM(Q2) AS Q2",
                "SUM(Q3) AS Q3",
                "SUM(Q4) AS Q4",
                "Total = SUM(Q1) + SUM(Q2) +  SUM(Q3) + SUM(Q4)"
            ]);

            $query->groupBy([
                "swfm.ClientMarketingProductId",
                "Client",
                "CampaignId",
                "swfm.TradeProductId",
                "swfm.GmidId",
                "PerformanceCenterId",
                "ValueCenterId",
                "ForecastDescription",
            ]);
        } else {
            $query->addSelect([
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December",
                "Q1",
                "Q2",
                "Q3",
                "Q4",
                "Total"
            ]);

            $query->andWhere([
                "swfm.TradeProductId" => $TradeProductId,
                "swfm.GmidId" => $GmidId,
            ]);
        }

        $query->orderBy("ForecastDescription");

        return $query;
    }

    private function downloadExcel(PHPExcel $objPHPExcel, string $title)
    {
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
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
}
