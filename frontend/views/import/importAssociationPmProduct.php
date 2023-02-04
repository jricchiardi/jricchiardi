<?php

use Kendo\Data\DataSource;
use Kendo\Data\DataSourceSchemaModelField;
use Kendo\UI\Grid;
use Kendo\UI\GridColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="container full-width">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h1 class="big-title">Importación de Asociación PM con Producto</h1>
            <a style="margin-left: 1rem;" href="<?= Url::to(['import/association-pm-product-download']) ?>">
                Descargar Asociación PM Producto
                <img width="40px" height="40px" src="<?= Yii::$app->urlManager->baseUrl ?>/images/download.gif"
                     alt="Descargar plantilla asociación pm producto"/>
            </a>
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
$this->title = "Asociación PM Producto";

if (isset($errors)) {

    $dataSource = new DataSource();
    $dataSource->data($errors);

    $gmidField = new DataSourceSchemaModelField('GMID');
    $gmidField->type('number');

    $tradeField = new DataSourceSchemaModelField('TRADEPRODUCT');
    $tradeField->type('number');

    $userField = new DataSourceSchemaModelField('USERID');
    $userField->type('number');

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

    $grid->addColumn($tradeField, $gmidField, $userField, $descriptionColumn, $causeColumn)
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
