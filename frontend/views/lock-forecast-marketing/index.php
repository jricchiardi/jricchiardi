<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LockForecastSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Bloqueo Marketing";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lock-forecast-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Lock Forecast'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        "summary" => false,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'DateFrom',
            'DateTo',
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}',],
        ],
    ]);
    ?>

</div>
