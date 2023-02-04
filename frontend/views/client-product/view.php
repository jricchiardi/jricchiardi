<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ClientProduct */

$this->title = $model->ClientProductId;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Client Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->ClientProductId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->ClientProductId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ClientProductId',
            'GmidId',
            'TradeProductId',
            'ClientId',
            'IsForecastable',
        ],
    ]) ?>

</div>
