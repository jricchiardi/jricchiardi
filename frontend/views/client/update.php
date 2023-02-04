<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Client */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Client',
]) . ' ' . $model->ClientId;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Clients'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ClientId, 'url' => ['view', 'id' => $model->ClientId]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="client-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
