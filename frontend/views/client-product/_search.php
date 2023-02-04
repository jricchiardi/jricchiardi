<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ClientProductSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="client-product-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ClientProductId') ?>

    <?= $form->field($model, 'GmidId') ?>

    <?= $form->field($model, 'TradeProductId') ?>

    <?= $form->field($model, 'ClientId') ?>

    <?= $form->field($model, 'IsForecastable') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
