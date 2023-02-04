<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		  	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
			<script src="<?= Yii::$app->urlManager->baseUrl ?>/js/respond.min.js"></script>
			<link href="<?= Yii::$app->urlManager->baseUrl ?>/css/ie.css" rel="stylesheet">
		<![endif]-->
		
		<!--[if IE 9]>
			<link id="ie9style" href="<?= Yii::$app->urlManager->baseUrl ?>/css/ie9.css" rel="stylesheet">
		<![endif]-->
</head>
<body>
    <?php include('../views/layouts/password-header.php'); ?>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-center">Copyright &copy; The Dow Chemical Company (1995-<?= date('Y') ?>). All Rights Reserved.<br></p>
        </div>
    </footer>
	
	<!-- start: JavaScript-->
	<!--[if !IE]>-->

	<script src="<?= Yii::$app->urlManager->baseUrl ?>/js/jquery-2.0.3.min.js"></script>

	<!--<![endif]-->

	<!--[if IE]>

		<script src="<?= Yii::$app->urlManager->baseUrl ?>/js/jquery-1.10.2.min.js"></script>

	<![endif]-->

	<script src="<?= Yii::$app->urlManager->baseUrl ?>/js/jquery-migrate-1.2.1.min.js"></script>
	
  <?php $this->endBody() ?>

        <?php include('../views/layouts/inc-footer.php'); ?>
</body>
</html>
<?php $this->endPage() ?>
