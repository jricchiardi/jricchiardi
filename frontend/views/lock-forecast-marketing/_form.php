<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\LockForecast */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lock-forecast-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'DateFrom')->textInput() ?>

    <?= $form->field($model, 'DateTo')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-primary in-nuevos-reclamos']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    $(document).ready(function () {

        $("#lockforecastmarketing-datefrom").kendoDatePicker({
            format: "yyyy-MM-dd"
        });

        $("#lockforecastmarketing-dateto").kendoDatePicker({
             format: "yyyy-MM-dd"
        });
    });
</script>