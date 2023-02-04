<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CampaignSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Campaigns');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="campaign-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(Yii::t('app', 'Create Campaign'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'summary' =>false,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'Name',
            'IsFuture:boolean',
            'IsActual:boolean',
            'DateBeginCampaign',
            'PlanDateFrom',
             'PlanDateTo',
             'PlanSettingDateFrom',
             'PlanSettingDateTo',
            // 'IsActive',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update}',],
        ],
    ]); ?>

</div>
