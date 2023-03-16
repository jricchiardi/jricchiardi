<?php

namespace frontend\controllers;

use common\components\controllers\CustomController;
use common\models\Campaign;
use common\models\ForecastMarketing;
use common\models\Setting;
use common\models\TypeAudit;
use Exception;
use PHPExcel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
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
        $user = Yii::$app->user->identity->Fullname;
        $UserId = Yii::$app->user->identity->UserId;
        $CampaignId = Campaign::getActualCampaign()->CampaignId;

        $connection = Yii::$app->db;
        $forecasts = $connection->createCommand("
SELECT      ClientMarketingProductId,
            [Client],
            ForecastDescription,
            [CampaignId],
            [swfm].[TradeProductId],
            swfm.GmidId,
            c.Description  AS Country,
            SUM(January)   AS January,
            SUM(February)  AS February,
            SUM(March)     AS March,
            SUM(April)     AS April,
            SUM(May)       AS May,
            SUM(June)      AS June,
            SUM(July)      AS July,
            SUM(August)    AS August,
            SUM(September) AS September,
            SUM(October)   AS October,
            SUM(November)  AS November,
            SUM(December)  AS December
FROM [SaleWithForecastMarketing] [swfm]
         INNER JOIN [pm_product] [pm] ON pm.TradeProductId = swfm.TradeProductId
         INNER JOIN [pm_client] [pmc] ON pmc.ClientId = swfm.ClientMarketingId
         INNER JOIN client_marketing cm on cm.ClientMarketingId = swfm.ClientMarketingId
         INNER JOIN country c ON c.CountryId = cm.CountryId
WHERE (([CampaignId] = $CampaignId) AND ([IsForecastable] = '1') AND ([pm].[UserId] = $UserId) AND ([pmc].[UserId] = $UserId)
    AND (CampaignSale = $CampaignId OR CampaignSale IS NULL))
GROUP BY ClientMarketingProductId, [Client], ForecastDescription, [CampaignId], [swfm].[TradeProductId], swfm.GmidId,
         c.Description
ORDER BY [ForecastDescription]
        ")->queryAll();

        // Read the file
        $objReader = IOFactory::createReader('Xlsx');
        $objPHPExcel = $objReader->load('templates/Forecast_Marketing_Offline_Template.xlsx');
        $objPHPExcel->getProperties()->setTitle($title);
        $objSheet = $objPHPExcel->getActiveSheet();

        // AUDIT
        Yii::$app->auditcomponents->createAudit(['UserId' => Yii::$app->user->identity->UserId,
            'TypeAuditId' => TypeAudit::TYPE_EXPORT_FORECAST_MARKETING_OFFLINE,
        ]);

        // WRITE VALUES IN TEMPLATE

        // WRITE HEADER
        $objSheet->setCellValue('C4', $UserId);
        $objSheet->setCellValue('C2', (int)$month);
        $objSheet->setCellValue('B2', Yii::$app->utilcomponents->getMonthES((int)$month));
        $objSheet->setCellValue('B3', $year);
        $objSheet->setCellValue('B4', $user);
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
            $objSheet->SetCellValue(chr($charCol) . $row, '=H' . $row . '+L' . $row . '+P' . $row . '+T' . $row);

            if ($row % 2 == 0) {
                $objSheet->getStyle("A$row:U$row")->applyFromArray([
                    'fill' => [
                        'type' => Fill::FILL_SOLID,
                        'color' => [
                            'rgb' => 'F2F2F2'
                        ]
                    ]
                ]);
            }
        }

        $monthEnableFrom = Setting::getValue(Setting::FORECAST_ENABLE_FROM);
        $finish = ($monthEnableFrom - 1) + $this->_getAmountQuarter($monthEnableFrom - 1);
        $cellFinished = chr(69 + ($finish - 1));

        if (($monthEnableFrom - 1) > 1) {
            $objSheet->getStyle("E7:" . $cellFinished . "$row")->applyFromArray([
                'fill' => [
                    'type' => Fill::FILL_SOLID,
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
                'type' => Fill::FILL_SOLID,
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

        $userId = Yii::$app->user->identity->UserId;
        $CampaignId = Campaign::getActualCampaign()->CampaignId;

        $query = new Query();
        $query->addSelect([
            "swfm.ClientMarketingProductId",
            "Client",
            "CampaignId",
            "swfm.TradeProductId",
            "PerformanceCenterId",
            "ValueCenterId",
            "ForecastDescription",
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
        $query->from("SaleWithForecastMarketing swfm");
        $query->innerJoin("pm_product pm", "pm.TradeProductId = swfm.TradeProductId");
        $query->innerJoin("pm_client pmc", "pmc.ClientId = swfm.ClientMarketingId");
        $query->innerJoin("client_marketing cm", "cm.ClientMarketingId = pmc.ClientId");
        $query->where([
            "CampaignId" => $CampaignId,
            "IsForecastable" => "1",
            "pm.UserId" => $userId,
            "pmc.UserId" => $userId,
            "cm.CountryId" => $CountryId,
        ]);
        $query->andWhere("CampaignSale = :CampaignId OR CampaignSale IS NULL", [":CampaignId" => $CampaignId]);

        if (!empty($TradeProductId)) {
            $query->andWhere("swfm.TradeProductId = $TradeProductId");
        }

        if (!empty($GmidId)) {
            $query->andWhere("swfm.GmidId = $GmidId");
        }

        $query->groupBy([
            "swfm.ClientMarketingProductId",
            "Client",
            "CampaignId",
            "swfm.TradeProductId",
            "PerformanceCenterId",
            "ValueCenterId",
            "ForecastDescription",
        ]);
        $query->orderBy("ForecastDescription");

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

        $connection = Yii::$app->db;

        return $connection->createCommand("
SELECT tp.TradeProductId,
	   null AS 'GmidId',
       tp.Description
FROM [client_marketing_product] [cp]
         INNER JOIN [pm_product] [pp] ON cp.TradeProductId = pp.TradeProductId
         INNER JOIN [trade_product] [tp] ON cp.TradeProductId = tp.TradeProductId
         INNER JOIN [performance_center] [pc] ON tp.PerformanceCenterId = pc.PerformanceCenterId
         INNER JOIN [value_center] [vc] ON pc.ValueCenterId = vc.ValueCenterId
         INNER JOIN [pm_client] [cpm] ON cpm.ClientId = cp.ClientMarketingId
         INNER JOIN client_marketing cm on cp.ClientMarketingId = cm.ClientMarketingId
WHERE (((cp.IsForecastable = 1) AND (pp.UserId = $UserId)) AND (cm.CountryId = $CountryId))
  AND (cpm.UserId = $UserId)
  AND vc.ValueCenterId = 10111
GROUP BY tp.TradeProductId, tp.Description
UNION
SELECT tp.TradeProductId,
       g.GmidId,
       g.Description
FROM [client_marketing_product] [cp]
         INNER JOIN [pm_product] [pp] ON cp.TradeProductId = pp.TradeProductId AND cp.GmidId = pp.GmidId
         INNER JOIN [gmid] [g] ON cp.GmidId = g.GmidId
         INNER JOIN [trade_product] [tp] ON cp.TradeProductId = tp.TradeProductId
         INNER JOIN [performance_center] [pc] ON tp.PerformanceCenterId = pc.PerformanceCenterId
         INNER JOIN [value_center] [vc] ON pc.ValueCenterId = vc.ValueCenterId
         INNER JOIN [pm_client] [cpm] ON cpm.ClientId = cp.ClientMarketingId
         INNER JOIN client_marketing cm on cp.ClientMarketingId = cm.ClientMarketingId
WHERE (((cp.IsForecastable = 1) AND (pp.UserId = $UserId)) AND (cm.CountryId = $CountryId))
  AND (cpm.UserId = $UserId)
  AND vc.ValueCenterId <> 10111
GROUP BY g.GmidId, tp.TradeProductId, g.Description
            ")->queryAll();
    }

    public function actionSave()
    {
        Yii::$app->response->format = 'json';

        $models = [];

        $modelsPost = Yii::$app->request->post('models', []);

        foreach ($modelsPost as $item) {
            $model = ForecastMarketing::findOne([
                'ClientMarketingProductId' => $item['ClientMarketingProductId'],
                'CampaignId' => $item['CampaignId'],
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
            "SELECT * FROM ExportConsolidMarketing
WHERE CampaignId = {$campaign->CampaignId} AND (Volumen <> 0 OR USD <> 0)
ORDER BY [Pais], [Nombre Product Manager], [Nombre Cliente],[Value Center], [Nombre Trade Product], [Nombre Performance], [Nombre GMID], MES ASC"
        )->queryAll();
//        OPTION (FORCE ORDER)

        $objReader = IOFactory::createReader('Xlsx');
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
                        'type' => Fill::FILL_SOLID,
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
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
}
