<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ClientSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="client-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ClientId') ?>

    <?= $form->field($model, 'ClientTypeId') ?>

    <?= $form->field($model, 'GroupId') ?>

    <?= $form->field($model, 'CountryId') ?>

    <?= $form->field($model, 'Description') ?>

    <?php // echo $form->field($model, 'IsGroup') ?>

    <?php // echo $form->field($model, 'IsActive') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
