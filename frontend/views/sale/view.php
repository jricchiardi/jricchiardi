<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Sale */

$this->title = $model->CampaignId;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sales'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sale-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'CampaignId' => $model->CampaignId, 'ClientId' => $model->ClientId, 'GmidId' => $model->GmidId, 'Month' => $model->Month], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'CampaignId' => $model->CampaignId, 'ClientId' => $model->ClientId, 'GmidId' => $model->GmidId, 'Month' => $model->Month], [
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
            'ClientId',
            'GmidId',
            'Month',
            'Amount',
            'Total',
            'CampaignId',
        ],
    ]) ?>

</div>
