<?php

namespace common\models\sis;

use Kendo\Data\DataSource;
use Kendo\Data\DataSourceAggregateItem;
use Kendo\JavaScriptFunction;
use Kendo\UI\Grid;
use Kendo\UI\GridColumn;

class SisGrid
{
    public static function getGrid($data){

        // define the columns
        $userField = new GridColumn();
        $userField->field('Usuario');
        $userField->title(self::getUserColumnName());
        $userField->footerTemplate('Total');

        $planSum = new DataSourceAggregateItem();
        $planSum->field("Plan");
        $planSum->aggregate("sum");
        $plan = new GridColumn();
        $plan->field('Plan');
        $plan->title('Plan');
        $plan->footerTemplate('#: kendo.toString(sum, "0,") #');
		
        $saleInputSum = new DataSourceAggregateItem();
        $saleInputSum->field("SaleInput");
        $saleInputSum->aggregate("sum");
        $saleInputField = new GridColumn();
        $saleInputField->field('SaleInput');
        $saleInputField->title('Input Ventas');
        $saleInputField->footerTemplate('#: kendo.toString(sum, "0,") #');

        $forecastSum = new DataSourceAggregateItem();
        $forecastSum->field("Forecast");
        $forecastSum->aggregate("sum");
        $forecast = new GridColumn();
        $forecast->field('Forecast');
        $forecast->title('Forecast S&OP');
        $forecast->footerTemplate('#: kendo.toString(sum, "0,") #');

        $factPendienteSum = new DataSourceAggregateItem();
        $factPendienteSum->field("FactPendiente");
        $factPendienteSum->aggregate("sum");
        $factPendiente = new GridColumn();
        $factPendiente->field('FactPendiente');
        $factPendiente->title('Facturacion Pendiente');
        $factPendiente->footerTemplate('#: kendo.toString(sum, "0,") #');

        $contPendienteSum = new DataSourceAggregateItem();
        $contPendienteSum->field("ContPendiente");
        $contPendienteSum->aggregate("sum");
        $contPendiente = new GridColumn();
        $contPendiente->field('ContPendiente');
        $contPendiente->title('Contabilizacion Pendiente');
        $contPendiente->footerTemplate('#: kendo.toString(sum, "0,") #');

        $realSaleSum = new DataSourceAggregateItem();
        $realSaleSum->field("RealSale");
        $realSaleSum->aggregate("sum");
        $realSale = new GridColumn();
        $realSale->field('RealSale');
        $realSale->title('Ventas');
        $realSale->footerTemplate('#: kendo.toString(sum, "0,") #');

        $cyoTotalSum = new DataSourceAggregateItem();
        $cyoTotalSum->field("CyO");
        $cyoTotalSum->aggregate("sum");
        $cyoTotal = new GridColumn();
        $cyoTotal->field('CyO');
        $cyoTotal->title('Cuenta y Orden');
        $cyoTotal->footerTemplate('#: kendo.toString(sum, "0,") #');

        $pedidosSum = new DataSourceAggregateItem();
        $pedidosSum->field("Pedidos");
        $pedidosSum->aggregate("sum");
        $pedidos = new GridColumn();
        $pedidos->field('Pedidos');
        $pedidos->title('Pedidos');
        $pedidos->footerTemplate('#: kendo.toString(sum, "0,") #');

        $pedidosFuturosSum = new DataSourceAggregateItem();
        $pedidosFuturosSum->field("PedidosFuturos");
        $pedidosFuturosSum->aggregate("sum");
        $pedidosFuturos = new GridColumn();
        $pedidosFuturos->field('PedidosFuturos');
        $pedidosFuturos->title('Pedidos Futuros');
        $pedidosFuturos->footerTemplate('#: kendo.toString(sum, "0,") #');

        $saldoParaIngresarSum = new DataSourceAggregateItem();
        $saldoParaIngresarSum->field("SaldoParaIngresar");
        $saldoParaIngresarSum->aggregate("sum");
        $saldoParaIngresar = new GridColumn();
        $saldoParaIngresar->field('SaldoParaIngresar');
        $saldoParaIngresar->title('Saldo para ingresar');
        $saldoParaIngresar->footerTemplate('#: kendo.toString(sum, "0,") #');


        $saldoParaDespachoSum = new DataSourceAggregateItem();
        $saldoParaDespachoSum->field("SaldoParaDespacho");
        $saldoParaDespachoSum->aggregate("sum");
        $saldoParaDespacho = new GridColumn();
        $saldoParaDespacho->field('SaldoParaDespacho');
        $saldoParaDespacho->title('Saldo para despacho');
        $saldoParaDespacho->footerTemplate('#: kendo.toString(sum, "0,") #');

        $saldoParaDespachoPerc = new GridColumn();
        $saldoParaDespachoPerc->title('Saldo para despacho %');

        $saldoAjustadoSum = new DataSourceAggregateItem();
        $saldoAjustadoSum->field("SaldoAjustado");
        $saldoAjustadoSum->aggregate("sum");
        $saldoAjustado = new GridColumn();
        $saldoAjustado->field('SaldoAjustado');
        $saldoAjustado->title('Saldo ajustado');
        $saldoAjustado->footerTemplate('#: kendo.toString(sum, "0,") #');

        $saldoAjustadoPerc = new GridColumn();
        $saldoAjustadoPerc->title('Saldo ajustado %');

        // load array with information
        $dataSource = new DataSource();
        $dataSource->data($data);
        $dataSource->pageSize(15);
        $dataSource->addAggregateItem(
			$planSum,
            $saleInputSum,
            $forecastSum,
            $factPendienteSum,
            $contPendienteSum,
            $realSaleSum,
            $pedidosSum,
            $pedidosFuturosSum,
            $cyoTotalSum,
            $saldoParaIngresarSum,
            $saldoParaDespachoSum,
            $saldoAjustadoSum
        );

        // define grid and bindings
        $grid = new Grid('grid');

        $grid->addColumn(
            $userField,
			$plan,
            $saleInputField,
            $forecast,
            $factPendiente,
            $contPendiente,
            $realSale,
            $pedidos,
            $pedidosFuturos,
            $cyoTotal,
            $saldoParaIngresar,
            $saldoParaDespacho,
            $saldoParaDespachoPerc,
            $saldoAjustado,
            $saldoAjustadoPerc
        )
            ->dataSource($dataSource)
            ->rowTemplateId('myTemplate')
            ->navigatable(true)
            ->scrollable(false)
            ->editable(false)
            ->pageable(true)
            ->dataBound('rowClick');
        return $grid;

    }
	
    static function getUserColumnName(){
        $value = strtoupper((new SisFilters())->getFilterUserLevel());
        if($value == 'CLIENT') {
            return 'Cliente';
        }
        if($value == 'PRODUCT') {
            return 'Producto';
        }
        return $value;
    }
}