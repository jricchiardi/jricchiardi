<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AuditSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="audit-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'AuditId') ?>

    <?= $form->field($model, 'TypeAuditId') ?>

    <?= $form->field($model, 'UserId') ?>

    <?= $form->field($model, 'ClientId') ?>

    <?= $form->field($model, 'Description') ?>

    <?php // echo $form->field($model, 'Date') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
