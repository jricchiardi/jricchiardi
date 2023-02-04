<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;

$this->title = Yii::t('app', 'Clients');
?>

<div class="client-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div id="clientGrid"></div>

    <?php
    Modal::begin([
        'id' => 'confirmModal',
        'header' => '<h2>'.Yii::t('app','Confirm Actions').'</h2>',
    ]);
    ?>   
    <center><h4> <?= Yii::t('app', '¿Do you want to confirm the changes on the'); ?> <span id="totalClient"></span> <?= Yii::t('app', 'Clients') ?> ? </h4>   </center>
    <button type="button" id="confirmButton"  class="btn btn-primary in-nuevos-reclamos"><?= Yii::t('app', 'Confirm Changes'); ?></button>                        

    <?php
    Modal::end();

    $this->registerJs('clients(); ', $this::POS_READY);
    ?>


    <script>

        var clients = function () {

            $.ajax({
                url: "<?= yii\helpers\Url::to(['client/list']) ?>",
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
                                                                NameGroup: {type: "string"}
                                                            }
                                                        }
                                            }
                                },                        
                        filterable: true,
                        columnMenu: true,
                        sortable: true,                
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
                                template: '#=GroupId ? NameGroup : "No"  #',
                                title: "<?= Yii::t('app', 'It belongs to group') ?>"
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

       
    </script>


</div>

