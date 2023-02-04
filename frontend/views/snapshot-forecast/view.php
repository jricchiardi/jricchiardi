<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\SnapshotForecast */

$this->title = $model->CampaignId;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Snapshot Forecasts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="snapshot-forecast-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'CampaignId' => $model->CampaignId, 'ClientProductId' => $model->ClientProductId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'CampaignId' => $model->CampaignId, 'ClientProductId' => $model->ClientProductId], [
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
            'CampaignId',
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December',
            'Total',
        ],
    ]) ?>

</div>
