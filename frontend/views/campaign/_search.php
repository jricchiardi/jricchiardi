<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CampaignSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="campaign-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'CampaignId') ?>

    <?= $form->field($model, 'Name') ?>

    <?= $form->field($model, 'IsFuture') ?>

    <?= $form->field($model, 'IsActual') ?>

    <?= $form->field($model, 'PlanDateFrom') ?>

    <?php // echo $form->field($model, 'PlanDateTo') ?>

    <?php // echo $form->field($model, 'PlanSettingDateFrom') ?>

    <?php // echo $form->field($model, 'PlanSettingDateTo') ?>

    <?php // echo $form->field($model, 'IsActive') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
