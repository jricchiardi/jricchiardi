<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Setting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="setting-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'DisplayName')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'Name')->hiddenInput()->label(false) ?>    
    <?= $form->field($model, 'Value')->textInput()->label($model->Name) ?>
    <div class="form-group">      
        <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary in-nuevos-reclamos']) ?>
    </div>   

    <?php ActiveForm::end(); ?>

</div>

<script>
    $(document).ready(function () {

        $("#setting-value").kendoDatePicker({            
            // defines the start view
            start: "year",
            // defines when the calendar should return date
            depth: "year",
            // display month and year in the input
            format: "MM"
        });
    });
</script>
