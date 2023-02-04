<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>




<div class="container full-width">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">				
            <h1 class="big-title"><?=Yii::t('app','Import CyO File') ?></h1>
        </div>
    </div>
    <?php
    if (!isset($errors)) :
        ?>
        <center>     

            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
            <?= $form->field($model, 'file')->fileInput() ?>                            

            <div class="form-group">      
                <?= Html::submitButton(Yii::t('app', 'Import'), ['class' => 'btn btn-primary in-nuevos-reclamos']) ?>
            </div>           
            <?php ActiveForm::end() ?>                      

    </div>
    </center>      
<?php endif; ?>    
</div>

<?php
$this->title = Yii::t('app', 'CyO');

if (isset($errors)) {

    $dataSource = new \Kendo\Data\DataSource();
    $dataSource->data($errors);


    $clientField = new \Kendo\Data\DataSourceSchemaModelField('CLIENT');
    $clientField->type('string');
    $clientColumn = new \Kendo\UI\GridColumn();
    $clientColumn->field('CLIENT');
    $clientColumn->title(Yii::t('app', 'Client'));


    $descriptionField = new \Kendo\Data\DataSourceSchemaModelField('GMID');
    $descriptionField->type('string');
    $descriptionColumn = new \Kendo\UI\GridColumn();
    $descriptionColumn->field('GMID');
    $descriptionColumn->title('Gmid');

    $causeField = new \Kendo\Data\DataSourceSchemaModelField('CAUSE');
    $causeField->type('string');
    $causeColumn = new \Kendo\UI\GridColumn();
    $causeColumn->field('CAUSE');
    $causeColumn->title(Yii::t('app','Cause'));

    $grid = new \Kendo\UI\Grid('grid');

    $grid->addColumn($clientColumn, $descriptionColumn, $causeColumn)
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