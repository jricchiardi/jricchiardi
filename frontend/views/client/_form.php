<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Client */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="client-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ClientId')->textInput() ?>

    <?= $form->field($model, 'ClientTypeId')->textInput() ?>

    <?= $form->field($model, 'GroupId')->textInput() ?>

    <?= $form->field($model, 'CountryId')->textInput() ?>

    <?= $form->field($model, 'Description')->textInput() ?>

    <?= $form->field($model, 'IsGroup')->textInput() ?>

    <?= $form->field($model, 'IsActive')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
