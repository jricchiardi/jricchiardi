<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Setting */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => Yii::t('app', 'Config.'),
]) . ' ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->SettingId]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="setting-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
