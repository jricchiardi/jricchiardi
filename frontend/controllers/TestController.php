<?php

namespace frontend\controllers;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use common\components\controllers\CustomController;
use common\components\models\FilterReporteSegmentoComercio;
use common\components\models\FilterReporteSegmentoComercioResultados;
use common\components\models\FilterReporteSegmentoNegocio;
use common\components\models\FilterReporteSegmentoNegocioResultados;
use common\models\Campaign;
use common\models\Client;
use common\models\PerformanceCenter;
use common\models\TableResume;
use common\models\TradeProduct;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PHPExcel_Style_Fill;
use Yii;
use yii\data\Pagination;

require_once Yii::$app->basePath . '/spout-3.1.0/src/Spout/Autoloader/autoload.php';

class TestController extends CustomController
{
    public function actionReporteSegmentoComercio()
    {
        $filterReporteSegmentoComercio = new FilterReporteSegmentoComercio();
        $filterReporteSegmentoComercio->TradeProductId = 0;

        return $this->render('reporte-segmento-comercio', [
            'filterReporteSegmentoComercio' => $filterReporteSegmentoComercio
        ]);
    }

    public function actionReporteSegmentoNegocio()
    {
        $filterReporteSegmentoNegocio = new FilterReporteSegmentoNegocio();

        return $this->render('reporte-segmento-negocio', [
            'filterReporteSegmentoNegocio' => $filterReporteSegmentoNegocio
        ]);
    }

    public function actionLoadSelectDsm($CountryId = null)
    {
        $data = [];

        $request = Yii::$app->request;

        if ($request->isAjax) {

            Yii::$app->response->format = 'json';

            $query = Client::find()->select(['dsm.UserId AS DsmId', 'dsm.Fullname AS FullName'])
                ->innerJoin('client_seller' . ' cs', 'client.ClientId=cs.ClientId')
                ->innerJoin('user' . ' seller', 'cs.SellerId=seller.UserId')
                ->innerJoin('user' . ' dsm', 'seller.ParentId=dsm.UserId');

            if ($CountryId !== '0') {
                $query->where(['client.CountryId' => $CountryId]);
            }

            $data = $query->distinct()->asArray()->all();
        }

        array_push($data, ['FullName' => 'TODOS', 'DsmId' => "0"]);

        return $data;
    }

    public function actionLoadSelectTam($DsmId = null)
    {
        $data = [];

        $request = Yii::$app->request;

        if ($request->isAjax) {

            Yii::$app->response->format = 'json';

            $query = Client::find()->select(['seller.UserId AS TamId', 'seller.Fullname AS FullName'])
                ->innerJoin('client_seller cs', 'client.ClientId=cs.ClientId')
                ->innerJoin('user seller', 'cs.SellerId=seller.UserId')
                ->innerJoin('user dsm', 'seller.ParentId=dsm.UserId');

            if ($DsmId !== '0') {
                $query->where(['seller.ParentId' => $DsmId]);
            }

            $data = $query->distinct()->asArray()->all();
        }

        array_push($data, ['FullName' => 'TODOS', 'TamId' => "0"]);

        return $data;
    }

    public function actionLoadSelectPerformanceCenter($ValueCenterId = null)
    {
        $data = [];

        $request = Yii::$app->request;

        if ($request->isAjax) {

            Yii::$app->response->format = 'json';

            $query = PerformanceCenter::find()->select(['PerformanceCenterId', 'Description']);

            if ($ValueCenterId !== '0') {
                $query->where(['ValueCenterId' => $ValueCenterId]);
            }

            $data = $query->orderBy('Description ASC')->asArray()->all();
        }

        array_push($data, array('Description' => 'TODOS', 'PerformanceCenterId' => "0"));

        return $data;
    }

    public function actionLoadSelectTradeProduct($PerformanceCenterId = null)
    {
        $data = [];

        $request = Yii::$app->request;

        if ($request->isAjax) {

            Yii::$app->response->format = 'json';

            $query = TradeProduct::find()->select(['TradeProductId', 'Description']);

            if ($PerformanceCenterId !== '0') {
                $query->where(['PerformanceCenterId' => $PerformanceCenterId]);
            }

            $data = $query->orderBy('Description ASC')->asArray()->all();
        }

        array_push($data, array('Description' => 'TODOS', 'TradeProductId' => "0"));

        return $data;
    }

