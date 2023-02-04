<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<h1><?=Yii::t('app','Change Password') ?></h1>
<div class="container header-login-forgot">    
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="wrapper-login">
					<h3><?=Yii::t('app','Enter your new password') ?>:</h3>
					  <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                                           <?= $form->field($model, 'Password')->passwordInput(['class' => 'form-control last', 'placeholder' => Yii::t('app','Password')])->label(false) ?>                                                											
                                           <?= $form->field($model, 'repeatpassword')->passwordInput(['class' => 'form-control last', 'placeholder' => Yii::t('app','Repeat Password')])->label(false) ?>                                                											                                                
                                           <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary pull-right']) ?>
					  <?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>		
</div>

