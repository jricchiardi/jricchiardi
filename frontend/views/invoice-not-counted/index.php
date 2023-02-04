<?php
use yii\helpers\Url;
$attributes = (new \common\models\InvoiceNotCounted())->attributeLabels();
?>

<div class="container">

    <div class="row">
        <div class="col-12 text-right">
            <p>
                <a href="<?= Url::to(['/invoice-not-counted/download']); ?>" class="btn btn-info">Descargar XLSX</a>
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
            /** @var \common\models\InvoiceNotCounted $invoices */
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