    public function actionLoadSelectBusinessSegment($PerformanceCenterId = null)
    {
        $data = [];

        $request = Yii::$app->request;

        if ($request->isAjax) {

            Yii::$app->response->format = 'json';

            // $query = TradeProduct::find()->select(['TradeProductId', 'Description']);

            // if ($PerformanceCenterId !== '0') {
            //     $query->where(['PerformanceCenterId' => $PerformanceCenterId]);
            // }

            // $data = $query->orderBy('Description ASC')->asArray()->all();
        }

        array_push($data, array('Description' => 'TODOS', 'BusinessSegmentId' => "0"));

        return $data;
    }

    public function actionLoadSelectSubBusinessSegment($BusinessSegmentId = null)
    {
        $data = [];

        $request = Yii::$app->request;

        if ($request->isAjax) {

            Yii::$app->response->format = 'json';

            // $query = TradeProduct::find()->select(['TradeProductId', 'Description']);

            // if ($PerformanceCenterId !== '0') {
            //     $query->where(['PerformanceCenterId' => $PerformanceCenterId]);
            // }

            // $data = $query->orderBy('Description ASC')->asArray()->all();
        }

        array_push($data, array('Description' => 'TODOS', 'SubBusinessSegmentId' => "0"));

        return $data;
    }

    public function actionReporteSegmentoComercioResultados()
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        $filterData = Yii::$app->request->post('FilterReporteSegmentoComercio');

        if (is_null($filterData)) {
            $filterData = Yii::$app->request->post('FilterReporteSegmentoComercioResultados');
        }

        $TamId = $filterData['TamId'];
        $CountryId = $filterData['CountryId'];
        $ValueCenterId = $filterData['ValueCenterId'];
        $PerformanceCenterId = $filterData['PerformanceCenterId'];
        $TradeProductId = $filterData['TradeProductId'];
        $CampaignId = $filterData['CampaignId'];
        $QuarterId = $filterData['QuarterId'];

        $selectByQuarterId = [
            'TableResume.TotalPlanVolume as Plan',
            'TableResume.TotalForecastMoreSaleVolume as VolInput',
            'TableResume.TotalForecastMoreSaleUSD as USDInput',
            'Vol = (sf.January + sf.February + sf.March + sf.April + sf.May + sf.June +sf.July + sf.August + sf.September + sf.October + sf.November + sf.December)',
            'USDFacturacion = (sf.JanuaryUSD + sf.FebruaryUSD + sf.MarchUSD + sf.AprilUSD + sf.MayUSD + sf.JuneUSD + sf.JulyUSD + sf.AugustUSD + sf.SeptemberUSD + sf.OctoberUSD + sf.NovemberUSD + sf.DecemberUSD)',
        ];

        if ($QuarterId === 'Q1') {
            $selectByQuarterId = [
                'TableResume.Q1PlanVolume as Plan',
                'TableResume.Q1ForecastMoreSaleVolume as VolInput',
                'TableResume.Q1ForecastMoreSaleUSD as USDInput',
                'sf.Q1 as Vol',
                'sf.Q1USD as USDFacturacion',
            ];
        } else if ($QuarterId === 'Q2') {
            $selectByQuarterId = [
                'TableResume.Q2PlanVolume as Plan',
                'TableResume.Q2ForecastMoreSaleVolume as VolInput',
                'TableResume.Q2ForecastMoreSaleUSD as USDInput',
                'sf.Q2 as Vol',
                'sf.Q2USD as USDFacturacion',
            ];
        } else if ($QuarterId === 'Q3') {
            $selectByQuarterId = [
                'TableResume.Q3PlanVolume as Plan',
                'TableResume.Q3ForecastMoreSaleVolume as VolInput',
                'TableResume.Q3ForecastMoreSaleUSD as USDInput',
                'sf.Q3 as Vol',
                'sf.Q3USD as USDFacturacion',
            ];
        } else if ($QuarterId === 'Q4') {
            $selectByQuarterId = [
                'TableResume.Q4PlanVolume as Plan',
                'TableResume.Q4ForecastMoreSaleVolume as VolInput',
                'TableResume.Q4ForecastMoreSaleUSD as USDInput',
                'sf.Q4 as Vol',
                'sf.Q4USD as USDFacturacion',
            ];
        }

