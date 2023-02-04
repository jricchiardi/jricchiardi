<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Campaign */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="campaign-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Name')->textInput() ?>
    <?= $form->field($model, 'DateBeginCampaign')->textInput() ?> 
    
    
    <div class="col-xs-12 col-sm-16 col-md-13 col-lg-6">
        <div class="box">
    
            <?= $form->field($model, 'PlanDateFrom')->textInput() ?>
            <?= $form->field($model, 'PlanDateTo')->textInput() ?>
        </div>    
    </div>    
    <div class="col-xs-12 col-sm-16 col-md-13 col-lg-6">
        <div class="box">
    
            <?= $form->field($model, 'PlanSettingDateFrom')->textInput() ?>
            <?= $form->field($model, 'PlanSettingDateTo')->textInput() ?>
        </div>
    </div>
        
    <br/>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-primary in-nuevos-reclamos']) ?>
        </div>
    

    <?php ActiveForm::end(); ?>

</div>

<script>
    $(document).ready(function () {

        $("#campaign-datebegincampaign").kendoDatePicker({
            format: "yyyy-MM-dd"
        });
        
        $("#campaign-plandatefrom").kendoDatePicker({
            format: "yyyy-MM-dd"
        });

        $("#campaign-plandateto").kendoDatePicker({
            format: "yyyy-MM-dd"
        });

        $("#campaign-plansettingdatefrom").kendoDatePicker({
            format: "yyyy-MM-dd"
        });

        $("#campaign-plansettingdateto").kendoDatePicker({
            format: "yyyy-MM-dd"
        });
    });
</script>