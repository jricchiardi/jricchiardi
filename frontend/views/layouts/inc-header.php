<?php

use common\models\AuthItem;
use yii\bootstrap\Nav;
use yii\helpers\Url;

?>
<div class="navbar">
    <div class="top-navbar">
        <span><?= Yii::t('app', 'Menu') ?> </span>
        <?= yii\helpers\Html::a('Logout', ['/site/logout']); ?>
    </div>
    <form action="" class="form-search" style="display: none;">
        <button><img src="<?= Yii::$app->urlManager->baseUrl ?>/images/ico-search.png" alt=""></button>
    </form>

    <?php
    $menuItems = [];
    $pathRol = yii\helpers\Url::to(['user/reset-password', 'id' => Yii::$app->user->identity->UserId]);

    $menuItems[] = ['label' => Yii::t('app', 'Dashboard'), 'url' => ['/site/index']];

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {

        if (Yii::$app->user->can(AuthItem::ROLE_RSM)) {
            //$menuItems[] = ['label' => Yii::t('app', 'Forecast'), 'url' => ['/forecast/view']];
            $menuItems[] = ['label' => Yii::t('app', 'Products'), 'url' => ['/product/index']];
            $menuItems[] = ['label' => Yii::t('app', 'Comparative Report'), 'url' => ['/snapshot-forecast/index']];
            $menuItems[] = ['label' => Yii::t('app', 'Consolid Export'), 'url' => ['/forecast/export-report-consolid']];
            $menuItems[] = ['label' => Yii::t('app', 'Report Comparative Sellers'), 'url' => ['/report/resume']];
            $menuItems[] = ['label' => Yii::t('app', 'Users'), 'url' => ['/user/index']];
			$menuItems[] = ['label' => Yii::t('app', 'SIS'), 'url' => ['/sis']];
        }

        if (Yii::$app->user->can(AuthItem::ROLE_DSM)) {
            //$menuItems[] = ['label' => Yii::t('app', 'Forecast'), 'url' => ['/forecast/view']];
            $menuItems[] = ['label' => Yii::t('app', 'Products'), 'url' => ['/product/index']];
            $menuItems[] = ['label' => Yii::t('app', 'Comparative Report'), 'url' => ['/snapshot-forecast/index']];
            $menuItems[] = ['label' => Yii::t('app', 'Consolid Export'), 'url' => ['/forecast/export-report-consolid']];
            $menuItems[] = ['label' => Yii::t('app', 'Report Comparative Sellers'), 'url' => ['/report/resume']];
            $menuItems[] = ['label' => Yii::t('app', 'Users'), 'url' => ['/user/index']];
			$menuItems[] = ['label' => Yii::t('app', 'SIS'), 'url' => ['/sis']];
        }

        if (Yii::$app->user->can(AuthItem::ROLE_SELLER)) {
            $menuItems[] = ['label' => Yii::t('app', 'Clientes'), 'url' => ['/client/index']];
            $menuItems[] = ['label' => Yii::t('app', 'Config. Products Mass'), 'url' => ['/client-product/wizard']];
            $menuItems[] = ['label' => Yii::t('app', 'Config. Products Individual'), 'url' => ['/client/clients']];
            $menuItems[] = ['label' => Yii::t('app', 'Forecast'), 'url' => ['/forecast/index']];
            $menuItems[] = ['label' => Yii::t('app', 'Import Offline Forecast'), 'url' => ['/import/offline']];
            $menuItems[] = ['label' => Yii::t('app', 'Import Offline Plan'), 'url' => ['/import/offline-plan']];
            $menuItems[] = ['label' => Yii::t('app', 'Plan'), 'url' => ['/plan/index']];
			$menuItems[] = ['label' => Yii::t('app', 'SIS'), 'url' => ['/sis']];
        }

        if (Yii::$app->user->can(AuthItem::ROLE_ADMIN)) {
            $menuItems[] = ['label' => Yii::t('app', 'Forecast'), 'url' => ['/forecast/index']];
			
            $menuItems[] = ['label' => Yii::t('app', 'Import Offline Forecast'), 'url' => ['/import/offline']];
            $menuItems[] = ['label' => Yii::t('app', 'Campaign'), 'url' => ['/campaign/index']];
            $menuItems[] = ['label' => Yii::t('app', 'Audit'), 'url' => ['/audit/index']];
            $menuItems[] = ['label' => Yii::t('app', 'Lock'), 'url' => ['/lock-forecast/index']];
            $menuItems[] = ['label' => Yii::t('app', 'Lock Marketing'), 'url' => ['/lock-forecast-marketing/index']];
            $menuItems[] = ['label' => Yii::t('app', 'Clients'), 'url' => ['/client/group']];
            $menuItems[] = ['label' => Yii::t('app', 'Settings'), 'url' => ['/setting/index']];
            $menuItems[] = ['label' => Yii::t('app', 'Consolid Export'), 'url' => ['/forecast/export-report-consolid']];
            $menuItems[] = ['label' => Yii::t('app', 'Import Sales'), 'url' => ['/import/sale']];
            $menuItems[] = ['label' => Yii::t('app', 'Import Customers'), 'url' => ['/import/customer']];
            $menuItems[] = ['label' => Yii::t('app', 'Import Customers Marketing'), 'url' => ['/import/customer-marketing']];
            $menuItems[] = ['label' => Yii::t('app', 'Import Products'), 'url' => ['/import/product']];
            $menuItems[] = ['label' => Yii::t('app', 'Import CyO'), 'url' => ['/import/cyo']];
            $menuItems[] = ['label' => Yii::t('app', 'Products'), 'url' => ['/product/index']];
            $menuItems[] = ['label' => Yii::t('app', 'Comparative Report'), 'url' => ['/snapshot-forecast/index']];
            $menuItems[] = ['label' => Yii::t('app', 'Report Comparative Sellers'), 'url' => ['/report/resume']];
            $menuItems[] = ['label' => Yii::t('app', 'Users'), 'url' => ['/user/index']];
            $menuItems[] = ['label' => Yii::t('app', 'Validation Plan'), 'url' => ['/import/setting']];
            $menuItems[] = ['label' => Yii::t('app', 'Sales'), 'url' => ['/sale/index']];
            //$menuItems[] = ['label' => 'Test reporte segmento comercio', 'url' => ['/test/reporte-segmento-comercio']];
            //$menuItems[] = ['label' => 'Test reporte segmento negocio', 'url' => ['/test/reporte-segmento-negocio']];
            //$menuItems[] = ['label' => 'Importar Oportunidad', 'url' => ['/import/opportunity']];
            //$menuItems[] = ['label' => 'Importar Selling Out', 'url' => ['/import/selling-out']];
            $menuItems[] = ['label' => 'Importe automatico de SAP', 'url' => ['/check-auto-sap-import']];
            $menuItems[] = ['label' => 'Importar Unificación de Clientes', 'url' => ['/import/unificacion-cliente']];
            $menuItems[] = ['label' => 'Importar Asociación PM Producto', 'url' => ['/import/association-pm-product']];
            //$menuItems[] = ['label' => Yii::t('app', 'Consolid Export Marketing'), 'url' => ['/forecast-marketing/export-report-consolid']];
            //$menuItems[] = ['label' => 'Clientes Pioneer', 'url' => ['/pioneer-client']];
        }

        if (Yii::$app->user->can(AuthItem::ROLE_SIS_ADMIN)) {
			$menuItems[] = ['label' => 'SIS', 'url' => ['/sis']];
	        $menuItems[] = ['label' => Yii::t('app', 'Open Orders'), 'url' => ['/open-orders/index']];
            $menuItems[] = ['label' => Yii::t('app', 'Facturados No Cont.'), 'url' => ['/invoice-not-counted/index']];
            $menuItems[] = ['label' => Yii::t('app', 'Despachados No Fc'), 'url' => ['/desp-no-fc/index']];
			$menuItems[] = ['label' => Yii::t('app', 'Forecast IBP'), 'url' => ['/forecastibp/index']];
        }

        if (Yii::$app->user->can(AuthItem::ROLE_DIRECTOR_COMERCIAL)) {
            $menuItems[] = ['label' => Yii::t('app', 'Comparative Report'), 'url' => ['/snapshot-forecast/index']];
            $menuItems[] = ['label' => Yii::t('app', 'Consolid Export'), 'url' => ['/forecast/export-report-consolid']];
            $menuItems[] = ['label' => Yii::t('app', 'Report Comparative Sellers'), 'url' => ['/report/resume']];
            $menuItems[] = ['label' => Yii::t('app', 'Users'), 'url' => ['/user/index']];
			$menuItems[] = ['label' => Yii::t('app', 'SIS'), 'url' => ['/sis']];
        }

        if (Yii::$app->user->can(AuthItem::ROLE_PM)) {
            array_pop($menuItems);
            $menuItems[] = ['label' => Yii::t('app', 'Forecast'), 'url' => ['/forecast-marketing/index']];
            $menuItems[] = ['label' => Yii::t('app', 'Import Offline Forecast'), 'url' => ['/import/offline-forecast-marketing']];
//            $menuItems[] = ['label' => Yii::t('app', 'Link Customers'), 'url' => ['/pm-client']];
            $menuItems[] = ['label' => Yii::t('app', 'Link DSM'), 'url' => ['/pm-dsm']];
			$menuItems[] = ['label' => Yii::t('app', 'SIS'), 'url' => ['/sis']];
        }
		
		
        if (Yii::$app->user->can(AuthItem::ROLE_SIS_VIEWER)) {
            $menuItems[] = ['label' => Yii::t('app', 'SIS'), 'url' => ['/sis']];
        }
    }

    echo Nav::widget([
        'options' => ['class' => 'links-navbar ', 'style' => " overflow-y: auto;"],
        'items' => $menuItems,
    ]);
    ?>


