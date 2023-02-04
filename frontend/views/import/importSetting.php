<?php

use common\models\Country;
use Kendo\Data\DataSource;
use Kendo\Data\DataSourceSchemaModelField;
use Kendo\UI\Grid;
use Kendo\UI\GridColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<div class="container full-width">
    <br/>
    <div class="row">
        <div class="col-md-6">
            <h1 class="big-title">Descargar Archivo de Ajustes del plan</h1>
            <?php $form = ActiveForm::begin([
                'action' => Url::to(['plan/download-validation']),
            ]) ?>
            <?= $form->field($modelDownload, 'plan')->dropdownList(['futuro' => 'Futuro', 'actual' => 'Actual'], ['prompt' => 'Seleccionar tipo de plan a descargar', 'class' => 'mySelectBoxClass']) ?>

            <?php
            $countries = Country::find()
                ->select(['Description'])
                ->indexBy('CountryId')
                ->column();
            ?>
            <?= $form->field($modelDownload, 'pais')->dropdownList($countries, ['prompt' => 'Seleccionar del pais a descargar', 'class' => 'mySelectBoxClass']) ?>

            <div class="form-group">
                <?= $form->field($modelDownload, 'incluirConVolumenCero')->checkbox() ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Descargar', ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h1 class="big-title"><?= Yii::t('app', 'File Import Settings plan') ?></h1>
        </div>
    </div>
    <?php
    if (!isset($errors)) :
    ?>
    <center>

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
        <?= $form->field($model, 'file')->fileInput() ?>

        <?= $form->field($model, 'plan')->dropdownList(['futuro' => 'Futuro', 'actual' => 'Actual'], ['prompt' => 'Seleccionar plan a modificar', 'class' => 'mySelectBoxClass']) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Import'), ['class' => 'btn btn-primary in-nuevos-reclamos']) ?>
        </div>
        <?php ActiveForm::end() ?>

</div>
    </center>
<?php endif; ?>
</div>

<?php
$this->title = Yii::t('app', 'Settings');

if (isset($errors)) {

    $dataSource = new DataSource();
    $dataSource->data($errors);


    $clientField = new DataSourceSchemaModelField('CLIENT');
    $clientField->type('string');
    $clientColumn = new GridColumn();
    $clientColumn->field('CLIENT');
    $clientColumn->title(Yii::t('app', 'Client'));


    $descriptionField = new DataSourceSchemaModelField('DESCRIPTION');
    $descriptionField->type('string');
    $descriptionColumn = new GridColumn();
    $descriptionColumn->field('DESCRIPTION');
    $descriptionColumn->title(Yii::t('app', 'Description'));

    $causeField = new DataSourceSchemaModelField('CAUSE');
    $causeField->type('string');
    $causeColumn = new GridColumn();
    $causeColumn->field('CAUSE');
    $causeColumn->title(Yii::t('app', 'Cause'));

    $grid = new Grid('grid');

    $grid->addColumn($clientColumn, $descriptionColumn, $causeColumn)
        ->scrollable(true)
        ->pageable(false)
        ->dataSource($dataSource)
        ->height(600);
    ?>

    <div class="row" id="imported">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <?= $grid->render(); ?>

            </div>
        </div>
    </div>

    <?php
}
?>