<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\LockForecast */

$this->title = $model->LockId;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lock Forecasts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lock-forecast-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->LockId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->LockId], [
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
            'LockId',
            'DateFrom',
            'DateTo',
        ],
    ]) ?>

</div>
