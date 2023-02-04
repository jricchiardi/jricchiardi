<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\LockForecast */

$this->title = Yii::t('app', 'Create Lock Forecast');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lock Forecasts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lock-forecast-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