</div>

<?php $notifications = Yii::$app->notificationscomponents->getNotifications(null, $count);
?>
<header class="navbar-fixed-top">
    <div class="container full-width">
        <div class="row">
            <div class="col-xs-3 col-sm-6 col-md-6 col-lg-6">
                <a href="<?= yii\helpers\Url::to(['site/index']) ?>"><img height="40px"
                                                                          src="<?= Yii::$app->urlManager->baseUrl ?>/images/logo.png"
                                                                          alt="" class="logo"></a>
            </div>
            <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6 header-right">
                <div class="notifications without hide">
                    <img src="<?= Yii::$app->urlManager->baseUrl ?>/images/ico-alarm-not.png" alt="">
                </div>
                <?php if (Yii::$app->user->can(AuthItem::ROLE_SELLER)) : ?>
                    <a href="<?= Url::to(['plan/export']) ?>">
                        Plan
                        <img width="40px" height="40px" src="<?= Yii::$app->urlManager->baseUrl ?>/images/download.gif"
                             alt="Descargar plantilla Forecast"/>
                    </a>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="<?= Url::to(['forecast/export']) ?>">
                        Forecast
                        <img width="40px" height="40px" src="<?= Yii::$app->urlManager->baseUrl ?>/images/download.gif"
                             alt="Descargar plantilla Forecast"/>
                    </a>
                <?php endif; ?>
                <?php if (Yii::$app->user->can(AuthItem::ROLE_PM)) : ?>
                    <a href="<?= Url::to(['forecast-marketing/export']) ?>">
                        Forecast
                        <img width="40px" height="40px" src="<?= Yii::$app->urlManager->baseUrl ?>/images/download.gif"
                             alt="Descargar plantilla Forecast"/>
                    </a>
                <?php endif; ?>
                <a href="javascript:" class="notifications">
                    <img src="<?= Yii::$app->urlManager->baseUrl ?>/images/ico-alarm.png" alt="">
                    <?php echo $count; ?>
                </a>

                <a type="button" href="<?= $pathRol ?>" class="user">
                    <?= substr(Yii::$app->user->identity->Fullname, 0, 10); ?>
                </a>

                <a href="javascript:" class="menu">
                    <img src="<?= Yii::$app->urlManager->baseUrl ?>/images/ico-menu.png" alt="">
                </a>
            </div>
        </div>
    </div>
    <div class="notifications-panel">
        <?php echo $notifications; ?>
    </div>
</header>
