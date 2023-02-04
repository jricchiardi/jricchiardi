<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SnapshotForecast */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="snapshot-forecast-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ClientProductId')->textInput() ?>

    <?= $form->field($model, 'CampaignId')->textInput() ?>

    <?= $form->field($model, 'January')->textInput() ?>

    <?= $form->field($model, 'February')->textInput() ?>

    <?= $form->field($model, 'March')->textInput() ?>

    <?= $form->field($model, 'April')->textInput() ?>

    <?= $form->field($model, 'May')->textInput() ?>

    <?= $form->field($model, 'June')->textInput() ?>

    <?= $form->field($model, 'July')->textInput() ?>

    <?= $form->field($model, 'August')->textInput() ?>

    <?= $form->field($model, 'September')->textInput() ?>

    <?= $form->field($model, 'October')->textInput() ?>

    <?= $form->field($model, 'November')->textInput() ?>

    <?= $form->field($model, 'December')->textInput() ?>

    <?= $form->field($model, 'Total')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
