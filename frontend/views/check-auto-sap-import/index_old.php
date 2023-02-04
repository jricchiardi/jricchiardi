<?php

use common\models\sap\ManualImport;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="row">
    <div class="col-md-8">
        <h1>Importaciones automaticas de SAP</h1>
        <a class="btn btn-success" style="margin-bottom: 1rem;"
           href="<?= Url::to(['check-auto-sap-import/run-again']) ?>">
            Reejecutar la importación automática
        </a>
    </div>
    <div class="col-md-4">
        <h1>Realizar una reimportación manual</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <table class="table table-striped table-bordered text-center">
            <tr>
                <th class="text-center">Tipo de importación</th>
                <th class="text-center">Fecha</th>
                <th class="text-center">Archivo</th>
                <th class="text-center">Finalizó correctamente?</th>
            </tr>
            <?php
            foreach ($imports as $key => $value) {
                ?>
                <tr>
                    <td><?= $value['TypeImportName']; ?></td>
                    <td><?= $value['CreatedAt']; ?></td>
                    <td>
                        <a href="<?= Url::to(['check-auto-sap-import/download', 'id' => $value['ImportId']]) ?>">
                            <?= $value['Name']; ?>
                        </a>
                    </td>
                    <td>
                        <?php if ($value['FinishedCorrectly'] === 1 && $value['WithErrors'] === 0): ?>
                            <p class="bg-success text-center">SI</p>
                        <?php elseif ($value['FinishedCorrectly'] === 1 && $value['WithErrors'] === 1): ?>
                            <p class="bg-warning text-center">
                                Si pero con errores -
                                <a href="<?= Url::to(['check-auto-sap-import/errors', 'id' => $value['ImportId']]) ?>">
                                    Ver errores
                                </a>
                            </p>
                        <?php else: ?>
                            <p class="bg-danger text-center">
                                NO -
                                <a href="<?= Url::to(['check-auto-sap-import/errors', 'id' => $value['ImportId']]) ?>">
                                    Ver errores
                                </a>
                            </p>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php }//endforeach?>
        </table>
    </div>

    <div class="col-md-4">
        <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data'],
        ]) ?>
		
		<?= $form->field($manualModelImport, 'tipo')->dropdownList([ManualImport::TIPO_VENTAS => 'Ventas', ManualImport::TIPO_CYOS => 'CyOs', ManualImport::TIPO_OPEN_ORDERS => 'Open Orders', ManualImport::TIPO_FC_NOCONT => 'Facturas No Cont', ManualImport::TIPO_DESP_NOFC => 'Despachado No Fc'], ['prompt' => 'Seleccionar tipo de importación', 'class' => 'mySelectBoxClass']) ?>
		
	   	<?= $form->field($manualModelImport, 'origen')->dropdownList([ManualImport::ORIGEN_DAS => 'DAS', ManualImport::ORIGEN_DUPONT => 'DUPONT', ManualImport::ORIGEN_DELIVORDS => 'OAPD5DELV', ManualImport::ORIGEN_ORDERS_CRED => 'OAPD5CRED', ManualImport::ORIGEN_DUPONT_SHORT => 'FCP2PC1', ManualImport::ORIGEN_DAS_SHORT => 'FCP2PD5'], ['prompt' => 'Seleccionar origen de importación', 'class' => 'mySelectBoxClass'])?>
		

	<?= $form->field($manualModelImport, 'file')->fileInput() ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Import'), ['class' => 'btn btn-primary in-nuevos-reclamos']) ?>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
