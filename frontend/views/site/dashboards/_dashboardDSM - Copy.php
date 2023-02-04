<?php

use yii\widgets\ActiveForm;
?>

<p>&nbsp;</p>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="alert alert-info alert-dismissible" style="font-size: 18px;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <a href="#"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;<?= Yii::t('app', 'Last Date of Update CyO : {cyo}    -   Sales : {sale} ', ['sale' => \Yii::$app->formatter->asDatetime($results['lastDateSale'], "php:d-m-Y H:i:s"), 'cyo' => \Yii::$app->formatter->asDatetime($results['lastDateCyo'], "php:d-m-Y H:i:s")]) ?></a>
        </div>
    </div>
    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-3">	
        <div class="panel panel-default">
            <div class="panel-heading"><?=Yii::t('app', 'Filters') ?></div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(); ?>       
                <?= $form->field($dashBoardFilter, 'QuarterId'); ?>
                <?= $form->field($dashBoardFilter, 'ValueCenterId') ?>
                <?= $form->field($dashBoardFilter, 'PerformanceCenterId') ?>
                <?= $form->field($dashBoardFilter, 'TradeProductId') ?>
                <?php if (!\Yii::$app->user->can(common\models\AuthItem::ROLE_SELLER)) : ?>
                    <?= $form->field($dashBoardFilter, 'SellerId') ?>
                <?php endif; ?>
                <?php if (!\Yii::$app->user->can(common\models\AuthItem::ROLE_DSM) && !\Yii::$app->user->can(common\models\AuthItem::ROLE_SELLER)) : ?>
                    <?= $form->field($dashBoardFilter, 'DsmId') ?>
                <?php endif; ?>
                <?php if (!\Yii::$app->user->can(common\models\AuthItem::ROLE_RSM) && !\Yii::$app->user->can(common\models\AuthItem::ROLE_DSM) && !\Yii::$app->user->can(common\models\AuthItem::ROLE_SELLER)) : ?>
                    <?= $form->field($dashBoardFilter, 'RsmId') ?>
                <?php endif; ?>                              

                <?= $form->field($dashBoardFilter, 'ClientId') ?>


                <button type="submit" class="btn btn-primary btn-nuevo-reclamo pull-right"><?=Yii::t('app','Search') ?></button>
                <?php ActiveForm::end(); ?>          
            </div>
        </div>
    </div>


    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 pull-left">         
        <div class="box">
            <div id="containerValues" />   </div>
    </div>
</div>
<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pull-right">         
    <div class="box" style="height:325px">
        <h2><b><?=Yii::t('app','APPROXIMATE RANGE FORECAST') ?></b></h2>
        <span style=" position:relative; top:60px; font-size: 100px"><?= number_format($results['profit'], 2) ?> % </span></div>
</div>