        $query = TableResume::find()
            ->select(array_merge([
                'c.Description as Cliente',
                'gmid.Description as GMID',
                'opportunity.Amount as Oportunidad',
            ], $selectByQuarterId))
            ->innerJoin('client c', 'c.ClientId = TableResume.ClientId')
            ->innerJoin('client_product cp', 'cp.TradeProductId = TableResume.TradeProductId AND cp.ClientId = TableResume.ClientId')
            ->leftJoin('SaleFormat sf', 'sf.ClientProductId = cp.ClientProductId AND sf.ClientId = TableResume.ClientId AND sf.GmidID = cp.GmidId AND sf.TradeProductId = cp.TradeProductId AND sf.CampaignId = TableResume.CampaignId')
            ->leftJoin('opportunity', 'opportunity.ClientProductId = cp.ClientProductId AND opportunity.CampaignId = TableResume.CampaignId')
            ->innerJoin('gmid', 'gmid.GmidId = cp.GmidId');

        $where = [
            'c.isGroup' => false,
            'c.isActive' => true,
        ];

        if ($TamId !== '0') {
            $where['TableResume.SellerId'] = $TamId;
        }

        if ($PerformanceCenterId !== '0') {
            $where['TableResume.PerformanceCenterId'] = $PerformanceCenterId;
        }

        if ($TradeProductId !== '0') {
            $where['TableResume.TradeProductId'] = $TradeProductId;
        }

        if ($CountryId !== '0') {
            $where['gmid.CountryId'] = $CountryId;
        }

        $campaignComparision = Campaign::find()->select(['CampaignId', 'Name'])->where(['CampaignId' => $CampaignId])->asArray()->one();

        $whereComparision = array_merge($where, [
            'TableResume.CampaignId' => $campaignComparision['CampaignId'],
        ]);

        $campaignActual = Campaign::find()->select(['CampaignId', 'Name'])->where(['IsActual' => true])->asArray()->one();

        $whereActual = array_merge($where, [
            'TableResume.CampaignId' => $campaignActual['CampaignId'],
        ]);

