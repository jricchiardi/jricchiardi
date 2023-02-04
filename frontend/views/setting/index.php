<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SettingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-index">

    <h1><?= Html::encode($this->title) ?></h1>
   
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary'=>false,
     //   'filterModel' => $searchModel,
        'columns' => [       
            'Name',            
            'Value',
            ['class' => 'yii\grid\ActionColumn','template'=>'{update}',],
        ],
    ]); ?>

</div>
