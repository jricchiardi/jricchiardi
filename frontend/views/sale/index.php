<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AuditSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Sales');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audit-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-3">	
        <div class="panel panel-default">
            <div class="panel-heading"><?=Yii::t('app','Filters') ?></div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(['action' => ['index'], 'method' => 'get']); ?>
                <?php echo $form->field($searchModel, 'CampaignId')->dropDownList(yii\helpers\ArrayHelper::map(\common\models\Campaign::find()->where(['not', ['IsFuture' => true]])->orderBy('Name DESC')->all(), 'CampaignId', 'Name'), ['CampaignId' => 'Name', 'class' => 'mySelectBoxClass hasCustomSelect']) ?>            
                <?php echo $form->field($searchModel, 'ClientId')->textInput() ?>                            
                <?php echo $form->field($searchModel, 'ValueCenterId')->textInput() ?> 
                <?php echo $form->field($searchModel, 'PerformanceCenterId')->textInput() ?> 
                <?php echo $form->field($searchModel, 'TradeProductId')->textInput() ?> 
                <button type="submit" class="btn btn-primary btn-nuevo-reclamo pull-right"><?=Yii::t('app','Search') ?></button>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-9">	
        <?=
        GridView::widget([
            'summary' => false,
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'label' => Yii::t('app','Client'),
                    'attribute' => 'client.Description',
                ],
                [
                    'label' => Yii::t('app','Trade Product'),
                    'attribute' => 'gmid.tradeProduct.Description',
                ],
                [
                    'label' => 'GMID',
                    'attribute' => 'gmid.Description',
                ],
                'Month',
                'Amount',
                'Total'
            ],
        ]);
        ?>
    </div>
</div>
<script>
    $(document).ready(function () {

        $("#salesearch-valuecenterid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app','Select'); ?>",
            filter: "startswith",
            dataTextField: "Description",
            dataValueField: "ValueCenterId",
            dataSource: {
                data: <?= yii\helpers\Json::encode(\common\models\ValueCenter::find()->orderBy('Description ASC')->all()) ?>
            }
        });
        
                               
     $("#salesearch-performancecenterid").kendoDropDownList({
            cascadeFrom: "salesearch-valuecenterid",
            cascadeFromField: "ValueCenterId",
            optionLabel: "<?=Yii::t('app','Select'); ?>",
            filter: "startswith",
            dataTextField: "Description",
            dataValueField: "PerformanceCenterId",
            dataSource: {
            data: <?= yii\helpers\Json::encode(common\models\PerformanceCenter::find()->orderBy('Description ASC')->asArray()->all()) ?>
            }
    });
    
    
       
        
          $("#salesearch-tradeproductid").kendoDropDownList({
            cascadeFrom: "salesearch-performancecenterid",
            cascadeFromField: "PerformanceCenterId",
            optionLabel: "<?=Yii::t('app','Select'); ?>",
            filter: "startswith",
            dataTextField: "Description",
            dataValueField: "TradeProductId",
            dataSource: {
            data: <?= yii\helpers\Json::encode(common\models\TradeProduct::find()->orderBy('Description ASC')->asArray()->all()) ?>
            }
    });
        
        
        
        
        $("#salesearch-clientid").kendoDropDownList({
            optionLabel: "<?=Yii::t('app','Select'); ?>",
            filter: "startswith",
            dataTextField: "Description",
            dataValueField: "ClientId",
            dataSource: {
                data: <?= yii\helpers\Json::encode(\common\models\Client::find()->orderBy('Description ASC')->all()) ?>
            }
        });  
        
                              
    });
</script>
