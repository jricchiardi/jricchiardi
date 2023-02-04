<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\LockForecast */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => Yii::t('app','Lock Forecasts'),
]) ;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lock Forecasts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->LockId, 'url' => ['view', 'id' => $model->LockId]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="lock-forecast-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