        if (Yii::$app->request->post('do-export') === "1") {
            $dataComparision = $query->where($whereComparision)
                ->orderBy('Cliente')
                ->asArray()->all();

            $dataActual = $query->where($whereActual)
                ->orderBy('Cliente')
                ->asArray()->all();

            $writer = WriterEntityFactory::createXLSXWriter();
            $writer->openToBrowser("Reporte_Test.xlsx");

            $writer->addRow(WriterEntityFactory::createRowFromArray([
                "Distribuidor/Cliente",
                "Presentación",
                "Oportunidad",
                "Plan Anual",
                "Forecast (Vol)",
                "Forecast (USD)",
                "Real Venta (Vol)",
                "Real Venta (USD)",
                "Sell out (Vol)",
                "Carry-in",
                "Oportunidad",
                "Plan Anual",
                "Forecast (Vol)",
                "Forecast (USD)",
                "Actual Venta (Vol)",
                "Actual Venta (USD)",
                "Sell out (Vol)",
                "Carry-out",
            ]));

            foreach ($dataActual as $key => $item) {
                $row = [
                    $dataActual[$key]['Cliente'],
                    $dataActual[$key]['GMID'],
                ];

                if (isset($dataComparision[$key])) {
                    array_push($row,
                        (int)$dataComparision[$key]['Oportunidad'] ?? '0',
                        (int)$dataComparision[$key]['Plan'] ?? '0',
                        (int)$dataComparision[$key]['VolInput'] ?? '0',
                        (float)$dataComparision[$key]['USDInput'] ?? '0',
                        (int)$dataComparision[$key]['Vol'] ?? '0',
                        (float)$dataComparision[$key]['USDFacturacion'] ?? '0'
                    );
                } else {
                    for ($i = 0; $i < 6; $i++) {
                        array_push($row, 'n/a');
                    }
                }

                array_push($row, 'NTH', 'NTH');

                if (isset($dataActual[$key])) {
                    array_push($row,
                        (int)$dataActual[$key]['Oportunidad'] ?? '0',
                        (int)$dataActual[$key]['Plan'] ?? '0',
                        (int)$dataActual[$key]['VolInput'] ?? '0',
                        (float)$dataActual[$key]['USDInput'] ?? '0',
                        (int)$dataActual[$key]['Vol'] ?? '0',
                        (float)$dataActual[$key]['USDFacturacion'] ?? '0'
                    );
                } else {
                    for ($i = 0; $i < 6; $i++) {
                        array_push($row, 'n/a');
                    }
                }

                array_push($row, 'NTH', 'NTH');

                $writer->addRow(WriterEntityFactory::createRowFromArray($row));
            }

            $writer->close();
        } else {
            $count = $query->where($whereActual)->count();
            $pagination = new Pagination([
                'totalCount' => $count,
                'pageSize' => 50,
                'page' => Yii::$app->request->post('page', 0),
            ]);

            $dataComparision = $query->where($whereComparision)
                ->orderBy('Cliente')
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->asArray()->all();

            $dataActual = $query->where($whereActual)
                ->orderBy('Cliente')
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->asArray()->all();

            $filterReporteSegmentoComercioResultados = new FilterReporteSegmentoComercioResultados();
            $filterReporteSegmentoComercioResultados->TradeProductId = $TradeProductId;
            $filterReporteSegmentoComercioResultados->TamId = $TamId;
            $filterReporteSegmentoComercioResultados->PerformanceCenterId = $PerformanceCenterId;
            $filterReporteSegmentoComercioResultados->CampaignId = $CampaignId;
            $filterReporteSegmentoComercioResultados->CountryId = $CountryId;
            $filterReporteSegmentoComercioResultados->QuarterId = $QuarterId;
            $filterReporteSegmentoComercioResultados->ValueCenterId = $ValueCenterId;

            $queryPerformanceCenters = PerformanceCenter::find()->select(['PerformanceCenterId', 'Description']);
            if ($ValueCenterId !== '0') {
                $queryPerformanceCenters->where(['ValueCenterId' => $ValueCenterId]);
            }
            $performanceCenters = $queryPerformanceCenters->orderBy('Description ASC')->asArray()->all();
            array_push($performanceCenters, array('Description' => 'TODOS', 'PerformanceCenterId' => "0"));

            $queryTradeProducts = TradeProduct::find()->select(['TradeProductId', 'Description']);
            if ($PerformanceCenterId !== '0') {
                $queryTradeProducts->where(['PerformanceCenterId' => $PerformanceCenterId]);
            }
            $tradeProducts = $queryTradeProducts->orderBy('Description ASC')->asArray()->all();
            array_push($tradeProducts, array('Description' => 'TODOS', 'TradeProductId' => "0"));

            return $this->render('reporte-segmento-comercio-resultados', [
                'filterReporteSegmentoComercioResultados' => $filterReporteSegmentoComercioResultados,
                'campaignComparision' => $campaignComparision,
                'campaignActual' => $campaignActual,
                'dataComparision' => $dataComparision,
                'dataActual' => $dataActual,
                'performanceCenters' => $performanceCenters,
                'tradeProducts' => $tradeProducts,
                'pagination' => $pagination,
            ]);
        }
    }

    public function actionReporteSegmentoNegocioResultados()
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        $filterData = Yii::$app->request->post('FilterReporteSegmentoNegocio');

        if (is_null($filterData)) {
            $filterData = Yii::$app->request->post('FilterReporteSegmentoNegocioResultados');
        }

        $TamId = $filterData['TamId'];
        $CountryId = $filterData['CountryId'];
        $PerformanceCenterId = $filterData['PerformanceCenterId'];
        $BusinessSegmentId = $filterData['BusinessSegmentId'];
        $SubBusinessSegmentId = $filterData['SubBusinessSegmentId'];
        $CampaignId = $filterData['CampaignId'];
        $QuarterId = $filterData['QuarterId'];

        $campaignComparision = Campaign::find()->select(['CampaignId', 'Name'])->where(['CampaignId' => $CampaignId])->asArray()->one();

        $dataComparision = [];

        $campaignActual = Campaign::find()->select(['CampaignId', 'Name'])->where(['IsActual' => true])->asArray()->one();

        $dataActual = [];

        if (Yii::$app->request->post('do-export') === "1") {
            // Read the file
            $objReader = IOFactory::createReader('Xlsx');
            $objPHPExcel = $objReader->load('templates/Reporte_Test.xlsx');
            $objPHPExcel->setActiveSheetIndex(0);
            $objSheet = $objPHPExcel->getActiveSheet();
            $title = 'Reporte_Test';

            $objSheet->SetCellValue("A6", $campaignComparision['Name']);
            $objSheet->SetCellValue("J6", $campaignActual['Name']);

            $row = 8;
            foreach ($dataActual as $key => $item) {
                $charCol = 65;

                $objSheet->SetCellValue(chr($charCol++) . $row, isset($dataActual[$key]) ? $dataActual[$key]['Cliente'] : 'n/a');
                $objSheet->SetCellValue(chr($charCol++) . $row, isset($dataActual[$key]) ? $dataActual[$key]['GMID'] : 'n/a');
                $objSheet->SetCellValue(chr($charCol++) . $row, isset($dataComparision[$key]) ? $dataComparision[$key]['Oportunidad'] : 'n/a');
                $objSheet->SetCellValue(chr($charCol++) . $row, isset($dataComparision[$key]) ? $dataComparision[$key]['Plan'] : 'n/a');
                $objSheet->SetCellValue(chr($charCol++) . $row, isset($dataComparision[$key]) ? number_format($dataComparision[$key]['VolInput']) : 'n/a');
                $objSheet->SetCellValue(chr($charCol++) . $row, isset($dataComparision[$key]) ? number_format($dataComparision[$key]['USDInput'], 2) : 'n/a');
                $objSheet->SetCellValue(chr($charCol++) . $row, isset($dataComparision[$key]) ? number_format($dataComparision[$key]['Vol']) : 'n/a');
                $objSheet->SetCellValue(chr($charCol++) . $row, isset($dataComparision[$key]) ? number_format($dataComparision[$key]['USDFacturacion'], 2) : 'n/a');
                $objSheet->SetCellValue(chr($charCol++) . $row, "NTH");

                $objSheet->SetCellValue(chr($charCol++) . $row, "NTH");
                $objSheet->SetCellValue(chr($charCol++) . $row, isset($dataActual[$key]) ? $dataActual[$key]['Oportunidad'] : 'n/a');
                $objSheet->SetCellValue(chr($charCol++) . $row, isset($dataActual[$key]) ? $dataActual[$key]['Plan'] : 'n/a');
                $objSheet->SetCellValue(chr($charCol++) . $row, isset($dataActual[$key]) ? number_format($dataActual[$key]['VolInput']) : 'n/a');
                $objSheet->SetCellValue(chr($charCol++) . $row, isset($dataActual[$key]) ? number_format($dataActual[$key]['USDInput'], 2) : 'n/a');
                $objSheet->SetCellValue(chr($charCol++) . $row, isset($dataActual[$key]) ? number_format($dataActual[$key]['Vol']) : 'n/a');
                $objSheet->SetCellValue(chr($charCol++) . $row, isset($dataActual[$key]) ? number_format($dataActual[$key]['USDFacturacion'], 2) : 'n/a');
                $objSheet->SetCellValue(chr($charCol++) . $row, "NTH");
                $objSheet->SetCellValue(chr($charCol++) . $row, "NTH");

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
        } else {
            $filterReporteSegmentoNegocioResultados = new FilterReporteSegmentoNegocioResultados();
            $filterReporteSegmentoNegocioResultados->TamId = $TamId;
            $filterReporteSegmentoNegocioResultados->PerformanceCenterId = $PerformanceCenterId;
            $filterReporteSegmentoNegocioResultados->BusinessSegmentId = $BusinessSegmentId;
            $filterReporteSegmentoNegocioResultados->SubBusinessSegmentId = $SubBusinessSegmentId;
            $filterReporteSegmentoNegocioResultados->CampaignId = $CampaignId;
            $filterReporteSegmentoNegocioResultados->CountryId = $CountryId;
            $filterReporteSegmentoNegocioResultados->QuarterId = $QuarterId;

            return $this->render('reporte-segmento-negocio-resultados', [
                'filterReporteSegmentoNegocioResultados' => $filterReporteSegmentoNegocioResultados,
                'campaignComparision' => $campaignComparision,
                'campaignActual' => $campaignActual,
                'dataComparision' => $dataComparision,
                'dataActual' => $dataActual,
                // 'pagination' => $pagination,
            ]);
        }
    }
}