<div class="col-xs-8 col-sm-8 col-md-8 col-lg-9">      		
    <table  class="table table-striped table-bordered">
        <thead>
            <tr>
                <th></th>
                <th colspan="2"><center><h4>Q1</h4></center></th>
        <th colspan="2"><center><h4>Q2</h4></center></th>
        <th colspan="2"><center><h4>Q3</h4></center></th>
        <th colspan="2"><center><h4>Q4</h4></center></th>                                        
        <th colspan="2"><center><h4>Total</h4></center></th>    
        </tr>
        <tr>
            <th></th>
  <th colspan="1"><center><b><?=Yii::t('app','Volume') ?></b></center></th>
        <th colspan="1"><center><b>USD</b></center></th>
        <th colspan="1"><center><b><?=Yii::t('app','Volume') ?></b></center></th>
        <th colspan="1"><center><b>USD</b></center></th>
        <th colspan="1"><center><b><?=Yii::t('app','Volume') ?></b></center></th>
        <th colspan="1"><center><b>USD</b></center></th>
        <th colspan="1"><center><b><?=Yii::t('app','Volume') ?></b></center></th>
        <th colspan="1"><center><b>USD</b></center></th>
        <th colspan="1"><center><b><?=Yii::t('app','Volume') ?></b></center></th>
        <th colspan="1"><center><b>USD</b></center></th>
        </tr>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td class="greenmedium"><?=Yii::t('app','Plan') ?></td>  
                <?php if (isset($results['resume']['TotalPlanVolume'])) : ?>    
                    <td class="greenmedium"><?= number_format($results['resume']['Q1PlanVolume']) ?> </td>             
                    <td class="greenmedium"><?= number_format($results['resume']['Q1PlanUSD'], 2) ?> </td> 
                    <td class="greenmedium"><?= number_format($results['resume']['Q2PlanVolume']) ?> </td>             
                    <td class="greenmedium"><?= number_format($results['resume']['Q2PlanUSD'], 2) ?> </td> 
                    <td class="greenmedium"><?= number_format($results['resume']['Q3PlanVolume']) ?> </td>             
                    <td class="greenmedium"><?= number_format($results['resume']['Q3PlanUSD'], 2) ?> </td> 
                    <td class="greenmedium"><?= number_format($results['resume']['Q4PlanVolume']) ?> </td>             
                    <td class="greenmedium"><?= number_format($results['resume']['Q4PlanUSD'], 2) ?> </td> 
                    <td class="greenmedium"><?= number_format($results['resume']['TotalPlanVolume']) ?> </td> 
                    <td class="greenmedium"><?= number_format($results['resume']['TotalPlanUSD'], 2) ?> </td>                                         
                    <?php
                else :
                    for ($x = 0; $x <= 9; $x++) :
                        ?>

                        <td class="greenmedium"></td>
                    <?php endfor; ?>
                <?php endif; ?>
            </tr>
            <tr>
                <td class="bluemedium"><?=Yii::t('app','Forecast + Sales') ?></td>  
                <?php if (isset($results['resume']['TotalForecastMoreSaleVolume'])) : ?>   
                    <td class="bluemedium"><?= number_format($results['resume']['Q1ForecastMoreSaleVolume']) ?> </td>             
                    <td class="bluemedium"><?= number_format($results['resume']['Q1ForecastMoreSaleUSD'], 2) ?> </td> 
                    <td class="bluemedium"><?= number_format($results['resume']['Q2ForecastMoreSaleVolume']) ?> </td>             
                    <td class="bluemedium"><?= number_format($results['resume']['Q2ForecastMoreSaleUSD'], 2) ?> </td> 
                    <td class="bluemedium"><?= number_format($results['resume']['Q3ForecastMoreSaleVolume']) ?> </td>             
                    <td class="bluemedium"><?= number_format($results['resume']['Q3ForecastMoreSaleUSD'], 2) ?> </td> 
                    <td class="bluemedium"><?= number_format($results['resume']['Q4ForecastMoreSaleVolume']) ?> </td>             
                    <td class="bluemedium"><?= number_format($results['resume']['Q4ForecastMoreSaleUSD'], 2) ?> </td> 
                    <td class="bluemedium"><?= number_format($results['resume']['TotalForecastMoreSaleVolume']) ?> </td> 
                    <td class="bluemedium"><?= number_format($results['resume']['TotalForecastMoreSaleUSD'], 2) ?> </td>                                          
                    <?php
                else :
                    for ($x = 0; $x <= 9; $x++) :
                        ?>

                        <td class="bluemedium"></td>
                    <?php endfor; ?>
                <?php endif; ?>
            </tr>
            <?php if (isset($results['resume']['TotalCyOVolume']) && $results['resume']['TotalCyOVolume'] > 0) : ?> 
                <tr>
                    <td class="yellowmedium">Cyo</td>  
                    <td class="yellowmedium" colspan="8"></td>
                    <td class="yellowmedium"><?= number_format((int) $results['resume']['TotalCyOVolume']) ?> </td>                                         
                    <td class="yellowmedium"><?= number_format($results['resume']['TotalCyOUSD'], 2) ?> </td>

                </tr>
            <?php endif; ?>  
        </tbody>
    </table>

</div>
<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 pull-right">      
    <div class="box">
        <div id="containerSales" />      
    </div>
</div>
</div>

</div>





