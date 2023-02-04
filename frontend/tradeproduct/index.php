<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TradeProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Trade Products');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trade-product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Trade Product'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'TradeProductId',
            'Description',
            'PerformanceCenterId',
            'Price',
            'Profit',
            // 'IsForecastable',
            // 'IsActive',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
