<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
?>
<div class="navbar">
    <div class="top-navbar">
        <span>Menu</span>
        <?= yii\helpers\Html::a('Logout', ['/site/logout']); ?>
    </div>
    <form action="" class="form-search" style="display: none;">
        <button><img src="<?= Yii::$app->urlManager->baseUrl ?>/images/ico-search.png" alt=""></button>        
    </form>

    <?php
    $menuItems = [];
  
   
    echo Nav::widget([
        'options' => ['class' => 'links-navbar'],
        'items' => $menuItems,
    ]);
    ?>
  
    
</div>

             			
<header class="navbar-fixed-top">
    <div class="container full-width">
        <div class="row">
            <div class="col-xs-3 col-sm-6 col-md-6 col-lg-6">
                <a href="<?=yii\helpers\Url::to(['site/index']) ?>"><img height="40px" src="<?= Yii::$app->urlManager->baseUrl ?>/images/logo.png"  alt="" class="logo"></a>
            </div>
            <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6 header-right">
                <div class="notifications without hide">
                    <img src="<?= Yii::$app->urlManager->baseUrl ?>/images/ico-alarm-not.png" alt="">
                </div>
                     <?php if ( Yii::$app->user->identity) : ?>   
                  <a type="button" href="#" class="user">
                    <?= ( Yii::$app->user->identity) ? Yii::$app->user->identity->Fullname : '-' ?>
                  </a>           
                 <?php endif; ?>
                <a href="javascript:;" class="menu">
                    <img src="<?= Yii::$app->urlManager->baseUrl ?>/images/ico-menu.png" alt="">
                </a>
            </div>
        </div>
    </div>    
</header>
