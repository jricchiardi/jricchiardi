<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ClientProduct */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Client Product',
]) . ' ' . $model->ClientProductId;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Client Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ClientProductId, 'url' => ['view', 'id' => $model->ClientProductId]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="client-product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
