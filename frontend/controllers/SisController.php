<?php

namespace frontend\controllers;

use common\components\controllers\CustomController;
use common\models\Campaign;
use common\models\Country;
use common\models\Gmid;
use common\models\InvoiceNotCounted;
use common\models\Sis;
use common\models\sis\SisFilters;
use common\models\sis\SisGrid;
use common\models\SisSearch;
use frontend\traits\UseDownloadExcel;
use PHPExcel_Style_NumberFormat;
use Yii;
use yii\helpers\Json;
use yii\web\Response;

class SisController extends CustomController
{
    use UseDownloadExcel;

    public function actionIndex()
    {
        return $this->render('index', [
            'campaigns' => Campaign::findAll(['IsFuture'=>'0']),
            'countries' => Country::find()->all(),
        ]);
    }

    public function actionDetail()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $productName = '';
        if((new SisFilters())->hasProductFilter()){
            $gmidId = (new SisFilters())->getFilterProduct();
            $productName = Gmid::find()->where(['GmidId'=>$gmidId])->one()->Description;
        }
        $sisSearch = new SisSearch();

        $tableResultsDetail = $sisSearch->getResults();
        return [
            'message' => 'success',
            'product' => $productName,
            'data' => $tableResultsDetail,
            'userColumn' => SisGrid::getUserColumnName(),
            'metaData' => $sisSearch->getMetaData(),
        ];
    }

    public function actionDownload()
    {
        $title = "SIS";
        $items = (new SisSearch())->getResults();
        $totals = [
            'Usuario' => 'Total',
            'SaleInput' => 0,
            'Forecast' => 0,
            'FactPendiente' => 0,
            'ContPendiente' => 0,
            'RealSale' => 0,
            'CyO' => 0,
            'Pedidos' => 0,
            'PedidosFuturos' => 0,
            'SaldoParaIngresar' => 0,
            'SaldoParaDespacho' => 0,
            'SaldoParaDespachoPerc' => 0,
            'SaldoAjustado' => 0,
            'SaldoAjustadoPerc' => 0,
        ];
        foreach ($items as $item) {
            $totals['SaleInput'] += $item['SaleInput'];
            $totals['Forecast'] += $item['Forecast'];
            $totals['FactPendiente'] += $item['FactPendiente'];
            $totals['ContPendiente'] += $item['ContPendiente'];
            $totals['RealSale'] += $item['RealSale'];
            $totals['CyO'] += $item['CyO'];
            $totals['Pedidos'] += $item['Pedidos'];
            $totals['PedidosFuturos'] += $item['PedidosFuturos'];
            $totals['SaldoParaIngresar'] += $item['SaldoParaIngresar'];
            $totals['SaldoParaDespacho'] += $item['SaldoParaDespacho'];
            $totals['SaldoParaDespachoPerc'] += $item['SaldoParaDespachoPerc'];
            $totals['SaldoAjustado'] += $item['SaldoAjustado'];
            $totals['SaldoAjustadoPerc'] += $item['SaldoAjustadoPerc'];
        }
        $items[] = $totals;

        $items = json_decode(json_encode($items));
        $attributes = [
            'Usuario' => 'Usuario',
            'SaleInput' => [
                'name'=>'Input Ventas',
                'format' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            ],
            'Forecast' => [
                'name'=>'Forecast S&OP',
                'format' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            ],
            'FactPendiente' => [
                'name'=>'Factuacion Pendiente',
                'format' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            ],
            'ContPendiente' => [
                'name'=>'Contabilizacion Pendiente',
                'format' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            ],
            'RealSale' => [
                'name'=>'Ventas',
                'format' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            ],
            'Pedidos' => [
                'name'=>'Pedidos',
                'format' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            ],
            'PedidosFuturos' => [
                'name'=>'Pedidos Futuros',
                'format' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            ],
            'CyO' => [
                'name'=>'Cuenta y Orden',
                'format' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            ],
            'SaldoParaIngresar' => [
                'name'=>'Saldo para ingresar',
                'format' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            ],
            'SaldoParaDespacho' => [
                'name'=>'Saldo para despacho',
                'format' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            ],
            'SaldoParaDespachoPerc' => [
                'name'=>'Saldo para despacho %',
                'format' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00
            ],
            'SaldoAjustado' => [
                'name'=>'Saldo ajustado',
                'format' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            ],
            'SaldoAjustadoPerc' => [
                'name'=>'Saldo ajustado %',
                'format' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00
            ],
        ];

        $this->downloadExcel($items, $attributes, $title);
    }

}
