<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;

$this->title = Yii::t('app', 'Product assignment');
?>

<div class="client-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div id="clientGrid"></div>


<?= $this->registerJs('clients(); ', $this::POS_READY); ?>


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
                                                                ClientId: {type: "number"},
                                                                GroupId: {type: "number"},
                                                                Description: {type: "string"},
                                                                Country: {type: "string"},
                                                                Type: {type: "string"},
                                                            }
                                                        }
                                            }
                                },
                        filterable: true,
                        columnMenu: true,
                        sortable: true,
                        height: 600,
                        scrollable: true,
                        columns: [
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
                            {
                                field: "GroupId",
                                template: '#=GroupId ? "<?=Yii::t("app","Others") ?>" : "<?=Yii::t("app","No") ?>"  #',
                                title: "<?= Yii::t('app', 'It belongs to the group') ?>"
                            },
                            {
                                template: '<a href="<?= \yii\helpers\Url::to(['client/products']) ?>?id=#=ClientId#"><div class="k-button"><span class="k-icon k-i-custom"></span> <?=Yii::t("app","Assigned products") ?></div></a>',
                            },
                        ],
                    });

                }
            });
        }

    </script>


</div>
</div>
