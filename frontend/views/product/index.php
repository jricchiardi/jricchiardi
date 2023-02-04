<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = Yii::t('app', 'Setup Products');
?>

<h1><?= Html::encode($this->title) ?></h1>
<div id="productGrid"> </div>
<div class="form-group">      
   <?php if (Yii::$app->user->can(\common\models\AuthItem::ROLE_ADMIN)) : ?>
    <?= Html::submitButton(Yii::t('app', 'Save'), ['id' => 'send', 'class' => 'btn btn-primary in-nuevos-reclamos']) ?>
   <?php endif; ?>
</div>   


<div class="modal fade" id="confirmModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t("app", "Result") ?></h4>
            </div>
            <div class="modal-body">
                <p><?= Yii::t("app", "The information was saved correctly!"); ?></p>
            </div>
        
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t("app", "Close") ?></button>
            </div>
         
        </div>

    </div>
</div>
<?php $this->registerJs('products();', $this::POS_READY); ?>

<script>
    $("#send").click(function ()
    {
        sendProducts();
    });

    var products = function ()
    {
        kendo.culture("en-US");
        $("#productGrid").kendoGrid({
            dataSource:
                    {
                        data: <?= json_encode($products) ?>,
                        schema:
                                {
                                    model:
                                            {
                                                fields: {
                                                    IsActive: {type: "boolean"},
                                                    GmidId: {type: "string"},
                                                    TradeProductId: {type: "string"},
                                                    Description: {type: "string"},
                                                    TradeProduct: {type: 'string'},
                                                    PerformanceCenter: {type: "string"},
                                                    ValueCenter: {type: "string"},
                                                    Price: {type: "number"},
                                                    Profit: {type: "number"},
                                                }
                                            }
                                }
                    },
            filterable: true,
            columnMenu: true,
            sortable: true,
            pageable: false,
            scrollable: true,
            height: 600,
            columns: [
            <?php if (Yii::$app->user->can(\common\models\AuthItem::ROLE_ADMIN)) : ?>
                {
                    sortable: false,
                    filterable: false,
                    field: "IsActive",
                    title: "Forcasteable", // para que no me rompan la chota
                    template: "<input type='checkbox' data-type='boolean'  data-bind='checked:IsActive'  id='#=(GmidId == null) ? 't'+TradeProductId : 'g'+GmidId #' class='product' />",
                },
           <?php endif; ?>  
                
                {
                    field: "ValueCenter",
                    title: "<?= Yii::t('app', 'Value Center') ?>"
                },
                {
                    field: "PerformanceCenter",
                    title: "<?= Yii::t('app', 'Performance Center') ?>"
                },
                {
                    hidden: true,
                    field: "TradeProductId",
                    title: "<?= Yii::t('app', 'TradeProduct') ?>"
                },
                {
                    hidden: true,
                    field: "GmidId",
                    title: "<?= Yii::t('app', 'GmidId') ?>"
                },
                {
                    field: "TradeProduct",
                    title: "<?= Yii::t('app', 'TradeProduct') ?>",
                },
                {
                    field: "Description",
                    title: "<?= Yii::t('app', 'Description') ?>"
                },
                 <?php if (Yii::$app->user->can(\common\models\AuthItem::ROLE_ADMIN) || Yii::$app->user->can(\common\models\AuthItem::ROLE_DIRECTOR_COMERCIAL) ) : ?>        
                {
                    field: "Price",
                    title: "<?= Yii::t('app', 'Price') ?>",
                    format: "{0:c}"                    
                },
                {
                    field: "Profit",
                    title: "<?= Yii::t('app', 'Profit') ?>",
                    format:"{0:#.## \\'%'}"
                  
                },
                <?php endif; ?>        
            ],
            dataBound: function () {
                var rows = this.tbody.children();
                var dataItems = this.dataSource.view();
                for (var i = 0; i < dataItems.length; i++) {
                    kendo.bind(rows[i], dataItems[i]);
                }
            }
        });

    }



        function sendProducts()
        {
            var gmidsChekeds = [];
            var tradesChekeds = [];
            var datas = {};


            var gridStep1 = $('#productGrid').data('kendoGrid').dataSource;
            var step1Json = gridStep1.data().toJSON();

            $.each(step1Json, function (i, e)
            {
                if (e.IsActive)
                {
                    if (e.GmidId)
                    {
                        gmidsChekeds.push(e.GmidId);
                    }
                    else
                    {
                        tradesChekeds.push(e.TradeProductId);
                    }
                }
            });

            datas = {gmids: gmidsChekeds, trades: tradesChekeds};

            $.ajax({
                type: "POST",
                url: "<?= \yii\helpers\Url::to(['product/save']) ?>",
                data: datas,
                cache: false,
                success: onSuccess,
            });

            function onSuccess(data)
            {
                $('#confirmModal').modal('show');
            }
    }


</script>