<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TradeProductSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="trade-product-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'TradeProductId') ?>

    <?= $form->field($model, 'Description') ?>

    <?= $form->field($model, 'PerformanceCenterId') ?>

    <?= $form->field($model, 'Price') ?>

    <?= $form->field($model, 'Profit') ?>

    <?php // echo $form->field($model, 'IsForecastable') ?>

    <?php // echo $form->field($model, 'IsActive') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
