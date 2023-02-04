<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SaleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sale-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ClientId') ?>

    <?= $form->field($model, 'GmidId') ?>

    <?= $form->field($model, 'Month') ?>

    <?= $form->field($model, 'Amount') ?>

    <?= $form->field($model, 'Total') ?>

    <?php // echo $form->field($model, 'CampaignId') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
