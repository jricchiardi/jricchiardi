<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = "Customer Unification Import";

?>
<div class="container full-width">
    <br/>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <p class="bg-warning">
                <a href="<?= Url::to(['import/unificacion-cliente-download']) ?>">
                    <img width="40px" height="40px" src="<?= Yii::$app->urlManager->baseUrl ?>/images/download.gif"
                         alt="Download Customer Unification Excel"/>
                </a>
                Download Customer Unification Excel
            </p>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h1 class="big-title">Import Customer Unification File</h1>

            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
            <?= $form->field($model, 'file')->fileInput() ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Import'), ['class' => 'btn btn-primary in-nuevos-reclamos']) ?>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>

<?php if (isset($errors)) : ?>

    <pre><?php print_r($errors); ?></pre>

<?php endif; ?>
