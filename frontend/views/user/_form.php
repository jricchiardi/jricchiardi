<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Username')->textInput() ?>

    <?= $form->field($model, 'Fullname')->textInput() ?>

    <?= $form->field($model, 'PasswordHash')->textInput() ?>

    <?= $form->field($model, 'PasswordResetToken')->textInput() ?>

    <?= $form->field($model, 'Email')->textInput() ?>

    <?= $form->field($model, 'ParentId')->textInput() ?>

    <?= $form->field($model, 'resetPassword')->textInput() ?>

    <?= $form->field($model, 'IsActive')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
