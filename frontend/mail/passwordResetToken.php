<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->PasswordResetToken]);
?>

Hola <?= Html::encode($user->Username) ?>,

El siguiente link te ayudará a reiniciar tu Contraseña:

<?= Html::a(Html::encode($resetLink), $resetLink) ?>

<p>Muchas gracias</p>
