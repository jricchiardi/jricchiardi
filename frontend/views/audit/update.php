<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Audit */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Audit',
]) . ' ' . $model->AuditId;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Audits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->AuditId, 'url' => ['view', 'id' => $model->AuditId]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="audit-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
