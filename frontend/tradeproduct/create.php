<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\TradeProduct */

$this->title = Yii::t('app', 'Create Trade Product');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Trade Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trade-product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
