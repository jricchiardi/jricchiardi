<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$attributes = (new \common\models\OpenOrders())->attributeLabels();
?>

<div class="container">

    <div class="row">
        <div class="col-12 text-right">
            <p>
				<a href="<?= Url::to(['/open-orders/download']); ?>" class="btn btn-info">Descargar XLSX</a>
            </p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <table class="table table-striped table-bordered text-center">
            <tr>
            <?php
            foreach ($attributes as $code => $name) {
            ?>
                <th class="text-center"><?= $name ?></th>
            <?php }?>
            </tr>
            <?php
            /** @var \common\models\OpenOrders $openOrder */
            foreach ($openOrders as $openOrder) {
                ?>
                <tr>
                <?php
                foreach ($attributes as $code => $name) {
                ?>
                        <td><?= $openOrder->$code; ?></td>
                <?php }?>
                </tr>
            <?php }?>
        </table>
    </div>

</div>
