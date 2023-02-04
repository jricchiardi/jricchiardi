<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SnapshotForecastSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="snapshot-forecast-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ClientProductId') ?>

    <?= $form->field($model, 'CampaignId') ?>

    <?= $form->field($model, 'January') ?>

    <?= $form->field($model, 'February') ?>

    <?= $form->field($model, 'March') ?>

    <?php // echo $form->field($model, 'April') ?>

    <?php // echo $form->field($model, 'May') ?>

    <?php // echo $form->field($model, 'June') ?>

    <?php // echo $form->field($model, 'July') ?>

    <?php // echo $form->field($model, 'August') ?>

    <?php // echo $form->field($model, 'September') ?>

    <?php // echo $form->field($model, 'October') ?>

    <?php // echo $form->field($model, 'November') ?>

    <?php // echo $form->field($model, 'December') ?>

    <?php // echo $form->field($model, 'Total') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
