<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;

$this->title = Yii::t('app', 'Clients');
?>

<div class="client-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $dropDownSeller = new \Kendo\UI\DropDownList('SellerId');
    $dropDownSeller->dataSource($sellers)
            ->filter("startswith")
            ->autoBind(false)
            ->dataTextField('Fullname')
            ->dataValueField('UserId')
            ->optionLabel(Yii::t('app','Select Sellers'))
            ->attr('style', 'width:100%')
            ->change('cleanGrid');

    $transport = new \Kendo\Data\DataSourceTransport();
    $transport->read(['url' => \yii\helpers\Url::to('client-by-seller'), 'type' => 'GET']);

    $dropDownClient = new \Kendo\UI\DropDownList('ClientId');
    $dropDownClient->dataSource(array('transport' => $transport, 'serverFiltering' => true))
            ->autoBind(false)
            ->cascadeFrom('SellerId')
            ->dataTextField('Description')
            ->dataValueField('ClientId')
            ->change('refreshGrid')
            ->attr('style', 'width: 300px')
            ->optionLabel(Yii::t('app','Select Client'));
    ?>   

    <div class="row">
        <div class="col-xs-13 col-sm-13 col-md-3 col-lg-3 pull-left">
            <?php echo '<span>'.Yii::t('app','Sellers') .'</span>' . $dropDownSeller->render(); ?>             
        </div>       
        <div class="col-xs-13 col-sm-13 col-md-3 col-lg-3  pull-left">
            <?php echo '<span>'.Yii::t('app','Clients as a group') .'</span>' . $dropDownClient->render(); ?> 
        </div>
    </div>
    <br/>


    <div id="clientGrid"></div>
    <div class="form-group">      
        <?= Html::submitButton(Yii::t('app', 'Save'), ['id' => 'confirmButton', 'class' => 'btn btn-primary in-nuevos-reclamos']) ?>
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

    <?= $this->registerJs('clients(); ', $this::POS_READY); ?>


    <script>

        var clients = function () {

            $("#clientGrid").kendoGrid({
                dataSource:
                        {
                            transport: {
                                read: {
                                    url: "<?= \yii\helpers\Url::to('client-by-seller') ?>",
                                }
                            },
                            schema:
                                    {
                                        model:
                                                {
                                                    fields: {
                                                        IsActive: {type: "boolean"},
                                                        ClientId: {type: "number"},
                                                        GroupId: {type: "number"},
                                                        Description: {type: "string"},
                                                        Country: {type: "string"},
                                                        Type: {type: "string"}
                                                    }
                                                }
                                    }
                        },
                filterable: true,
                columnMenu: true,
                sortable: true,
                height: 400,
                scrollable: true,
                columns: [
                    {
                        sortable: false,
                        filterable: false,
                        field: "IsActive",
                        title: "<input id='checkAll', type='checkbox', class='check-box' />",
                        template: "<input type='checkbox' data-type='boolean'  data-bind='checked:IsActive' id='#=ClientId #'   class='client' />",
                        width: 110,
                    },
                    {
                        field: "ClientId",
                        title: "<?= Yii::t('app', 'Code') ?>"
                    },
                    {
                        field: "Description",
                        title: "<?= Yii::t('app', 'Client') ?>"
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

        var clientsChekeds = [];




        $("#confirmButton").click(function ()
        {
            countClients();
            var datas = {clients: clientsChekeds, SellerId: $("#SellerId").val(), ClientId: $("#ClientId").val()};

            $.post("<?= \yii\helpers\Url::to(['client/group']) ?>", datas).done(function (data)
            {
                clients();
                $('#confirmModal').modal('show');
                cleanGrid();
                $("#SellerId").data("kendoDropDownList").value("");
            });
        });


        function countClients()
        {
            clientsChekeds = [];
            $('.client').each(function ()
            {
                if (this.checked)
                {
                    clientsChekeds.push(this.id);
                }
            });

            $("#totalClient").html(clientsChekeds.length);
            $('#confirmModal').modal('show');
        }

        $("body").on("click", "#checkAll", function ()
        {
            var grid = $("#clientGrid").data("kendoGrid");
            var datas = grid.dataSource.data();
            var flag;

            if ($("#checkAll").attr('checked'))
                flag = true;
            else
                flag = false;

            for (var i = 0; i < datas.length; i++)
            {
                datas[i].IsActive = flag;
            }

            $('.client').each(function ()
            {
                if ($("#checkAll").attr('checked'))
                {
                    $(this).attr('checked', 'checked');
                }
                else
                {
                    $(this).removeAttr('checked');
                }
            });
        });

        function cleanGrid()
        {
            $("#clientGrid").data('kendoGrid').dataSource.data([]);
        }
        function refreshGrid()
        {
            var url = "<?= \yii\helpers\Url::to('client-by-seller') ?>?SellerId=" + $("#SellerId").val() + "&&ParentId=" + $("#ClientId").val();
            $('#clientGrid').data('kendoGrid').dataSource.options.transport.read.url = url;
            $('#clientGrid').data('kendoGrid').dataSource.read();
        }

    </script>


</div>

