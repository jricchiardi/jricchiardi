<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

     <?php if (Yii::$app->user->can(\common\models\AuthItem::ROLE_ADMIN)) : ?>
    <p>
        <?= Html::a(Yii::t('app', 'Create User'), ['signup'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>
    <div id="userGrid"> </div>
    <?php $this->registerJs('users();', $this::POS_READY); ?>

    <script>

        var users = function ()
        {
            $("#userGrid").kendoGrid({
                dataSource:
                        {
                            data: <?= json_encode($users) ?>,
                            pageSize: 20,
                            schema:
                                    {
                                        model:
                                                {
                                                    fields: {
                                                        DowUserId: {type: "string"},
                                                        Username: {type: "string"},
                                                        Fullname: {type: "string"},
                                                        Email: {type: "string"},
                                                        Type: {type: "string"},
                                                        Father: {type: "string"},
                                                    }
                                                }
                                    }
                        },
                filterable: true,
                columnMenu: true,
                sortable: true,
                pageable: true,
                scrollable: true,
                height: 600,
                columns: [
                    {
                        field: "DowUserId",
                        title: "<?= Yii::t('app', 'DowUserId') ?>"
                    },
                    {
                        field: "Username",
                        title: "<?= Yii::t('app', 'Username') ?>"
                    },
                    {
                        field: "Fullname",
                        title: "<?= Yii::t('app', 'Fullname') ?>"
                    },
                    {
                        field: "Email",
                        title: "<?= Yii::t('app', 'Email') ?>"
                    },
                    {
                        field: "Type",
                        title: "<?= Yii::t('app', 'Type') ?>"
                    },
                    {
                        field: "Father",
                        title: "<?= Yii::t('app', 'Father') ?>"
                    },         
                ],
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
</div>
