<?php

use yii\helpers\Html;
use frontend\assets\AppAsset;
use frontend\assets\KendoAsset;
use frontend\assets\PlaceHolderAsset;
use frontend\widgets\Alert;
use kartik\widgets\AlertBlock;
use frontend\Kendo;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
KendoAsset::register($this);
?>

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

        <link id="page_favicon" href="<?= Yii::$app->urlManager->baseUrl ?>/images/favicon.ico" rel="icon" type="image/x-icon">        
        <!-- start: JavaScript-->
        <!--[if !IE]>-->

        <script src="<?= Yii::$app->urlManager->baseUrl ?>/js/jquery-2.0.3.min.js"></script>

        <!--<![endif]-->

        <!--[if IE]>

                <script src="<?= Yii::$app->urlManager->baseUrl ?>/js/jquery-1.10.2.min.js"></script>

        <![endif]-->

    </head>
    <body> 
        <?php include('../views/layouts/inc-header.php'); ?>

        <?php $this->beginBody() ?>
        <?PHP
//        AlertBlock::widget([
//            'useSessionFlash' => true,
//            'type' => AlertBlock::TYPE_ALERT,
//            'delay' => 10000
//        ]);
        // Alert::widget() 
        ?>
        <div class="container full-width">

            <?php
            foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
            }
            ?>
<?= $content ?>

        </div>
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <p class="pull-center">Copyright &copy; The Dow Chemical Company (1995-<?= date('Y') ?>). All Rights Reserved.<br></p>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Modal -->
        <?=
        \yii\bootstrap\Modal::widget();
        ?>

        <div id="alertSuccess" style="display: none;">               
            <div class="alert alert-success" role="alert">
<?= Yii::t('app', 'Your changes have been saved'); ?>
            </div>

            <button type="button" class="btn btn-gray in-nuevos-reclamos" data-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
        </div>

        <div id="alertFail" style="display: none;">
            <div class="alert alert-error" role="alert">
<?= Yii::t('app', 'Unexpected Error'); ?>
            </div>
            <button type="button" class="btn btn-gray in-nuevos-reclamos" data-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
        </div>



        <?php $this->endBody() ?>

<?php include('../views/layouts/inc-footer.php'); ?>

    </body>
</html>
<?php $this->endPage() ?>