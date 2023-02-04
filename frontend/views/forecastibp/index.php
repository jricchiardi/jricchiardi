<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$attributes = (new \common\models\FcastIBP())->attributeLabels();
?>


<div class="container">

    <div class="row">
        <div class="col-12 text-right">
            <p>
                <a href="<?= Url::to(['/forecastibp/download']);?>" class="btn btn-info">Descargar XLSX</a>
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
            /** @var \common\models\FcastIBP $FcastIBPs */
            foreach ($ForecastIBP as $FcastIBP) {
                ?>
                <tr>
                <?php
                foreach ($attributes as $code => $name) {
                ?>
                        <td><?= $FcastIBP->$code; ?></td>
                <?php }?>
                </tr>
            <?php }?>
        </table>
    </div>

</div>
