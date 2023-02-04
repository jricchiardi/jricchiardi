<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

?>

<div class="row">

    <div class="col-md-12">
        <h1>Reporte generado</h1>
    </div>

    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">Parámetros</div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id' => "form",
                ]); ?>

                <?= $form->field($filterReporteSegmentoComercioResultados, 'PerformanceCenterId') ?>
                <?= $form->field($filterReporteSegmentoComercioResultados, 'TradeProductId') ?>

                <?= Html::activeHiddenInput($filterReporteSegmentoComercioResultados, 'TamId'); ?>
                <?= Html::activeHiddenInput($filterReporteSegmentoComercioResultados, 'CampaignId'); ?>
                <?= Html::activeHiddenInput($filterReporteSegmentoComercioResultados, 'CountryId'); ?>
                <?= Html::activeHiddenInput($filterReporteSegmentoComercioResultados, 'QuarterId'); ?>
                <?= Html::activeHiddenInput($filterReporteSegmentoComercioResultados, 'ValueCenterId'); ?>

                <button name="export" id="export"
                        style=" width='40px';background:url('<?= Yii::$app->urlManager->baseUrl ?>/images/download.gif'); float:left; display:block; width: 40px;height: 38px;border: none;background-size: contain;background-repeat: no-repeat; cursor:pointer;"></button>

                <button name="actualizar" id="actualizar" type="submit"
                        class="btn btn-primary btn-nuevo-reclamo pull-right">Actualizar
                </button>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

    <div class="col-md-9">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th colspan="2"></th>
                <th colspan="6">
                    <center><h4><?= $campaignComparision['Name'] ?></h4></center>
                </th>
                <th colspan="6">
                    <center><h4><?= $campaignActual['Name'] ?></h4></center>
                </th>
            </tr>
            <tr>
                <th colspan="1">
                    <center><b>Distribuidor/Cliente</b></center>
                </th>
                <th colspan="1">
                    <center><b>Presentacion</b></center>
                </th>
                <th colspan="1">
                    <center><b>Oportunidad</b></center>
                </th>
                <th colspan="1">
                    <center><b>Plan Anual</b></center>
                </th>
                <th colspan="1">
                    <center><b>Forecast (Vol)</b></center>
                </th>
                <th colspan="1">
                    <center><b>Forecast (USD)</b></center>
                </th>
                <th colspan="1">
                    <center><b>Real Venta (Vol)</b></center>
                </th>
                <th colspan="1">
                    <center><b>Real Venta (USD)</b></center>
                </th>
                <th colspan="1" style='display:none;'>
                    <center><b>Sell out (Vol)</b></center>
                </th>

                <th colspan="1" style='display:none;'>
                    <center><b>Carry-in</b></center>
                </th>
                <th colspan="1">
                    <center><b>Oportunidad</b></center>
                </th>
                <th colspan="1">
                    <center><b>Plan Anual</b></center>
                </th>
                <th colspan="1">
                    <center><b>Forecast (Vol)</b></center>
                </th>
                <th colspan="1">
                    <center><b>Forecast (USD)</b></center>
                </th>
                <th colspan="1">
                    <center><b>Actual Venta (Vol)</b></center>
                </th>
                <th colspan="1">
                    <center><b>Actual Venta (USD)</b></center>
                </th>
                <th colspan="1" style='display:none;'>
                    <center><b>Sell out (Vol)</b></center>
                </th>
                <th colspan="1" style='display:none;'>
                    <center><b>Carry-out</b></center>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($dataActual as $key => $value) {
                $bg_color = $key % 2 === 0 ? "success" : "info";
                ?>
                <tr class=<?= $bg_color; ?>>
                    <td><?= isset($dataActual[$key]) ? $dataActual[$key]['Cliente'] : 'n/a'; ?></td>
                    <td><?= isset($dataActual[$key]) ? $dataActual[$key]['GMID'] : 'n/a'; ?></td>
                    <td><?= isset($dataComparision[$key]) ? $dataComparision[$key]['Oportunidad'] : 'n/a'; ?></td>
                    <td><?= isset($dataComparision[$key]) ? $dataComparision[$key]['Plan'] : 'n/a'; ?></td>
                    <td><?= isset($dataComparision[$key]) ? number_format($dataComparision[$key]['VolInput']) : 'n/a' ?> </td>
                    <td><?= isset($dataComparision[$key]) ? number_format($dataComparision[$key]['USDInput'], 2) : 'n/a' ?> </td>
                    <td><?= isset($dataComparision[$key]) ? number_format($dataComparision[$key]['Vol']) : 'n/a' ?> </td>
                    <td><?= isset($dataComparision[$key]) ? number_format($dataComparision[$key]['USDFacturacion'], 2) : 'n/a' ?> </td>
                    <td style='display:none;'><?= "NTH" /*number_format(20) */ ?> </td>

                    <td style='display:none;'><?= "NTH" /*number_format(20) */ ?> </td>
                    <td><?= isset($dataActual[$key]) ? $dataActual[$key]['Oportunidad'] : 'n/a'; ?></td>
                    <td><?= isset($dataActual[$key]) ? $dataActual[$key]['Plan'] : 'n/a'; ?></td>
                    <td><?= isset($dataActual[$key]) ? number_format($dataActual[$key]['VolInput']) : 'n/a'; ?> </td>
                    <td><?= isset($dataActual[$key]) ? number_format($dataActual[$key]['USDInput'], 2) : 'n/a'; ?> </td>
                    <td><?= isset($dataActual[$key]) ? number_format($dataActual[$key]['Vol']) : 'n/a'; ?> </td>
                    <td><?= isset($dataActual[$key]) ? number_format($dataActual[$key]['USDFacturacion'], 2) : 'n/a'; ?> </td>
                    <td style='display:none;'><?= "NTH" /*number_format(20) */ ?> </td>
                    <td style='display:none;'><?= "NTH" /*number_format(20) */ ?> </td>
                </tr>
                <?php
            }//endforeach
            ?>
            </tbody>
        </table>

        <?php
        echo LinkPager::widget([
            'pagination' => $pagination,
            'id' => 'paginator'
        ]);
        ?>
    </div>


