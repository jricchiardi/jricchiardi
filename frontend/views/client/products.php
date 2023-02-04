<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = Yii::t('app', 'Products of clients');
?>
<div class="client-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'Description',
            'country.Description',
            'clientType.Description',
            'IsGroup:boolean',
        ],
    ]);
    ?>

    <div id="productGrid"></div>
    <?= $this->registerJs('products(); ', $this::POS_READY); ?>

    <div class="form-group">      
        <button type="button"  class="btn btn-primary in-nuevos-reclamos" onClick="save();"><?= Yii::t('app', 'Save'); ?></button>
    </div>  
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
<script>
    var products = function ()
    {

        $("#productGrid").kendoGrid({
            dataSource:
                    {
                        data: <?= json_encode($products) ?>,
                        schema:
                                {
                                    model:
                                            {
                                                fields: {
                                                    IsForecastable: {type: "boolean"},
                                                    ClientProductId: {type: "number"},
                                                    TradeProductId: {type: "number"},
                                                    GmidId: {type: "number"},
                                                    Description: {type: "string"},
                                                    PerformanceCenter: {type: "string"},
                                                    ValueCenter: {type: "string"},
                                                }
                                            }
                                }
                    },
            filterable: true,
            columnMenu: false,
            sortable: true,
            pageable: false,
            scrollable: true,
            height: 600,
            columns: [
                {
                    sortable: false,
                    filterable: false,
                    field: "IsForecastable",
                    title: "<input id='checkAllProduct', type='checkbox', class='check-box' />",
                    template: "<input type='checkbox' data-type='boolean'  data-bind='checked:IsForecastable' id='#=ClientProductId #'   class='product' />",
                    width: 50,
                },
                {
                    field: "ValueCenter",
                    title: "<?= Yii::t('app', 'ValueCenter') ?>"
                },
                {
                    field: "PerformanceCenter",
                    title: "<?= Yii::t('app', 'PerformanceCenter') ?>"
                },                             
                {
                    field: "Description",
                    title: "<?= Yii::t('app', 'Description') ?>"
                },
                
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

    var clientsProducts = [];

    function save()
    {
      
            var gridStep1 = $('#productGrid').data('kendoGrid').dataSource;
            var step1Json = gridStep1.data().toJSON();
            
            $.each(step1Json, function (i, e)
            {
                if (e.IsForecastable)
                {
                     clientsProducts.push(e.ClientProductId);
                }
            });

        var datas = {clientsProducts: clientsProducts};
        
        $.post("<?= \yii\helpers\Url::to(['client/products', 'id' => $model->ClientId]) ?>", datas).done(function (data)
        {
           $('#confirmModal').modal('show');
        });
    }

    $("body").on("click", "#checkAllProduct", function ()
    {
        var grid = $("#productGrid").data("kendoGrid");
        var datas = grid.dataSource.data();
        var flag;
        
        if ($("#checkAllProduct").attr('checked'))
            flag = true;
        else
            flag = false;

        for (var i = 0; i < datas.length; i++)
        {
            datas[i].IsForecastable = flag;
        }
        
        $('.product').each(function ()
        {
            if ($("#checkAllProduct").attr('checked'))
            {
                $(this).attr('checked', 'checked');
            }
            else
            {
                $(this).removeAttr('checked');
            }
        });
    });

</script>








