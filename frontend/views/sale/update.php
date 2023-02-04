<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Sale */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Sale',
]) . ' ' . $model->CampaignId;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sales'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->CampaignId, 'url' => ['view', 'CampaignId' => $model->CampaignId, 'ClientId' => $model->ClientId, 'GmidId' => $model->GmidId, 'Month' => $model->Month]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="sale-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
