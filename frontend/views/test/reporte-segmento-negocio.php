<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\models\Client;
use common\models\Campaign;
?>

<div class="row">

    <div class="col-md-6 col-md-offset-3">
    <h1>Generar Reporte Segmento de Negocio</h1>
        <div class="panel panel-default">
            <div class="panel-heading">Parametros entrada</div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin([
                        'action' => ['test/reporte-segmento-negocio-resultados'],
                        'enableClientValidation' => true,
                    ]);
                    ?>
                    <?= $form->field($filterReporteSegmentoNegocio, 'CountryId') ?>
                    <?= $form->field($filterReporteSegmentoNegocio, 'DsmId') ?>
                    <?= $form->field($filterReporteSegmentoNegocio, 'TamId') ?>
                    <?= $form->field($filterReporteSegmentoNegocio, 'ValueCenterId') ?>
                    <?= $form->field($filterReporteSegmentoNegocio, 'PerformanceCenterId') ?>
                    <?= $form->field($filterReporteSegmentoNegocio, 'BusinessSegmentId') ?>
                    <?= $form->field($filterReporteSegmentoNegocio, 'SubBusinessSegmentId') ?>
                    <?= $form->field($filterReporteSegmentoNegocio, 'QuarterId') ?>
                    <?= $form->field($filterReporteSegmentoNegocio, 'CampaignId') ?>

                    <br>

                    <button type="submit" class="btn btn-primary btn-nuevo-reclamo pull-right">Generar</button>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        /** COUNTRY **/
        function onCountryChange(){
           $("#filterreportesegmentonegocio-dsmid").data('kendoDropDownList').enable(false);
           $.ajax({
              url: "<?= yii\helpers\Url::to(['test/load-select-dsm']) ?>",
              type: "GET",
              data: {
                CountryId: $("#filterreportesegmentonegocio-countryid").data("kendoDropDownList").value(),
              },
              success: function(dsm){
                $("#filterreportesegmentonegocio-dsmid").data('kendoDropDownList').dataSource.data(dsm);
                $("#filterreportesegmentonegocio-dsmid").data('kendoDropDownList').enable(true);
            }
         })
        }

        <?php
           $countries = \common\models\Country::find()->select(['country.CountryId AS CountryId', 'country.Description'])->orderBy('country.Description ASC')->asArray()->all();
           array_push($countries,array('Description' => 'TODOS', 'CountryId' => "0"));
        ?>

        $("#filterreportesegmentonegocio-countryid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app','Select') ?>",
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
        function onDsmChange(){
            $("#filterreportesegmentonegocio-tamid").data('kendoDropDownList').enable(false);
           $.ajax({
              url: "<?= yii\helpers\Url::to(['test/load-select-tam']) ?>",
              type: "GET",
              data: {
                DsmId: $("#filterreportesegmentonegocio-dsmid").data('kendoDropDownList').value()
              },
              success: function(tam){
                $("#filterreportesegmentonegocio-tamid").data('kendoDropDownList').dataSource.data(tam);
                $("#filterreportesegmentonegocio-tamid").data('kendoDropDownList').enable(true);
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

        $("#filterreportesegmentonegocio-dsmid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app','Select') ?>",
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

        $("#filterreportesegmentonegocio-tamid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app','Select') ?>",
            filter: "contains",
            dataTextField: "FullName",
            dataValueField: "TamId",
            dataSource: {
               data:  <?= yii\helpers\Json::encode($tams); ?>
            },
            value: "0"
        });

        /** VALUE CENTER **/
        function onValueCenterChange(){
           $("#filterreportesegmentonegocio-performancecenterid").data('kendoDropDownList').enable(false);
           $.ajax({
              url: "<?= yii\helpers\Url::to(['test/load-select-performance-center']) ?>",
              type: "GET",
              data: {
                ValueCenterId: $("#filterreportesegmentonegocio-valuecenterid").data("kendoDropDownList").value(),
              },
              success: function(performanceCenters){
                $("#filterreportesegmentonegocio-performancecenterid").data('kendoDropDownList').dataSource.data(performanceCenters);
                $("#filterreportesegmentonegocio-performancecenterid").data('kendoDropDownList').enable(true);
            }
         })
        }

        <?php
           $valueCenters = \common\models\ValueCenter::find()->select(['value_center.ValueCenterId', 'value_center.Description'])->orderBy('value_center.Description ASC')->asArray()->all();

           array_push($valueCenters, array('Description' => 'TODOS', 'ValueCenterId' => "0"));
        ?>

        $("#filterreportesegmentonegocio-valuecenterid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app','Select') ?>",
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
        function onPerformanceCenterChange(){
            $("#filterreportesegmentonegocio-businesssegmentid").data('kendoDropDownList').enable(false);
            $.ajax({
                url: "<?= yii\helpers\Url::to(['test/load-select-business-segment']) ?>",
                type: "GET",
                data: {
                    PerformanceCenterId: $("#filterreportesegmentonegocio-performancecenterid").data('kendoDropDownList').value(),
                },
                success: function(tradeProducts){
                    $("#filterreportesegmentonegocio-businesssegmentid").data('kendoDropDownList').dataSource.data(tradeProducts);
                    $("#filterreportesegmentonegocio-businesssegmentid").data('kendoDropDownList').enable(true);
                }
             })
        }

        <?php
           $performanceCenters = \common\models\PerformanceCenter::find()
                ->select(['PerformanceCenterId', 'Description'])->orderBy('Description ASC')->asArray()->all();

           array_push($performanceCenters, array('Description' => 'TODOS', 'PerformanceCenterId' => "0"));
        ?>

        $("#filterreportesegmentonegocio-performancecenterid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app','Select') ?>",
            filter: "contains",
            dataTextField: "Description",
            dataValueField: "PerformanceCenterId",
            change: onPerformanceCenterChange,
            dataSource: {
                data: <?= yii\helpers\Json::encode($performanceCenters); ?>
            },
            value: "0"
        });

        /** BUSINESS SEGMENT **/
        function onBusinessSegmentChange(){
            $("#filterreportesegmentonegocio-subbusinesssegmentid").data('kendoDropDownList').enable(false);
            $.ajax({
                url: "<?= yii\helpers\Url::to(['test/load-select-sub-business-segment']) ?>",
                type: "GET",
                data: {
                    BusinessSegmentId: $("#filterreportesegmentonegocio-businesssegmentid").data('kendoDropDownList').value(),
                },
                success: function(tradeProducts){
                    $("#filterreportesegmentonegocio-subbusinesssegmentid").data('kendoDropDownList').dataSource.data(tradeProducts);
                    $("#filterreportesegmentonegocio-subbusinesssegmentid").data('kendoDropDownList').enable(true);
                }
             })
        }

        <?php
            // $businessSegments = \common\models\BusinessSegment::find()->orderBy('Description ASC')->asArray()->all();
            $businessSegments = [];

            array_push($businessSegments, array('Description' => 'TODOS', 'BusinessSegmentId' => "0"));
        ?>

        $("#filterreportesegmentonegocio-businesssegmentid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app','Select') ?>",
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

        $("#filterreportesegmentonegocio-subbusinesssegmentid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app','Select') ?>",
            filter: "contains",
            dataTextField: "Description",
            dataValueField: "SubBusinessSegmentId",
            dataSource: {
                data: <?= yii\helpers\Json::encode($subBusinessSegments) ?>
            },
            value: "0"
        });

        /** QUARTERS **/
        $("#filterreportesegmentonegocio-quarterid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app','Select') ?>",
            filter: "contains",
            dataTextField: "text",
            dataValueField: "value",
            dataSource: {
                data: [
                    {'value':'Q1', 'text':'Q1'},
                    {'value':'Q2', 'text':'Q2'},
                    {'value':'Q3', 'text':'Q3'},
                    {'value':'Q4', 'text':'Q4'},
                    {'value':'Y', 'text':'YEAR'}
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

        $("#filterreportesegmentonegocio-campaignid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app','Select') ?>",
            filter: "contains",
            dataTextField: "Name",
            dataValueField: "CampaignId",
            dataSource: {
                data: <?= yii\helpers\Json::encode($campaigns) ?>
            },
        });

    });

</script>