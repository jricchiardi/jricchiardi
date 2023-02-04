<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>

<div class="container header-login-forgot">
		<div class="row">
			<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
				<img src="<?= Yii::$app->urlManager->baseUrl ?>/images/logo-header.jpg" alt="" width="150px" height="70px" style='position:relative; left:180px; top:120px;'  >
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="wrapper-login">
					<h1>Recuperar Contraseña</h1>
					  <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>                                                
                                                <?= $form->field($model, 'Email')->textInput(['class' => 'form-control last', 'placeholder' => 'Dirección de correo electrónico'])->label(false)?>												
                                                <a href="<?= \yii\helpers\Url::to('login') ?>" class="btn pull-left">Login</a>
                                                 <?= Html::submitButton('Recordar', ['class' => 'btn btn-primary pull-right']) ?>						
					  <?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>		
</div>


