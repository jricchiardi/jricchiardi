<?php

use Kendo\Data\DataSource;
use Kendo\Data\DataSourceSchema;
use Kendo\Data\DataSourceSchemaModel;
use Kendo\Data\DataSourceSchemaModelField;
use Kendo\UI\Grid;
use Kendo\UI\GridColumn;
use yii\helpers\Url;

?>
<div class="container full-width">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <h1 class="big-title">Errores de importación automática de SAP</h1>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <h4>Tipo de importación: <?= $typeImportDescription ?></h4>
            <h4>Fecha: <?= $import->CreatedAt ?></h4>
            <h4>Archivo: <a
                        href="<?= Url::to(['check-auto-sap-import/download', 'id' => $import->ImportId]) ?>"><?= $import->Name ?></a>
            </h4>
            <a href="<?= Url::to(['check-auto-sap-import/index']) ?>">Volver</a>
        </div>
    </div>
    <?php
    $rowNumberField = (new DataSourceSchemaModelField('rowNumber'))->type('int');
    $gmidField = (new DataSourceSchemaModelField('gmid'))->type('string');
    $gmidDescriptionField = (new DataSourceSchemaModelField('gmidDescription'))->type('string');
    $clientField = (new DataSourceSchemaModelField('client'))->type('string');
    $clientNameField = (new DataSourceSchemaModelField('clientName'))->type('string');
    $countryField = (new DataSourceSchemaModelField('country'))->type('string');
    $descriptionField = (new DataSourceSchemaModelField('description'))->type('string');

    $model = (new DataSourceSchemaModel())
        ->addField($rowNumberField)
        ->addField($gmidField)
        ->addField($gmidDescriptionField)
        ->addField($clientField)
        ->addField($clientNameField)
        ->addField($countryField)
        ->addField($descriptionField);

    $schema = (new DataSourceSchema())->model($model);

    $dataSource = (new DataSource())
        ->schema($schema)
        ->data($errors);

    $rowNumberColumn = (new GridColumn())->field('rowNumber')->title(Yii::t('app', 'Fila'))->filterable(false);
    $gmidColumn = (new GridColumn())->field('gmid')->title(Yii::t('app', 'GMID'));
    $gmidDescriptionColumn = (new GridColumn())->field('gmidDescription')->title(Yii::t('app', 'GMID Descripción'));
    $clientColumn = (new GridColumn())->field('client')->title(Yii::t('app', 'Client'));
    $clientNameColumn = (new GridColumn())->field('clientName')->title(Yii::t('app', 'Nombre del Cliente'));
    $countryColumn = (new GridColumn())->field('country')->title(Yii::t('app', 'País'));
    $descriptionColumn = (new GridColumn())->field('description')->title(Yii::t('app', 'Error'));

    $grid = new Grid('grid');

    $grid->addColumn(
            $rowNumberColumn,
            $clientColumn,
            $clientNameColumn,
            $gmidColumn,
            $gmidDescriptionColumn,
            $countryColumn,
            $descriptionColumn)
        ->scrollable(true)
        ->pageable(false)
        ->dataSource($dataSource)
        ->height(600)
        ->sortable(true)
        ->filterable(true);
    ?>

    <div class="row" id="imported">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <?= $grid->render(); ?>
            </div>
        </div>
    </div>
</div>
