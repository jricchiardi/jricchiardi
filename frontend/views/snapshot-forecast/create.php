<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\SnapshotForecast */

$this->title = Yii::t('app', 'Create Snapshot Forecast');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Snapshot Forecasts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="snapshot-forecast-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
