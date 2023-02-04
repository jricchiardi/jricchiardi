<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Asociar DSMs"
?>

<h1><?= Html::encode($this->title) ?></h1>
<div id="dsmGrid"></div>
<div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Save'), ['id' => 'send', 'class' => 'btn btn-primary in-nuevos-reclamos']) ?>
</div>

<div class="modal fade" id="confirmModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t("app", "Result") ?></h4>
            </div>
            <div class="modal-body">
                <p><?= Yii::t("app", "The information was saved correctly!"); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal"><?= Yii::t("app", "Close") ?></button>
            </div>
        </div>
    </div>
</div>

<?php $this->registerJs('dsm();', $this::POS_READY); ?>

<script>
    $("#send").click(function () {
        sendDsm();
    });

    var dsm = function () {
        kendo.culture("en-US");

        $("#dsmGrid").kendoGrid({
            dataSource:
                {
                    data: <?= json_encode($dsm) ?>,
                    schema:
                        {
                            model:
                                {
                                    fields: {
                                        DsmId: {type: "number"},
                                        Fullname: {type: "string"},
                                        IsRelated: {type: "boolean"},
                                        Country: {type: "string"}
                                    }
                                }
                        }
                },
            filterable: {
                mode: "row"
            },
            columnMenu: true,
            sortable: true,
            pageable: false,
            scrollable: true,
            height: 400,
            columns: [
                {
                    field: "IsRelated",
                    title: "Asociado",
                    filterable: {
                        cell: {
                            template: function (container) {
                                container.element.kendoDropDownList({
                                    dataSource: {
                                        data: [
                                            {text: "Si", value: true},
                                            {text: "No", value: false}
                                        ]
                                    },
                                    dataTextField: "text",
                                    dataValueField: "value",
                                    valuePrimitive: true,
                                    optionLabel: "Todos"
                                });
                            },
                            showOperators: false
                        }
                    },
                    sortable: false,
                    template: "<input type='checkbox' data-type='boolean' data-bind='checked:IsRelated' id='#=DsmId#' class='product' />",
                    width: "150px",
                },
                {
                    field: "Country",
                    title: "Pais",
                    filterable: {
                        cell: {
                            template: function (container) {
                                container.element.kendoDropDownList({
                                    dataSource: container.dataSource,
                                    dataTextField: "Country",
                                    dataValueField: "Country",
                                    valuePrimitive: true,
                                    optionLabel: "Todos"
                                });
                            },
                            showOperators: false
                        }
                    }
                },
                {
                    field: "Fullname",
                    title: "Nombre DSM",
                    filterable: {
                        cell: {
                            template: function (container) {
                                container.element.kendoAutoComplete({
                                    filter: "contains",
                                    dataTextField: "Fullname",
                                    valuePrimitive: true,
                                    dataSource: container.dataSource,
                                    placeholder: "Busque DSM...",
                                });
                            },
                            showOperators: false
                        }
                    }
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
    };

    function sendDsm() {
        var data = $('#dsmGrid').data('kendoGrid').dataSource.data().toJSON();

        var dsmIds = data.map(item => {
            var isRelated = item.IsRelated ? 1 : 0;
            return {"dsmId": item.DsmId, isRelated}
        });

        $.ajax({
            type: "POST",
            url: "<?= Url::to(['pm-dsm/set-dsm-ids']) ?>",
            data: {dsmIds},
            cache: false,
            success: onSuccess,
        });

        function onSuccess(data) {
            $('#confirmModal').modal('show');
        }
    }
</script>
