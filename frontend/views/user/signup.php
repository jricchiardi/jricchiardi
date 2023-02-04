<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = Yii::t('app','User Register');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    
    <div class="row">
        <div class="col-lg-12">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <?= $form->field($model, 'Username') ?>
                <?= $form->field($model, 'Fullname') ?>            
                <?= $form->field($model, 'Email') ?>
                <?= $form->field($model, 'Rol')->dropDownList($roles, ['prompt' => Yii::t('app', 'Select'), 'class' => 'mySelectBoxClass']); ?>
                <?= $form->field($model, 'Password')->passwordInput() ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t("app",'Save'), ['class' => 'btn btn-primary in-nuevos-reclamos', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
