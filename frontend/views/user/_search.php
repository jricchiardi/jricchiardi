<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'UserId') ?>

    <?= $form->field($model, 'DowUserId') ?>

    <?= $form->field($model, 'Username') ?>

    <?= $form->field($model, 'Fullname') ?>

    <?= $form->field($model, 'AuthKey') ?>

    <?php // echo $form->field($model, 'PasswordHash') ?>

    <?php // echo $form->field($model, 'PasswordResetToken') ?>

    <?php // echo $form->field($model, 'Email') ?>

    <?php // echo $form->field($model, 'ParentId') ?>

    <?php // echo $form->field($model, 'CreatedAt') ?>

    <?php // echo $form->field($model, 'UpdatedAt') ?>

    <?php // echo $form->field($model, 'resetPassword') ?>

    <?php // echo $form->field($model, 'IsActive') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
