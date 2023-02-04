<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$attributes = (new \common\models\DespNoFc())->attributeLabels();
?>

<div class="container">

    <div class="row">
        <div class="col-12 text-right">
            <p>
                <a href="<?= Url::to(['/desp-no-fc/download']); ?>" class="btn btn-info">Descargar XLSX</a>
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
            /** @var \common\models\DespNoFc $invoice */
            foreach ($invoices as $invoice) {
                ?>
                <tr>
                <?php
                foreach ($attributes as $code => $name) {
                ?>
                        <td><?= $invoice->$code; ?></td>
                <?php }?>
                </tr>
            <?php }?>
        </table>
    </div>

</div>
