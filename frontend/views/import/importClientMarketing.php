<?php

use Kendo\Data\DataSource;
use Kendo\Data\DataSourceSchemaModelField;
use Kendo\UI\Grid;
use Kendo\UI\GridColumn;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Clients Marketing');
?>

<div id="divBlack" style="display:none;">
    <div id="loading" >
        <img src="<?= Yii::$app->request->baseUrl ?>/images/loading.gif" width="60"/>
        <br>
        <?=Yii::t('app','Processing...') ?>
    </div>
</div>

<div class="container full-width">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h1 class="big-title"><?= Yii::t('app', 'Import clients marketing file') ?></h1>
        </div>
    </div>
    <?php if (!isset($errors)) : ?>
        <center>
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
            <?= $form->field($model, 'file')->fileInput() ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Import'), ['class' => 'btn btn-primary in-nuevos-reclamos']) ?>
            </div>
            <?php ActiveForm::end() ?>
        </center>
    <?php endif; ?>
</div>

<?php

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
<?php } ?>
