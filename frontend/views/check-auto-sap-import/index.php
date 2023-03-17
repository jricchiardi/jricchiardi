<?php

use common\models\sap\ManualImport;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="row">
    <div class="col-md-8">
        <h1>Automatic imports from SAP</h1>
        <a class="btn btn-success" style="margin-bottom: 1rem;"
           href="<?= Url::to(['check-auto-sap-import/run-again']) ?>">
           Run automatic import
        </a>
        <?php if (Yii::$app->user->can(\common\models\AuthItem::ROLE_SIS_ADMIN)){ ?>
		<a class="btn btn-primary" style="margin-bottom: 1rem;"
           href="<?= Url::to(['check-auto-sap-import/run-vaciado-oa']) ?>">
           Clear OA Table
        </a>
		
		<a class="btn btn-warning" style="margin-bottom: 1rem;"
           href="<?= Url::to(['check-auto-sap-import/run-vaciado-fc-no-cont']) ?>">
            Clear FC NO CONT Table
        </a>
		
		<a class="btn btn-danger" style="margin-bottom: 1rem;"
           href="<?= Url::to(['check-auto-sap-import/run-vaciado-desp-no-fc']) ?>">
            Clear DESP NO FC Table
        </a>		
		<?php } ?>
    </div>
    <div class="col-md-4">
        <h1>Perform a manual reimport</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <table class="table table-striped table-bordered text-center">
            <tr>
                <th class="text-center">import type</th>
                <th class="text-center">Date</th>
                <th class="text-center">File</th>
                <th class="text-center">Did it finish successfully?</th>
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
                            yes but with errors -
                                <a href="<?= Url::to(['check-auto-sap-import/errors', 'id' => $value['ImportId']]) ?>">
                                see errors
                                </a>
                            </p>
                        <?php else: ?>
                            <p class="bg-danger text-center">
                                NO -
                                <a href="<?= Url::to(['check-auto-sap-import/errors', 'id' => $value['ImportId']]) ?>">
                                see errors
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
		
        <?php
        $typeOptions = [
            ManualImport::TIPO_VENTAS => 'Ventas',
            ManualImport::TIPO_CYOS => 'CyOs',
        ];
        if (Yii::$app->user->can(\common\models\AuthItem::ROLE_SIS_ADMIN)){
            $typeOptions = [
                ManualImport::TIPO_OPEN_ORDERS => 'Open Orders',
                ManualImport::TIPO_FC_NOCONT => 'Facturas No Cont',
                ManualImport::TIPO_DESP_NOFC => 'Despachado No Fc',
                ManualImport::TIPO_FCASTIBP => 'Forecast IBP',
            ];
        }
        ?>
		<?= $form->field($manualModelImport, 'tipo')->dropdownList($typeOptions, ['prompt' => 'Select import type', 'class' => 'mySelectBoxClass']) ?>

        <?php
        $originOptions = [
            ManualImport::ORIGEN_DAS => 'DAS',
            ManualImport::ORIGEN_DUPONT => 'DUPONT',
        ];
        if (Yii::$app->user->can(\common\models\AuthItem::ROLE_SIS_ADMIN)){
            $originOptions = [
                ManualImport::ORIGEN_DAS => 'DAS',
                ManualImport::ORIGEN_DUPONT => 'DUPONT',
                ManualImport::ORIGEN_DELIVORDS => 'OAPD5DELV',
                ManualImport::ORIGEN_ORDERS_CRED => 'OAPD5CRED',
                ManualImport::ORIGEN_DUPONT_SHORT => 'FCP2PC1',
                ManualImport::ORIGEN_DAS_SHORT => 'FCP2PD5',
                ManualImport::ORIGEN_FCASTIBP => 'Forecast IBP',
            ];
        }
        ?>
		<?= $form->field($manualModelImport, 'origen')->dropdownList($originOptions, ['prompt' => 'Select import source', 'class' => 'mySelectBoxClass'])?>
	<?= $form->field($manualModelImport, 'file')->fileInput() ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Import'), ['class' => 'btn btn-primary in-nuevos-reclamos']) ?>
        </div>
		
	<?php ActiveForm::end() ?>
    </div>
</div>
