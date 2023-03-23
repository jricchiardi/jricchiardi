<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


?>


        <div class="container header-login-forgot">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <img src="<?= Yii::$app->urlManager->baseUrl ?>/images/logo-header.jpg" alt=""width="220px" height="auto" style='position:relative; top:120px;'  >
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="wrapper-login">
					<h1>Login</h1>
        				<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                                        <?= $form->field($model, 'Username')->textInput(['class' => 'form-control', 'placeholder' => 'Username'])->label(false)?>						
                                        <?= $form->field($model, 'Password')->passwordInput(['class' => 'form-control last', 'placeholder' => 'Password'])->label(false) ?>											
                                        <?= Html::a('Need Help?', ['site/request-password-reset'],['class'=>'btn pull-left']) ?>	
                                        <?= Html::submitButton('Login', ['class' => 'btn btn-primary pull-right', 'name' => 'login-button']) ?>
                                        
					<?php ActiveForm::end(); ?>

					<?= Html::a('Login AD', ['site/login_ad'],['class'=>'btn btn-primary pull-left']) ?>	

				</div>
			</div>
		</div>		
        </div>

