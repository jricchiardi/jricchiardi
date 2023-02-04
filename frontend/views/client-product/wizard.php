<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = Yii::t('app', 'Setup Products To Clients');
?>


<h1><?= Html::encode($this->title) ?></h1>

<div id="tabstrip">
    <ul>
        <li class="k-state-active"><?= Yii::t('app', 'Step 1 (Selection of Clientes)'); ?></li>
        <li><?= Yii::t('app', 'Step 2 (Selection of Products)'); ?></li>        
        <li><?= Yii::t('app', 'Step 3 (Confirmation)'); ?></li>
    </ul>    
    <div><div id="clientGrid"> </div></div>
    <div><div id="productGrid"> </div></div>
    <div>
        <h1><?= Yii::t('app', 'Amount of selected products :'); ?> <span id="totalProduct"></span> </h1>
        <h1><?= Yii::t('app', 'Amount of selected clients:'); ?>  <span id="totalClient"></span> </h1>
        <button type="button" id="import" class="btn btn-primary in-nuevos-reclamos"  onClick="confirm();"  data-dismiss="modal"><?= Yii::t('app', 'Confirm'); ?></button>                        

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

<?php $this->registerJs('wizard(); clients(); products();', $this::POS_READY); ?>

<script>
    var clients = function () {


        $.ajax({
            url: "<?= yii\helpers\Url::to(['client-product/list-client']) ?>",
            type: 'GET',
            success: function (response)
            {
                $("#clientGrid").kendoGrid({
                    dataSource:
                            {
                                data: response,
                                schema:
                                        {
                                            model:
                                                    {
                                                        fields: {
                                                            IsActive: {type: "boolean"},
                                                            ClientId: {type: "number"},
                                                            Description: {type: "string"},
                                                            Country: {type: "string"},
                                                            Type: {type: "string"},
                                                        }
                                                    }
                                        }
                            },
                    filterable: true,
                    columnMenu: true,
                    sortable: false,
                    pageable: false,
                    scrollable: true,
                    height: 600,
                    columns: [
                        {
                            sortable: false,
                            filterable: false,
                            field: "IsActive",
                            title: "<input id='checkAllClient', type='checkbox', class='check-box' />",
                            template: "<input type='checkbox' data-type='boolean'  data-bind='checked:IsActive' id='#=ClientId #'   class='client' />",
                            width: 110,
                        },
                        {
                            field: "Description",
                            title: "<?= Yii::t('app', 'Client') ?>"
                        },
                        {
                            field: "Country",
                            title: "<?= Yii::t('app', 'Country') ?>"
                        },
                        {
                            field: "Type",
                            title: "<?= Yii::t('app', 'Type') ?>"
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
        });

    }


    var products = function ()
    {
        $.ajax({
            url: "<?= yii\helpers\Url::to(['client-product/list-product']) ?>",
            type: 'GET',
            success: function (response)
            {

                $("#productGrid").kendoGrid({
                    dataSource:
                            {
                                data: response,
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
                        {
                            sortable: false,
                            filterable: false,
                            field: "IsActive",
                            title: "<input id='checkAllProduct', type='checkbox', class='check-box' />",
                            template: "<input type='checkbox' data-type='boolean'  data-bind='checked:IsActive'  id='#=(GmidId == null) ? 't'+TradeProductId : 'g'+GmidId #' class='product' />",
                            width: 110,
                        },
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
                        }
                        
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
        });
    }

    var wizard = function ()
    {
        $("#tabstrip").kendoTabStrip({
            select: calculatePreview,
            animation: {
                close: {
                    effects: "fadeOut"
                }
            }
        });
    }




    var clientsChekeds = [];
    var gmidsChekeds = [];
    var tradesChekeds = [];
    function calculatePreview()
    {
        clientsChekeds = [];
        gmidsChekeds = [];
        tradesChekeds = [];

        $('.client').each(function ()
        {
            if (this.checked)
            {
                clientsChekeds.push(this.id);
            }
        });

        $('.product').each(function ()
        {
            if (this.checked)
            {
                if (this.id.indexOf("g") >= 0)
                {
                    this.id = this.id.replace("g", "");
                    gmidsChekeds.push(this.id);
                }
                else
                {
                    this.id = this.id.replace("t", "");
                    tradesChekeds.push(this.id);
                }
            }
        });
        $("#totalProduct").html(gmidsChekeds.length + tradesChekeds.length);
        $("#totalClient").html(clientsChekeds.length);
    }

    function confirm()
    {
        var datas = {clients: clientsChekeds, gmids: gmidsChekeds, trades: tradesChekeds};

        if ((gmidsChekeds.length > 0 || tradesChekeds.length > 0) && clientsChekeds.length > 0)
        {

            $.ajax({
                type: "POST",
                url: "<?= \yii\helpers\Url::to(['client-product/setup-product-client']) ?>",
                data: datas,
                cache: false,
                success: onSuccess,
            });

            function onSuccess(data)
            {
                $('#confirmModal').modal('show');
            }
        }
        else
        {
            alert("<?= Yii::t('app', 'Must necessarily select Products and Customers') ?>");
        }
    }

    $("body").on("click", "#checkAllClient", function ()
    {

        var grid = $("#clientGrid").data("kendoGrid");
        var datas = grid.dataSource.data();
        var flag;

        if ($("#checkAllClient").attr('checked'))
            flag = true;
        else
            flag = false;

        for (var i = 0; i < datas.length; i++)
        {
            datas[i].IsActive = flag;
        }

        $('.client').each(function ()
        {
            if ($("#checkAllClient").attr('checked'))
            {
                $(this).attr('checked', 'checked');
            }
            else
            {
                $(this).removeAttr('checked');
            }
        });
    });


    $("body").on("click", "#checkAllProduct", function ()
    {
        var flag;
        var grid = $("#productGrid").data("kendoGrid");
        var datas = grid.dataSource.data();
        if ($("#checkAllProduct").attr('checked'))
            flag = true;
        else
            flag = false;

        for (var i = 0; i < datas.length; i++)
        {
            datas[i].IsActive = flag;
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