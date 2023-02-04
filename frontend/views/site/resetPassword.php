<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>

<div class="container header-login-forgot">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<img src="<?= Yii::$app->urlManager->baseUrl ?>/images/logo-header.jpg" alt="" width="220px" height="70px" style='position:relative; top:120px;'  >
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="wrapper-login">
					<h3>Ingrese su nueva contraseña :</h3>
					  <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                                           <?= $form->field($model, 'Password')->passwordInput(['class' => 'form-control last', 'placeholder' => 'Password'])->label(false) ?>                                                											
                                           <?= $form->field($model, 'repeatpassword')->passwordInput(['class' => 'form-control last', 'placeholder' => 'Repetir Password'])->label(false) ?>                                                											                                                
                                           <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary pull-right']) ?>
					  <?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>		
</div>

