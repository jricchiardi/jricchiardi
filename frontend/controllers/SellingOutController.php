<?php

namespace frontend\controllers;

set_include_path(get_include_path() . PATH_SEPARATOR . "..");
include_once("xlsxwriter.class.php");

use common\components\controllers\CustomController;
use common\models\Campaign;
use Yii;

class SellingOutController extends CustomController
{
    public function actionDownload()
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        $CampaignId = Campaign::getActualCampaign()->CampaignId;

        $connection = Yii::$app->db;
        $plans = $connection->createCommand("SELECT sp.ClientProductId as '#',
                                                       Country as 'Pais',
                                                       DsmId as 'DSM',
                                                       DSM as 'Nombre DSM',
                                                       SellerDowId as 'Vendedor',
                                                       SellerName as 'Nombre Vendedor',
                                                       ClientId as 'Cliente',
                                                       Client as 'Nombre Cliente',
                                                       ClientType as 'Clasificacion',
                                                       ValueCenter as 'Value Center',
                                                       PerformanceCenter as 'Performance',
                                                       PlanDescription as 'Descripcion',
                                                       cam.Name as 'Año',
                                                       Amount as 'Cantidad'
                                                FROM dbo.SaleWithPlan sp
                                                INNER JOIN dbo.selling_out so ON sp.ClientProductId = so.ClientProductId
                                                INNER JOIN dbo.Campaign cam ON sp.CampaignId = cam.CampaignId
                                                WHERE sp.CampaignId = {$CampaignId} AND IsForecastable = 1 AND Total > 0
                                                ORDER BY PlanDescription ASC
                                              ")->queryAll();

        $writer = new \XLSXWriter();

        $styles1 = ['font' => 'Calibri', 'font-size' => 11, 'font-style' => 'bold,italic', 'fill' => '#dce6f2',];

        $arr = [];
        for ($i = 0; $i < 12; $i++) {
            $arr[] = '';
        }
        $row1 = array_merge(['Plantilla Selling Out', ''], $arr);
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
        for ($i = 0; $i < 14; $i++) {
            $arr2[] = $styles2;
        }
        $arr2[13] = ['font' => 'Calibri', 'font-size' => 12, 'font-style' => 'bold', 'fill' => '#fac090', 'halign' => 'center', 'border' => 'left,right,top,bottom'];
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
            'Año',
            'Cantidad'
        ];
        $writer->writeSheetRow('Sheet1', $row6, $arr2);

        $arr3 = [];
        for ($i = 0; $i < 14; $i++) {
            $arr3[] = [];
        }

        $rowNum = 7;
        foreach ($plans as $row) {
            $styleOfRow = $arr3;

            if ($rowNum % 2 == 0) {
                $styleOfRow = [];
                for ($i = 0; $i < 14; $i++) {
                    $styleOfRow[] = ['fill' => '#f2f2f2',];
                }
            }
            $styleOfRow[13] = ['fill' => '#fabf8f',];

            $writer->writeSheetRow('Sheet1', $row, $styleOfRow);

            $rowNum++;
        }

        $title = "Template Selling Out (" . date("Y-m-d") . ").xls";

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
        return $this->redirect(['import/opportunity']);
    }
}