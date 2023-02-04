<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SnapshotForecast */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Snapshot Forecast',
]) . ' ' . $model->CampaignId;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Snapshot Forecasts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->CampaignId, 'url' => ['view', 'CampaignId' => $model->CampaignId, 'ClientProductId' => $model->ClientProductId]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="snapshot-forecast-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
