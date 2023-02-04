<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AuditSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Audits');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audit-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-3">                                  
        <div class="panel panel-default">
            <div class="panel-heading"><?=Yii::t("app","Filters"); ?></div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(['action' => ['index'], 'method' => 'get',   'enableClientScript' => false,]); ?>                               
                <input type="submit" value="" name="export" id="export"  style=" width='40px';background:url('<?= Yii::$app->urlManager->baseUrl ?>/images/download.gif'); float:right; display:block; width: 42px;height: 38px;border: none;background-size: contain;background-repeat: no-repeat;"/>  <br/>               
                <?php echo $form->field($searchModel, 'CampaignId')->dropDownList(yii\helpers\ArrayHelper::map(\common\models\Campaign::find()->where(['not', ['IsFuture' => true]])->orderBy('Name DESC')->all(), 'CampaignId', 'Name'), ['CampaignId' => 'Name','class'=>'mySelectBoxClass hasCustomSelect']) ?>            
                <?php echo $form->field($searchModel, 'TypeAuditId')->dropDownList(yii\helpers\ArrayHelper::map(\common\models\TypeAudit::find()->orderBy('Name DESC')->all(), 'TypeAuditId', 'Name'), ['prompt'=>Yii::t('app','Select'),'TypeAuditId' => 'Name','class'=>'mySelectBoxClass hasCustomSelect']) ?>            
                <?php echo $form->field($searchModel, 'RsmId') ?>
                <?php echo $form->field($searchModel, 'DsmId') ?>
                <?php echo $form->field($searchModel, 'UserId') ?>
                <?php echo $form->field($searchModel, 'dateFrom') ?>
                <?php echo $form->field($searchModel, 'dateTo') ?>                
                <button type="submit" name="send" id="send" class="btn btn-primary btn-nuevo-reclamo pull-right"><?=Yii::t("app","Search") ?></button>                
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
                'AuditId',
                [
                   'label'=> Yii::t('app','Audit Type'),
                   'attribute' => 'typeAudit.Name',                   
                  ],   
                   [
                   'label'=> Yii::t('app','User'),
                   'attribute' => 'user.Fullname',                   
                  ],               
                  [
                   'label'=> Yii::t('app','Client'),
                   'attribute' => 'client.Description',                   
                  ],
                'Date:datetime',
            ],
        ]);
        ?>
    </div>
</div>
<script>
    $(document).ready(function () {

        $("#auditsearch-datefrom").kendoDatePicker({
            format: "yyyy-MM-dd"
        });

        $("#auditsearch-dateto").kendoDatePicker({
            format: "yyyy-MM-dd"
        });



   $("#auditsearch-rsmid").kendoDropDownList({         
            optionLabel: "<?=Yii::t('app','Select') ?>",
            filter: "startswith",
            dataTextField: "Fullname",
            dataValueField: "UserId",
            dataSource: {
            data: <?= yii\helpers\Json::encode(common\models\User::find()->select(['UserId','user.UserId AS RsmId','user.Fullname'])->joinWith('itemNames')->where(['[auth_item].name' => common\models\AuthItem::ROLE_RSM])->orderBy('Fullname ASC')->asArray()->all()) ?>
            }
    });
    
    
      $("#auditsearch-dsmid").kendoDropDownList({
            cascadeFrom: "auditsearch-rsmid",
            cascadeFromField: "RsmId",      
            optionLabel: "<?=Yii::t('app','Select') ?>",
            filter: "startswith",
            dataTextField: "Fullname",
            dataValueField: "UserId",
            dataSource: {
            data: <?= yii\helpers\Json::encode(common\models\User::find()->select(['UserId','user.ParentId AS RsmId','user.Fullname'])->joinWith('itemNames')->where(['[auth_item].name' => common\models\AuthItem::ROLE_DSM])->orderBy('Fullname ASC')->asArray()->all()) ?>
            }
        });
    
       $("#auditsearch-userid").kendoDropDownList({
            cascadeFrom: "auditsearch-dsmid",
            cascadeFromField: "DsmId",
            optionLabel: "<?=Yii::t('app','Select') ?>",
            filter: "startswith",
            dataTextField: "Fullname",
            dataValueField: "UserId",
            dataSource: {
                data: <?= yii\helpers\Json::encode(\common\models\User::find()->select(['UserId','user.ParentId AS DsmId','user.Fullname'])->innerJoinWith('itemNames')->where(['name' => common\models\AuthItem::ROLE_SELLER])->asArray()->orderBy('user.Fullname ASC')->all()) ?>
            }
        });
        
        
        
                    
      
                
    });
</script>