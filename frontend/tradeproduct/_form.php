<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TradeProduct */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="trade-product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'TradeProductId')->textInput() ?>

    <?= $form->field($model, 'Description')->textInput() ?>

    <?= $form->field($model, 'PerformanceCenterId')->textInput() ?>

    <?= $form->field($model, 'Price')->textInput() ?>

    <?= $form->field($model, 'Profit')->textInput() ?>

    <?= $form->field($model, 'IsForecastable')->textInput() ?>

    <?= $form->field($model, 'IsActive')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
