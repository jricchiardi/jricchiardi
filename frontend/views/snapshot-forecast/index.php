<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AuditSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Comparative Report');
$this->params['breadcrumbs'][] = $this->title;
$act = (int) date("m");

$sql = 'SELECT client.ClientId, client.Description, client_seller.SellerId FROM client INNER JOIN client_seller ON client.ClientId = client_seller.ClientId ORDER BY client.Description ASC';
$clientIds = Yii::$app->db->createCommand($sql)->queryAll();

?>
<div class="audit-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-3">	
        <?php if ($act >= 3) : ?>
            <p class="bg-warning">  

                <a href="<?= \yii\helpers\Url::to(['snapshot-forecast/comparative-report']) ?>">    
                    <img width="40px" height="40px" src="<?= Yii::$app->urlManager->baseUrl ?>/images/download.gif" alt="Descargar plantilla Forecast"/>
                </a><?= Yii::t('app', 'Download Comparative Report '); ?>
            </p>
        <?php else : ?>
            <div class="alert alert-warning">
                <strong>Warning!</strong> Current month must be greater than or equal to March
            </div>
        <?php endif; ?>
        <div class="panel panel-default">
            <div class="panel-heading"><?= Yii::t('app', 'Filters') ?></div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(['action' => ['index'], 'method' => 'get']); ?>
                <?php echo $form->field($searchModel, 'CampaignId')->dropDownList(yii\helpers\ArrayHelper::map(\common\models\Campaign::find()->where(['not', ['IsFuture' => true]])->orderBy('Name DESC')->all(), 'CampaignId', 'Name'), ['CampaignId' => 'Name', 'class' => 'mySelectBoxClass hasCustomSelect']) ?>            
                <?php
                if (!\Yii::$app->user->can(\common\models\AuthItem::ROLE_DSM))
                    echo $form->field($searchModel, 'DsmId')->textInput();
                ?>                            
                <?php echo $form->field($searchModel, 'SellerId')->textInput() ?>   
                <?php echo $form->field($searchModel, 'ClientId')->textInput() ?> 
                <button type="submit" class="btn btn-primary btn-nuevo-reclamo pull-right"><?= Yii::t('app', 'Search') ?></button>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-9">	
        <?=
        GridView::widget([
            'formatter' => [
                'class' => 'yii\i18n\Formatter',
                'nullDisplay' => '-',
            ],
            'summary' => false,
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'label' => Yii::t('app', 'Client'),
                    'attribute' => 'clientProduct.client.Description',
                ],
                [
                    'label' => Yii::t('app', 'Trade Product'),
                    'attribute' => 'clientProduct.tradeProduct.Description',
                ],
                [
                    'label' => 'GMID',
                    'attribute' => 'clientProduct.gmid.Description',
                ],
                'January:decimal',
                'February:decimal',
                'March:decimal',
                'April:decimal',
                'May:decimal',
                'June:decimal',
                'July:decimal',
                'August:decimal',
                'September:decimal',
                'October:decimal',
                'November:decimal',
                'December:decimal',
            // 'Total',
            ],
        ]);
        ?>
    </div>
</div>


<script>
    $(document).ready(function () {

<?php
if (\Yii::$app->user->can(\common\models\AuthItem::ROLE_RSM))
    $dsms = \common\models\User::find()->select(['UserId', 'user.UserId AS DsmId', 'user.Fullname'])->innerJoinWith('itemNames')->where(['name' => common\models\AuthItem::ROLE_DSM, 'ParentId' => Yii::$app->user->identity->UserId])->asArray()->orderBy('user.Fullname ASC')->all();
else
    $dsms = \common\models\User::find()->select(['UserId', 'user.UserId AS DsmId', 'user.Fullname'])->innerJoinWith('itemNames')->where(['name' => common\models\AuthItem::ROLE_DSM])->asArray()->orderBy('user.Fullname ASC')->all();
?>
        $("#snapshotforecastsearch-dsmid").kendoDropDownList({
            optionLabel: "<?= Yii::t('app', 'Select') ?>",
            filter: "startswith",
            dataTextField: "Fullname",
            dataValueField: "DsmId",
            dataSource: {
                data: <?= yii\helpers\Json::encode($dsms) ?>
            }
        });

        $("#snapshotforecastsearch-sellerid").kendoDropDownList({
            cascadeFrom: "snapshotforecastsearch-dsmid",
            cascadeFromField: "DsmId",
            optionLabel: "<?= Yii::t('app', 'Select') ?>",
            filter: "startswith",
            dataTextField: "Fullname",
            dataValueField: "SellerId",
            dataSource: {
                data: <?= yii\helpers\Json::encode(\common\models\User::find()->select(['UserId', 'user.UserId AS SellerId', 'user.Fullname', 'user.ParentId AS DsmId'])->innerJoinWith('itemNames')->where(['name' => common\models\AuthItem::ROLE_SELLER])->asArray()->orderBy('user.Fullname ASC')->all()) ?>
            }
        });

        $("#snapshotforecastsearch-clientid").kendoDropDownList({
            cascadeFrom: "snapshotforecastsearch-sellerid",
            cascadeFromField: "SellerId",
            optionLabel: "<?= Yii::t('app', 'Select') ?>",
            filter: "startswith",
            dataTextField: "Description",
            dataValueField: "ClientId",
            dataSource: {
                data: <?= yii\helpers\Json::encode($clientIds) ?>
            }
        });
    });
</script>