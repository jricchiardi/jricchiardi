<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Sale */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sale-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ClientId')->textInput() ?>

    <?= $form->field($model, 'GmidId')->textInput() ?>

    <?= $form->field($model, 'Month')->textInput() ?>

    <?= $form->field($model, 'Amount')->textInput() ?>

    <?= $form->field($model, 'Total')->textInput() ?>

    <?= $form->field($model, 'CampaignId')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
