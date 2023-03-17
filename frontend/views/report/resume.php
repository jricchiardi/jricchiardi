<?php

use yii\widgets\ActiveForm;
?>

<p>&nbsp;</p>

<h1><?= Yii::t('app', 'Report comparative by Seller') ?></h1>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">                                  
        <div class="panel panel-default">
            <div class="panel-heading"><?= Yii::t('app', 'Filters') ?></div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(['action' => ['resume'], 'method' => 'get']); ?>                               

                <?php if (!\Yii::$app->user->can(common\models\AuthItem::ROLE_RSM) && !\Yii::$app->user->can(common\models\AuthItem::ROLE_DSM) && !\Yii::$app->user->can(common\models\AuthItem::ROLE_SELLER)) : ?>
                    <?= $form->field($reportModel, 'RsmId') ?>
                <?php endif; ?> 
                <?php if (!\Yii::$app->user->can(common\models\AuthItem::ROLE_DSM) && !\Yii::$app->user->can(common\models\AuthItem::ROLE_SELLER)) : ?>
                    <?= $form->field($reportModel, 'DsmId') ?>
                <?php endif; ?>

                <button type="submit" name="send" id="send" class="btn btn-primary btn-nuevo-reclamo pull-right"><?= Yii::t('app', 'Search') ?></button>                
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <strong>
        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-9">      		
            <table  class="table table-striped table-bordered" width="20%">
                <thead id="head-resume">
                    <tr>
                        <th colspan="2"><center><h4>Seller</h4></center></th>   
                <th colspan="2"><center><h4>Margin</h4></center></th>
                <th colspan="2"><center><h4>CyO</h4></center></th>
                <th></th>
                <th class="space"></th>
                <th colspan="2"><center><h4>Q1</h4></center></th>
                <th  class="space"></th>
                <th colspan="2"><center><h4>Q2</h4></center></th>
                <th  class="space"></th>
                <th colspan="2"><center><h4>Q3</h4></center></th>
                <th  class="space"></th>
                <th colspan="2"><center><h4>Q4</h4></center></th>      
                <th  class="space"></th>
                <th colspan="2"><center><h4><?= Yii::t('app', 'Total') ?></h4></center></th>    
                </tr>
                <tr>
                    <th colspan="2"></th>
                    <th colspan="2"></th>                    
                    <th colspan="1"><center><b><?= Yii::t('app', 'Volume') ?></b></center></th>
                <th colspan="1"><center><b>USD</b></center></th>
                <th></th>
                <th class="space"></th>
                <th colspan="1"><center><b><?= Yii::t('app', 'Volume') ?></b></center></th>
                <th colspan="1"><center><b>USD</b></center></th>
                <th class="space"></th>
                <th colspan="1"><center><b><?= Yii::t('app', 'Volume') ?></b></center></th>
                <th colspan="1"><center><b>USD</b></center></th>
                <th class="space"></th>
                <th colspan="1"><center><b><?= Yii::t('app', 'Volume') ?></b></center></th>
                <th colspan="1"><center><b>USD</b></center></th>
                <th  class="space"></th> 
                <th colspan="1"><center><b><?= Yii::t('app', 'Volume') ?></b></center></th>
                <th colspan="1"><center><b>USD</b></center></th>
                <th class="space"></th>
                <th colspan="1"><center><b><?= Yii::t('app', 'Volume') ?></b></center></th>
                <th colspan="1"><center><b>USD</b></center></th>
                </tr>
                </tr>
                </thead>
                <tbody>
                    <?php
                    $row = 0;
                    foreach ($results['resume'] as $item) :
                        $color = ($row % 2 == 0) ? 'green-excel' : 'blue-excel';
                        ?>     
                        <tr class="<?= $color ?>">
                            <td  colspan="2" rowspan="3"><h4><?= $item['SellerName'] ?></h4></td>
                            <td  colspan="2" rowspan="3"><?= number_format($item['Profit'], 2) ?>%</td>                          
                            <td  rowspan="3"><?= number_format((int) $item['TotalCyOVolume']) ?> </td>                                         
                            <td  rowspan="3"><?= number_format($item['TotalCyOUSD'], 2) ?> </td>

                            <td ><?= Yii::t('app', 'Plan') ?></td>  
                            <td class="space"></td>
                            <?php if (isset($item['TotalPlanVolume'])) : ?>    

                                <td><?= number_format($item['Q1PlanVolume']) ?> </td>             
                                <td ><?= number_format($item['Q1PlanUSD'], 2) ?> </td> 
                                <td  class="space"></td>
                                <td ><?= number_format($item['Q2PlanVolume']) ?> </td>             
                                <td ><?= number_format($item['Q2PlanUSD'], 2) ?> </td> 
                                <td class="space"></td>
                                <td ><?= number_format($item['Q3PlanVolume']) ?> </td>             
                                <td ><?= number_format($item['Q3PlanUSD'], 2) ?> </td> 
                                <td class="space"></td>
                                <td ><?= number_format($item['Q4PlanVolume']) ?> </td>             
                                <td ><?= number_format($item['Q4PlanUSD'], 2) ?> </td> 
                                <td class="space"></td>
                                <td ><?= number_format($item['TotalPlanVolume']) ?> </td> 
                                <td ><?= number_format($item['TotalPlanUSD'], 2) ?> </td>                                         
                                <?php
                            else :
                                for ($x = 0; $x <= 9; $x++) :
                                    ?>

                                    <td class="greenmedium"></td>
                                <?php endfor; ?>
                            <?php endif; ?>
                        </tr>
                        <tr class="<?= $color ?>">

                            <td ><?= Yii::t('app', 'Forecast + Sales') ?></td>  
                            <td class="space"></td>
                            <?php if (isset($item['TotalForecastMoreSaleVolume'])) : ?>                          
                                <td><?= number_format($item['Q1ForecastMoreSaleVolume']) ?> </td>             
                                <td ><?= number_format($item['Q1ForecastMoreSaleUSD'], 2) ?> </td> 
                                <th colspan="1" class="space"></th>
                                <td><?= number_format($item['Q2ForecastMoreSaleVolume']) ?> </td>             
                                <td ><?= number_format($item['Q2ForecastMoreSaleUSD'], 2) ?> </td> 
                                <th colspan="1" class="space"></th>
                                <td ><?= number_format($item['Q3ForecastMoreSaleVolume']) ?> </td>             
                                <td ><?= number_format($item['Q3ForecastMoreSaleUSD'], 2) ?> </td> 
                                <th colspan="1" class="space"></th>
                                <td ><?= number_format($item['Q4ForecastMoreSaleVolume']) ?> </td>             
                                <td ><?= number_format($item['Q4ForecastMoreSaleUSD'], 2) ?> </td> 
                                <th colspan="1" class="space"></th>
                                <td ><?= number_format($item['TotalForecastMoreSaleVolume']) ?> </td> 
                                <td ><?= number_format($item['TotalForecastMoreSaleUSD'], 2) ?> </td>                                         
                            <?php endif; ?>
                        </tr>     

                        <tr>

                            <td class="grey-col"><?= Yii::t('app', 'Diference') ?></td>  
                            <td class="space"></td>            
                            <td class="grey-col"><?= number_format($item['Q1PlanVolume'] - $item['Q1ForecastMoreSaleVolume']) ?> </td>             
                            <td class="grey-col"><?= number_format($item['Q1PlanUSD'] - $item['Q1ForecastMoreSaleUSD'], 2) ?> </td> 
                            <th colspan="1" class="space"></th>
                            <td class="grey-col"><?= number_format($item['Q2PlanVolume'] - $item['Q2ForecastMoreSaleVolume']) ?> </td>             
                            <td class="grey-col"><?= number_format($item['Q2PlanUSD'] - $item['Q2ForecastMoreSaleUSD'], 2) ?> </td> 
                            <th colspan="1" class="space"></th>
                            <td class="grey-col"><?= number_format($item['Q3PlanVolume'] - $item['Q3ForecastMoreSaleVolume']) ?> </td>             
                            <td class="grey-col"><?= number_format($item['Q3PlanUSD'] - $item['Q3ForecastMoreSaleUSD'], 2) ?> </td> 
                            <th colspan="1" class="space"></th>
                            <td class="grey-col"><?= number_format($item['Q4PlanVolume'] - $item['Q4ForecastMoreSaleVolume']) ?> </td>             
                            <td class="grey-col"><?= number_format($item['Q4PlanUSD'] - $item['Q4ForecastMoreSaleUSD'], 2) ?> </td> 
                            <th colspan="1" class="space"></th>
                            <td class="grey-col"><?= number_format($item['TotalPlanVolume'] - $item['TotalForecastMoreSaleVolume']) ?> </td> 
                            <td class="grey-col"><?= number_format($item['TotalPlanUSD'] - $item['TotalForecastMoreSaleUSD'], 2) ?> </td>                                         

                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        <?php $row++;
                    endforeach;
                    ?>
                </tbody>
            </table>

        </div>
    </strong>

    <?php
    $this->registerJS(' 
           $("#reportsearch-rsmid").kendoDropDownList({
            optionLabel: "Select",
            filter: "startswith",
            dataTextField: "Fullname",
            dataValueField: "UserId",
            dataSource: {
            data: ' . yii\helpers\Json::encode(common\models\User::find()->select(['UserId', 'user.UserId AS RsmId', 'user.Fullname'])->joinWith('itemNames')->where(['[auth_item].name' => common\models\AuthItem::ROLE_RSM])->orderBy('Fullname ASC')->asArray()->all()) . '
            }
    });
            $("#reportsearch-dsmid").kendoDropDownList({
            cascadeFrom: "reportsearch-rsmid",
            cascadeFromField: "RsmId",
            optionLabel: "Select",
            filter: "startswith",
            dataTextField: "Fullname",
            dataValueField: "UserId",
            dataSource: {
            data: ' . yii\helpers\Json::encode(common\models\User::find()->select(['UserId', 'user.ParentId AS RsmId', 'user.Fullname'])->joinWith('itemNames')->where(['[auth_item].name' => common\models\AuthItem::ROLE_DSM])->orderBy('Fullname ASC')->asArray()->all()) . '
            }
    });
        
        ', yii\web\View::POS_READY);
    