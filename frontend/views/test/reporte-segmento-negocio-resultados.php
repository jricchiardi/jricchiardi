<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="row">

    <div class="col-md-12">
        <h1>Reporte de segmento de negocio generado</h1>
    </div>

    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">Parámetros</div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id' => "form",
                ]); ?>

                <?= $form->field($filterReporteSegmentoNegocioResultados, 'BusinessSegmentId') ?>
                <?= $form->field($filterReporteSegmentoNegocioResultados, 'SubBusinessSegmentId') ?>

                <?= Html::activeHiddenInput($filterReporteSegmentoNegocioResultados, 'TamId'); ?>
                <?= Html::activeHiddenInput($filterReporteSegmentoNegocioResultados, 'PerformanceCenterId'); ?>
                <?= Html::activeHiddenInput($filterReporteSegmentoNegocioResultados, 'CampaignId'); ?>
                <?= Html::activeHiddenInput($filterReporteSegmentoNegocioResultados, 'CountryId'); ?>
                <?= Html::activeHiddenInput($filterReporteSegmentoNegocioResultados, 'QuarterId'); ?>

                <button disable name="export" id="export"
                        style=" width='40px';background:url('<?= Yii::$app->urlManager->baseUrl ?>/images/download.gif'); float:left; display:block; width: 40px;height: 38px;border: none;background-size: contain;background-repeat: no-repeat; cursor:pointer;"></button>

                <button type="submit" class="btn btn-primary btn-nuevo-reclamo pull-right">Actualizar</button>
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
        // echo LinkPager::widget([
        //     'pagination' => $pagination,
        //     'id' => 'paginator'
        // ]);
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

        // $('.pagination li').each(function (i) {
        //     $("a", this).click(function(event) {
        //         event.preventDefault()
        //         var page = $(this).attr('data-page')
        //         var el = '<input type="hidden" name="page" value="' + page + '"></input>'
        //         $('#form').append(el).submit()
        //     })
        // })

        /** BUSINESS SEGMENT **/
        function onBusinessSegmentChange() {
            $("#filterreportesegmentonegocioresultados-subbusinesssegmentid").data('kendoDropDownList').enable(false);
            $.ajax({
                url: "<?= yii\helpers\Url::to(['test/load-select-sub-business-segment']) ?>",
                type: "GET",
                data: {
                    BusinessSegmentId: $("#filterreportesegmentonegocioresultados-businesssegmentid").data('kendoDropDownList').value(),
                },
                success: function (tradeProducts) {
                    $("#filterreportesegmentonegocioresultados-subbusinesssegmentid").data('kendoDropDownList').dataSource.data(tradeProducts);
                    $("#filterreportesegmentonegocioresultados-subbusinesssegmentid").data('kendoDropDownList').enable(true);
                }
            })
        }

        <?php
        // $businessSegments = \common\models\BusinessSegment::find()->orderBy('Description ASC')->asArray()->all();
        $businessSegments = [];

        array_push($businessSegments, array('Description' => 'TODOS', 'BusinessSegmentId' => "0"));
        ?>

        $("#filterreportesegmentonegocioresultados-businesssegmentid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app', 'Select') ?>",
            filter: "contains",
            dataTextField: "Description",
            dataValueField: "BusinessSegmentId",
            change: onBusinessSegmentChange,
            dataSource: {
                data: <?= yii\helpers\Json::encode($businessSegments) ?>
            },
            value: "0"
        });

        /** SUB BUSINESS SEGMENT **/
        <?php
        // $subBusinessSegments = \common\models\SubBusinessSegment::find()->orderBy('Description ASC')->asArray()->all();
        $subBusinessSegments = [];

        array_push($subBusinessSegments, array('Description' => 'TODOS', 'SubBusinessSegmentId' => "0"));
        ?>

        $("#filterreportesegmentonegocioresultados-subbusinesssegmentid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app', 'Select') ?>",
            filter: "contains",
            dataTextField: "Description",
            dataValueField: "SubBusinessSegmentId",
            dataSource: {
                data: <?= yii\helpers\Json::encode($subBusinessSegments) ?>
            },
            value: "0"
        });

        /** TRADE PRODUCT **/
        // $("#filterreportesegmentonegocioresultados-businesssegmentid").kendoDropDownList({
        //     optionLabel: "<?=Yii::t('app', 'Select') ?>",
        //     filter: "contains",
        //     dataTextField: "Description",
        //     dataValueField: "BusinessSegmentId",
        //     dataSource: {
        //     data: <?= yii\helpers\Json::encode($businessSegments) ?>
        //     }
        // });

        // $('#export').on("click", function() {
        //     $('<input>').attr({
        //         type: 'hidden',
        //         id: 'do-export',
        //         name: 'do-export',
        //         value: '1'
        //     }).appendTo('#form');
        //     $('#form').submit();
        //     // $("#form").append('<input type="hidden" name="export" type="text" value="1" />').submit();
        // });
    });

</script>