<script>
    var containerSales =
            function () {
            $('#containerSales').highcharts({
            chart:
            {
            type: 'spline'
            },
                    title: {
                    text: '<?=Yii::t('app','Sale Historical by USD') ?>'
                    },
                    xAxis:
            {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
                    yAxis:
            {
            title: {
            text: 'USD'
            }
            },
                    plotOptions:
            {
            line:
            {
            dataLabels:
            {
            enabled: true
            },
                    enableMouseTracking: false
            }
            },
                    series: [
<?php foreach ($results['campaigns'] as $campaign) : ?>
                        {
                        name: "<?= $campaign['Name'] ?>",
                                data: [
    <?php foreach ($results['sales'][$campaign['Name']] as $sale) : ?>
        <?= "[" . $sale . "]," ?>
    <?php endforeach; ?>
                                ]
                        },
<?php endforeach; ?>

                    ],
                    credits: {enabled: false},
            });
            }

    var campaigns = function () {


    // Build the chart
    $('#containerValues').highcharts({
    colors: ["#4897df", "#f38200", "#99d34b"],
            chart: {
            width: 500,
                    height: 300,
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
            },
            title: {
            align: 'left',
                    text: '<?=Yii::t('app','Forecast distribution') ?>'
            },
            tooltip: {
            pointFormat: '<?=Yii::t('app','Percentage') ?>: <b>{point.percentage:.1f}% </b>'
            },
            plotOptions: {
            pie: {
            allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                    enabled: true,
                            distance: 3,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} % ',
                            style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            },
                            connectorColor: 'silver'
                    }
            }
            },
            series: [
            {
            type: 'pie',
                    name: 'Items',
                    innerSize: '25%',
                    data: [["<?=Yii::t('app','Real Sales') ?>", <?= $results['distribution']['sale'] ?>],
                            ["<?=Yii::t('app','Pending') ?>", <?= $results['distribution']['forecast'] ?>],
                            ["<?=Yii::t('app','CyO') ?>", <?= $results['distribution']['cyo'] ?>],
                    ],
            }],
            credits: {enabled: false},
    });
    };
            $(document).ready(function () {



    $("#filterdashboard-valuecenterid").kendoDropDownList({
    optionLabel: "<?=Yii::t('app','Select') ?>",
            filter: "startswith",
            dataTextField: "Description",
            dataValueField: "ValueCenterId",
            dataSource: {
            data: <?= yii\helpers\Json::encode(common\models\ValueCenter::find()->asArray()->all()) ?>
            }
    });
            $("#filterdashboard-performancecenterid").kendoDropDownList({
    cascadeFrom: "filterdashboard-valuecenterid",
            cascadeFromField: "ValueCenterId",
            optionLabel: "<?=Yii::t('app','Select') ?>",
            filter: "startswith",
            dataTextField: "Description",
            dataValueField: "PerformanceCenterId",
            dataSource: {
            data: <?= yii\helpers\Json::encode(common\models\PerformanceCenter::find()->orderBy('Description ASC')->asArray()->all()) ?>
            }
    });
            $("#filterdashboard-tradeproductid").kendoDropDownList({
    cascadeFrom: "filterdashboard-performancecenterid",
            cascadeFromField: "PerformanceCenterId",
            optionLabel: "<?=Yii::t('app','Select') ?>",
            filter: "startswith",
            dataTextField: "Description",
            dataValueField: "TradeProductId",
            dataSource: {
            data: <?= yii\helpers\Json::encode(common\models\TradeProduct::find()->orderBy('Description ASC')->asArray()->all()) ?>
            }
    });
            $("#filterdashboard-sellerid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app','Select') ?>",
            filter: "startswith",
            dataTextField: "Fullname",
            dataValueField: "UserId",
            dataSource: {
            data: <?= yii\helpers\Json::encode(\common\models\User::find()->select(['UserId', 'user.ParentId AS DsmId', 'user.Fullname'])->innerJoinWith('itemNames')->where(['name' => common\models\AuthItem::ROLE_SELLER, 'ParentId' => Yii::$app->user->identity->UserId])->asArray()->orderBy('user.Fullname ASC')->all()) ?>
            }
    });
            $("#filterdashboard-clientid").kendoDropDownList({
            cascadeFrom: "filterdashboard-sellerid",
            cascadeFromField: "SellerId",
            optionLabel: "<?=Yii::t('app','Select') ?>",
            filter: "startswith",
            dataTextField: "Description",
            dataValueField: "ClientId",
            dataSource: {
            data: <?= yii\helpers\Json::encode(\common\models\Client::find()->select(['client.ClientId', 'client.Description', 'client_seller.SellerId AS SellerId'])->joinWith('clientSellers')->where(['GroupId' => NULL])->orderBy('client.Description ASC')->asArray()->all()) ?>
            }
    });
            $("#filterdashboard-quarterid").kendoDropDownList({
              optionLabel: "<?=Yii::t('app','Select') ?>",
            filter: "startswith",
            dataTextField: "text",
            dataValueField: "value",
            dataSource: {
            data: [{'value':'Q1', 'text':'Q1'}, {'value':'Q2', 'text':'Q2'}, {'value':'Q3', 'text':'Q3'}, {'value':'Q4', 'text':'Q4'}]
            }
    });
    });
</script>


<?php
$this->registerJs('campaigns(); containerSales();
    ', $this::POS_READY);
