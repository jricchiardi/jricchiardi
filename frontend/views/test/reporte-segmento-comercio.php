<?php

use common\models\Campaign;
use common\models\Client;
use common\models\Country;
use common\models\PerformanceCenter;
use common\models\ValueCenter;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="row">

    <div class="col-md-6 col-md-offset-3">
        <h1>Generar Reporte Segmento Comercio</h1>
        <div class="panel panel-default">
            <div class="panel-heading">Parametros entrada</div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'action' => ['test/reporte-segmento-comercio-resultados'],
                    'enableClientValidation' => true,
                ]);
                ?>
                <?= $form->field($filterReporteSegmentoComercio, 'CountryId') ?>
                <?= $form->field($filterReporteSegmentoComercio, 'DsmId') ?>
                <?= $form->field($filterReporteSegmentoComercio, 'TamId') ?>
                <?= $form->field($filterReporteSegmentoComercio, 'ValueCenterId') ?>
                <?= $form->field($filterReporteSegmentoComercio, 'PerformanceCenterId') ?>
                <?= $form->field($filterReporteSegmentoComercio, 'QuarterId') ?>
                <?= $form->field($filterReporteSegmentoComercio, 'CampaignId') ?>

                <?= Html::activeHiddenInput($filterReporteSegmentoComercio, 'TradeProductId'); ?>

                <br>

                <button type="submit" class="btn btn-primary btn-nuevo-reclamo pull-right">Generar</button>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        /** COUNTRY **/
        function onCountryChange() {
            $("#filterreportesegmentocomercio-dsmid").data('kendoDropDownList').enable(false);
            $.ajax({
                url: "<?= yii\helpers\Url::to(['test/load-select-dsm']) ?>",
                type: "GET",
                data: {
                    CountryId: $("#filterreportesegmentocomercio-countryid").data("kendoDropDownList").value(),
                },
                success: function (dsm) {
                    $("#filterreportesegmentocomercio-dsmid").data('kendoDropDownList').dataSource.data(dsm);
                    $("#filterreportesegmentocomercio-dsmid").data('kendoDropDownList').enable(true);
                }
            })
        }

        <?php
        $countries = Country::find()->select(['country.CountryId AS CountryId', 'country.Description'])->orderBy('country.Description ASC')->asArray()->all();
        array_push($countries, array('Description' => 'TODOS', 'CountryId' => "0"));
        ?>

        $("#filterreportesegmentocomercio-countryid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app', 'Select') ?>",
            filter: "contains",
            dataTextField: "Description",
            dataValueField: "CountryId",
            change: onCountryChange,
            dataSource: {
                data:  <?= yii\helpers\Json::encode($countries); ?>
            },
            value: "0"
        });

        /** DSM **/
        function onDsmChange() {
            $("#filterreportesegmentocomercio-tamid").data('kendoDropDownList').enable(false);
            $.ajax({
                url: "<?= yii\helpers\Url::to(['test/load-select-tam']) ?>",
                type: "GET",
                data: {
                    DsmId: $("#filterreportesegmentocomercio-dsmid").data('kendoDropDownList').value()
                },
                success: function (tam) {
                    $("#filterreportesegmentocomercio-tamid").data('kendoDropDownList').dataSource.data(tam);
                    $("#filterreportesegmentocomercio-tamid").data('kendoDropDownList').enable(true);
                }
            })
        }

        <?php
        $dsms = Client::find()->select(['dsm.UserId AS DsmId', 'dsm.Fullname AS FullName'])
            ->innerJoin('client_seller cs', 'client.ClientId=cs.ClientId')
            ->innerJoin('user seller', 'cs.SellerId=seller.UserId')
            ->innerJoin('user dsm', 'seller.ParentId=dsm.UserId')
            ->distinct()->asArray()->all();

        array_push($dsms, array('FullName' => 'TODOS', 'DsmId' => "0"));
        ?>

        $("#filterreportesegmentocomercio-dsmid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app', 'Select') ?>",
            filter: "contains",
            dataTextField: "FullName",
            dataValueField: "DsmId",
            change: onDsmChange,
            dataSource: {
                data:  <?= yii\helpers\Json::encode($dsms); ?>
            },
            value: "0"
        });

        /** TAM **/
        <?php
        $tams = Client::find()->select(['seller.UserId AS TamId', 'seller.Fullname AS FullName'])
            ->innerJoin('client_seller cs', 'client.ClientId=cs.ClientId')
            ->innerJoin('user seller', 'cs.SellerId=seller.UserId')
            ->innerJoin('user dsm', 'seller.ParentId=dsm.UserId')
            ->distinct()->asArray()->all();

        array_push($tams, array('FullName' => 'TODOS', 'TamId' => "0"));
        ?>

        $("#filterreportesegmentocomercio-tamid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app', 'Select') ?>",
            filter: "contains",
            dataTextField: "FullName",
            dataValueField: "TamId",
            dataSource: {
                data:  <?= yii\helpers\Json::encode($tams); ?>
            },
            value: "0"
        });

        /** VALUE CENTER **/
        function onValueCenterChange() {
            $("#filterreportesegmentocomercio-performancecenterid").data('kendoDropDownList').enable(false);
            $.ajax({
                url: "<?= yii\helpers\Url::to(['test/load-select-performance-center']) ?>",
                type: "GET",
                data: {
                    ValueCenterId: $("#filterreportesegmentocomercio-valuecenterid").data("kendoDropDownList").value(),
                },
                success: function (performanceCenters) {
                    $("#filterreportesegmentocomercio-performancecenterid").data('kendoDropDownList').dataSource.data(performanceCenters);
                    $("#filterreportesegmentocomercio-performancecenterid").data('kendoDropDownList').enable(true);
                }
            })
        }

        <?php
        $valueCenters = ValueCenter::find()->select(['value_center.ValueCenterId', 'value_center.Description'])->orderBy('value_center.Description ASC')->asArray()->all();

        array_push($valueCenters, array('Description' => 'TODOS', 'ValueCenterId' => "0"));
        ?>

        $("#filterreportesegmentocomercio-valuecenterid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app', 'Select') ?>",
            filter: "contains",
            dataTextField: "Description",
            dataValueField: "ValueCenterId",
            change: onValueCenterChange,
            dataSource: {
                data: <?= yii\helpers\Json::encode($valueCenters) ?>
            },
            value: "0"
        });

        /** PERFORMANCE CENTER **/
        <?php
        $performanceCenters = PerformanceCenter::find()
            ->select(['PerformanceCenterId', 'Description'])->orderBy('Description ASC')->asArray()->all();

        array_push($performanceCenters, array('Description' => 'TODOS', 'PerformanceCenterId' => "0"));
        ?>

        $("#filterreportesegmentocomercio-performancecenterid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app', 'Select') ?>",
            filter: "contains",
            dataTextField: "Description",
            dataValueField: "PerformanceCenterId",
            // change: onPerformanceCenterChange,
            dataSource: {
                data: <?= yii\helpers\Json::encode($performanceCenters); ?>
            },
            value: "0"
        });

        /** QUARTERS **/
        $("#filterreportesegmentocomercio-quarterid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app', 'Select') ?>",
            filter: "contains",
            dataTextField: "text",
            dataValueField: "value",
            dataSource: {
                data: [
                    {'value': 'Q1', 'text': 'Q1'},
                    {'value': 'Q2', 'text': 'Q2'},
                    {'value': 'Q3', 'text': 'Q3'},
                    {'value': 'Q4', 'text': 'Q4'},
                    {'value': 'Y', 'text': 'YEAR'}
                ]
            },
            value: "Y"
        });

        /** YEAR **/
        <?php
        $campaigns = Campaign::find()
            ->select(['CampaignId', 'Name'])
            ->where(['IsActual' => false])
            ->asArray()->all();
        ?>

        $("#filterreportesegmentocomercio-campaignid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app', 'Select') ?>",
            filter: "contains",
            dataTextField: "Name",
            dataValueField: "CampaignId",
            dataSource: {
                data: <?= yii\helpers\Json::encode($campaigns) ?>
            },
        });

    });

</script>