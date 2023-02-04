<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Audit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="audit-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'TypeAuditId')->textInput() ?>

    <?= $form->field($model, 'UserId')->textInput() ?>

    <?= $form->field($model, 'ClientId')->textInput() ?>

    <?= $form->field($model, 'Description')->textInput() ?>

    <?= $form->field($model, 'Date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
