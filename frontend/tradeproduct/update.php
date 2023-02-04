<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TradeProduct */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Trade Product',
]) . ' ' . $model->TradeProductId;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Trade Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->TradeProductId, 'url' => ['view', 'id' => $model->TradeProductId]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="trade-product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
