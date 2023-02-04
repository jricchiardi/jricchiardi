<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<div id="divBlack" style="display:none;">
    <div id="loading" >
        <img src="<?= Yii::$app->request->baseUrl ?>/images/loading.gif" width="60"/>
        <br>
        <?=Yii::t('app','Processing...') ?>
    </div>
</div>

<div id="containerTec" class="container full-width">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">				
            <h1 class="big-title"><?=Yii::t('app','Import Offline Forecast'); ?></h1>
        </div>
    </div>
    <?php
    if (!isset($errors)) :
        ?>
        <center>     

            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
            <?= $form->field($model, 'file')->fileInput() ?>                            

            <div class="form-group">      
                <?= Html::submitButton(Yii::t('app', 'Import'), [ 'id' => 'save', 'class' => 'btn btn-primary in-nuevos-reclamos']) ?>
            </div>           
            <?php ActiveForm::end() ?>                      

    </div>


    <?php
endif;


if (isset($errors)) {

    $dataSource = new \Kendo\Data\DataSource();
    $dataSource->data($errors);


    $gmidField = new \Kendo\Data\DataSourceSchemaModelField('GMID');
    $gmidField->type('string');



    $descriptionField = new \Kendo\Data\DataSourceSchemaModelField('DESCRIPTION');
    $descriptionField->type('string');
    $descriptionColumn = new \Kendo\UI\GridColumn();
    $descriptionColumn->field('DESCRIPTION');
    $descriptionColumn->title(Yii::t('app','Description'));


    $monthField = new \Kendo\Data\DataSourceSchemaModelField('MONTH');
    $monthField->type('number');
    $monthColumn = new \Kendo\UI\GridColumn();
    $monthColumn->field('MONTH');
    $monthColumn->title(Yii::t('app','Month'));

    $clientField = new \Kendo\Data\DataSourceSchemaModelField('CLIENT');
    $clientField->type('string');
    $clientColumn = new \Kendo\UI\GridColumn();
    $clientColumn->field('CLIENT');
    $clientColumn->title(Yii::t('app','Client'));

    $causeField = new \Kendo\Data\DataSourceSchemaModelField('CAUSE');
    $causeField->type('string');
    $causeColumn = new \Kendo\UI\GridColumn();
    $causeColumn->field('CAUSE');
    $causeColumn->title(Yii::t('app','Cause'));

    $grid = new \Kendo\UI\Grid('grid');

    $grid->addColumn($clientColumn, $gmidField, $descriptionColumn, $monthColumn, $causeColumn)
            ->scrollable(true)
            ->pageable(false)
            ->dataSource($dataSource)
            ->height(600);
    ?>

    <div class="row" id="imported" >
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <?= $grid->render(); ?>

            </div>
        </div>
    </div>

    <?php
}
?>

<script>
    $('#save').click(function ()
    {
        var has_error = $("#containerTec").find(".has-error").text();
        if (!has_error)
            $('#divBlack').show();
    });
</script>