</div>

<script id="template" type="text/x-kendo-template">
    <span class="#: isDeleted ? 'k-state-disabled': ''#">
        #: text #
    </span>
</script>

<script>

    $(document).ready(function () {

        $('.pagination li').each(function (i) {
            $("a", this).click(function (event) {
                event.preventDefault()
                var page = $(this).attr('data-page')
                var el = '<input type="hidden" name="page" value="' + page + '"></input>'
                $('#form').append(el).submit()
            })
        })

        /** PERFORMANCE CENTER **/
        function onPerformanceCenterChange() {
            $("#filterreportesegmentocomercioresultados-tradeproductid").data('kendoDropDownList').enable(false);
            $.ajax({
                url: "<?= yii\helpers\Url::to(['test/load-select-trade-product']) ?>",
                type: "GET",
                data: {
                    PerformanceCenterId: $("#filterreportesegmentocomercioresultados-performancecenterid").data('kendoDropDownList').value(),
                },
                success: function (tradeProducts) {
                    $("#filterreportesegmentocomercioresultados-tradeproductid").data('kendoDropDownList').dataSource.data(tradeProducts);
                    $("#filterreportesegmentocomercioresultados-tradeproductid").data('kendoDropDownList').enable(true);
                }
            })
        }

        $("#filterreportesegmentocomercioresultados-performancecenterid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app', 'Select') ?>",
            filter: "contains",
            dataTextField: "Description",
            dataValueField: "PerformanceCenterId",
            change: onPerformanceCenterChange,
            dataSource: {
                data: <?= yii\helpers\Json::encode($performanceCenters); ?>
            }
        });

        /** TRADE PRODUCT **/
        $("#filterreportesegmentocomercioresultados-tradeproductid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app', 'Select') ?>",
            filter: "contains",
            dataTextField: "Description",
            dataValueField: "TradeProductId",
            dataSource: {
                data: <?= yii\helpers\Json::encode($tradeProducts) ?>
            }
        });

        $('#actualizar').on("click", function (e) {
            e.preventDefault();
            $('#form').find('#do-export').remove();
            $('#form').submit();
        });

        $('#export').on("click", function () {
            $('<input>').attr({
                type: 'hidden',
                id: 'do-export',
                name: 'do-export',
                value: '1'
            }).appendTo('#form');
            $('#form').submit();
        });
    });

</script>