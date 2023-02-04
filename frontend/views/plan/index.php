<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;

$this->title = Yii::t('app', 'Plan');

$dataResume = array(
    array('Concepto' => 'Plan',
        'ActualMonth' => 0,
        'Q1' => 0,
        'Q2' => 0,
        'Q3' => 0,
        'Q4' => 0,
        'Total' => 0,
    ),  
);


// ******************** DEFINE validations *******************************

$requiredValidationString = array('required' => array(true, 'message' => Yii::t('app','Required')));
$requiredValidationNumber = array('required' => array(true, 'message' =>Yii::t('app','Required')));

//********************* DEFINE the columns and models ********************

/* COLUMN CLIENTPRODUCTID */
$clientProductIdField = new \Kendo\Data\DataSourceSchemaModelField('ClientProductId');
$clientProductIdField->type('number')->validation($requiredValidationNumber);
$clientProductIdField->editable(false);

/* COLUMN CAMPAIGNID */
$campaignIdField = new \Kendo\Data\DataSourceSchemaModelField('CampaignId');
$campaignIdField->type('number')->validation($requiredValidationNumber);
$campaignIdField->editable(false);


/* COLUMN GMID */
$codeField = new \Kendo\Data\DataSourceSchemaModelField('GmidId');
$codeField->type('number')->validation($requiredValidationNumber);
$codeField->editable(false);
$codeColumn = new \Kendo\UI\GridColumn();
$codeColumn->field('GmidId');
$codeColumn->title('GMID');
$codeColumn->hidden(true);
$codeColumn->editor("function (e) {                              
            $('#plan').data('kendoGrid').closeCell();}
        ");
$codeColumn->width(80);


/* COLUMN TRADEPRODUCTID */
$tradeProductIdField = new \Kendo\Data\DataSourceSchemaModelField('TradeProductId');
$tradeProductIdField->type('number')->validation($requiredValidationNumber);
$tradeProductIdField->editable(false);
$tradeProductIdColumn = new \Kendo\UI\GridColumn();
$tradeProductIdColumn->field('TradeProductId');
$tradeProductIdColumn->title('TradeProduct');
$tradeProductIdColumn->hidden(true);
$tradeProductIdColumn->editor("function (e) {                              
            $('#plan').data('kendoGrid').closeCell();}
        ");
$tradeProductIdColumn->width(80);


/* COLUMN PRICE */
$priceField = new \Kendo\Data\DataSourceSchemaModelField('PlanPrice');
$priceField->type('number')->validation($requiredValidationNumber);
$priceField->editable(false);
$priceColumn = new \Kendo\UI\GridColumn();
$priceColumn->field('PlanPrice');
$priceColumn->hidden(TRUE);
$priceColumn->title('Precio');
$priceColumn->editor("function (e) {                              
            $('#plan').data('kendoGrid').closeCell();}
        ");
$priceColumn->width(80);

/* COLUMN MARGEN */
$margenField = new \Kendo\Data\DataSourceSchemaModelField('TradeProductProfit');
$margenField->type('number')->validation($requiredValidationNumber);
$margenField->editable(false);
$margenColumn = new \Kendo\UI\GridColumn();
$margenColumn->field('TradeProductProfit');
$margenColumn->hidden(TRUE);
$margenColumn->title('Margen');
$margenColumn->editor("function (e) {                              
            $('#plan').data('kendoGrid').closeCell();}
        ");
$margenColumn->width(80);

/* COLUMN PRODUCTO */
$productField = new \Kendo\Data\DataSourceSchemaModelField('PlanDescription');
$productField->type('string')
        ->editable(false)
        ->nullable(true);
$productColumn = new \Kendo\UI\GridColumn();
$productColumn->field('PlanDescription');
$productColumn->title('Producto');
$productColumn->width(200);
$productColumn->lockable(false);
$productColumn->locked(true);

$productColumn->editor("function (e) {                              
            $('#plan').data('kendoGrid').closeCell();}
        ");


/* COLUMN ENE */
$eneField = new \Kendo\Data\DataSourceSchemaModelField('January');
$eneField->nullable(TRUE);
$eneField->editable(TRUE);
$eneField->type('number');
$eneColumn = new \Kendo\UI\GridColumn();
$eneColumn->field('January');
$eneColumn->title('Ene');
$eneColumn->format('{0:n0}');
$eneColumn->width(80);


/* COLUMN FEB */

$febField = new \Kendo\Data\DataSourceSchemaModelField('February');
$febField->nullable(TRUE);
$febField->editable(TRUE);
$febField->type('number');

$febColumn = new \Kendo\UI\GridColumn();
$febColumn->title('Feb');
$febColumn->format('{0:n0}');
$febColumn->field('February');
$febColumn->width(80);

/* COLUMN MAR */
$marField = new \Kendo\Data\DataSourceSchemaModelField('March');
$marField->nullable(TRUE);
$marField->editable(TRUE);
$marField->type('number');
$marColumn = new \Kendo\UI\GridColumn();
$marColumn->field('March');
$marColumn->title('Mar');
$marColumn->format('{0:n0}');
$marColumn->width(80);


/* COLUMN Q1 */
$q1Field = new \Kendo\Data\DataSourceSchemaModelField('Q1');
$q1Field->nullable(TRUE);
$q1Field->editable(TRUE);
$q1Field->type('number')
        ->validation($requiredValidationNumber);
$q1Column = new \Kendo\UI\GridColumn();
$q1Column->field('Q1');
$q1Column->title('Q1');
$q1Column->format('{0:n0}');
$q1Column->attributes(['style' => 'background-color:\#A0C79B;color:white;font-weight:700']);
$q1Column->editor("function (e) {                              
            $('#plan').data('kendoGrid').closeCell();}
        ");
$q1Column->width(80);

/* COLUMN Abr */
$abrField = new \Kendo\Data\DataSourceSchemaModelField('April');
$abrField->nullable(TRUE);
$abrField->editable(TRUE);
$abrField->type('number');
$abrColumn = new \Kendo\UI\GridColumn();
$abrColumn->field('April');
$abrColumn->title('Abr');
$abrColumn->format('{0:n0}');
$abrColumn->width(80);

/* COLUMN MAY */

$mayField = new \Kendo\Data\DataSourceSchemaModelField('May');
$mayField->nullable(TRUE);
$mayField->editable(TRUE);
$mayField->type('number');
$mayColumn = new \Kendo\UI\GridColumn();
$mayColumn->field('May');
$mayColumn->title('May');
$mayColumn->format('{0:n0}');
$mayColumn->width(80);

/* COLUMN Jun */
$junField = new \Kendo\Data\DataSourceSchemaModelField('June');
$junField->nullable(TRUE);
$junField->editable(TRUE);
$junField->type('number');
$junColumn = new \Kendo\UI\GridColumn();
$junColumn->field('June');
$junColumn->title('Jun');
$junColumn->format('{0:n0}');
$junColumn->width(80);

/* COLUMN Q2 */

$q2Field = new \Kendo\Data\DataSourceSchemaModelField('Q2');
$q2Field->nullable(TRUE);
$q2Field->editable(TRUE);
$q2Field->type('number')
        ->validation($requiredValidationNumber);
$q2Column = new \Kendo\UI\GridColumn();
$q2Column->field('Q2');
$q2Column->title('Q2');
$q2Column->format('{0:n0}');
$q2Column->attributes(['style' => 'background-color:\#A0C79B;color:white;font-weight:700']);
$q2Column->editor("function (e) {                              
            $('#plan').data('kendoGrid').closeCell();}
        ");
$q2Column->width(80);

/* COLUMN JUL */

$julField = new \Kendo\Data\DataSourceSchemaModelField('July');
$julField->nullable(TRUE);
$julField->editable(TRUE);
$julField->type('number');
$julColumn = new \Kendo\UI\GridColumn();
$julColumn->field('July');
$julColumn->title('Jul');
$julColumn->format('{0:n0}');
$julColumn->width(80);


/* COLUMN Ago */
$agoField = new \Kendo\Data\DataSourceSchemaModelField('August');
$agoField->nullable(TRUE);
$agoField->editable(TRUE);
$agoField->type('number');
$agoColumn = new \Kendo\UI\GridColumn();
$agoColumn->field('August');
$agoColumn->title('Ago');
$agoColumn->format('{0:n0}');
$agoColumn->width(80);


/* COLUMN SEP */
$sepField = new \Kendo\Data\DataSourceSchemaModelField('September');
$sepField->nullable(TRUE);
$sepField->editable(TRUE);
$sepField->type('number');
$sepColumn = new \Kendo\UI\GridColumn();
$sepColumn->field('September');
$sepColumn->title('Sep');
$sepColumn->format('{0:n0}');
$sepColumn->width(80);

/* COLUMN Q3 */
$q3Field = new \Kendo\Data\DataSourceSchemaModelField('Q3');
$q3Field->nullable(TRUE);
$q3Field->editable(TRUE);
$q3Field->type('number');
$q3Field->validation($requiredValidationNumber);
$q3Column = new \Kendo\UI\GridColumn();
$q3Column->field('Q3');
$q3Column->title('Q3');
$q3Column->format('{0:n0}');
$q3Column->attributes(['style' => 'background-color:\#A0C79B;color:white;font-weight:700']);
$q3Column->editor("function (e) {                              
            $('#plan').data('kendoGrid').closeCell();}
        ");
$q3Column->width(80);

/* COLUMN OCT */
$octField = new \Kendo\Data\DataSourceSchemaModelField('October');
$octField->nullable(TRUE);
$octField->editable(TRUE);
$octField->type('number');
$octColumn = new \Kendo\UI\GridColumn();
$octColumn->field('October');
$octColumn->title('Oct');
$octColumn->format('{0:n0}');
$octColumn->width(80);

/* COLUMN NOV */
$novField = new \Kendo\Data\DataSourceSchemaModelField('November');
$novField->nullable(TRUE);
$novField->editable(TRUE);
$novField->type('number');
$novColumn = new \Kendo\UI\GridColumn();
$novColumn->field('November');
$novColumn->title('Nov');
$novColumn->format('{0:n0}');
$novColumn->width(80);

/* COLUMN DIC */
$dicField = new \Kendo\Data\DataSourceSchemaModelField('December');
$dicField->nullable(TRUE);
$dicField->editable(TRUE);
$dicField->type('number');
$dicColumn = new \Kendo\UI\GridColumn();
$dicColumn->field('December');
$dicColumn->title('Dic');
$dicColumn->format('{0:n0}');
$dicColumn->width(80);


/* COLUMN Q4 */
$q4Field = new \Kendo\Data\DataSourceSchemaModelField('Q4');
$q4Field->nullable(TRUE);
$q4Field->editable(TRUE);
$q4Field->type('number')
        ->editable(TRUE)
        ->validation($requiredValidationNumber);
$q4Column = new \Kendo\UI\GridColumn();
$q4Column->field('Q4');
$q4Column->title('Q4');
$q4Column->format('{0:n0}');
$q4Column->attributes(['style' => 'background-color:\#A0C79B;color:white;font-weight:700']);
$q4Column->editor("function (e) {                              
            $('#plan').data('kendoGrid').closeCell();}
        ");
$q4Column->width(80);

/* COLUMN TOTAL */
$totalField = new \Kendo\Data\DataSourceSchemaModelField('Total');
$totalField->type('number');
$totalField->nullable(TRUE);
$totalField->editable(TRUE);

$totalColumn = new \Kendo\UI\GridColumn();
$totalColumn->field('Total');
$totalColumn->title('Total');
$totalColumn->format('{0:n0}');
$totalColumn->editor("function (e) {                              
            $('#plan').data('kendoGrid').closeCell();}
        ");
$totalColumn->width(80);

/* COLUMN PERFORMANCE */
$performanceCenterIdField = new \Kendo\Data\DataSourceSchemaModelField('PerformanceCenterId');
$performanceCenterIdField->type('string');
$performanceCenterIdField->editable(false);


/* COLUMN PERFORMANCE CENTER */
$valueCenterIdField = new \Kendo\Data\DataSourceSchemaModelField('ValueCenterId');
$valueCenterIdField->type('string');
$valueCenterIdField->editable(false);



// RESUME

/* COLUMN ACTUAL MONTH */
$monthField = new \Kendo\Data\DataSourceSchemaModelField('ActualMonth');
$monthColumn = new \Kendo\UI\GridColumn();
$monthColumn->title('Mes Actual');
$monthColumn->field('ActualMonth');

/* COLUMN CONCEPT */

$conceptField = new \Kendo\Data\DataSourceSchemaModelField('Concepto');
$conceptColumn = new \Kendo\UI\GridColumn();
$conceptColumn->field('Concepto');
$conceptColumn->title('Concepto');

$command = new \Kendo\UI\GridColumn();
$command->addCommandItem(['name' => 'destroy', 'text' => Yii::t('app', 'Delete')])
        ->title('&nbsp;')
        ->width(110);

$q1FieldResume = new \Kendo\Data\DataSourceSchemaModelField('Q1');
$q1ColumnResume = new \Kendo\UI\GridColumn();
$q1ColumnResume->field('Q1');
$q1ColumnResume->title('Q1');
$q1ColumnResume->attributes(['style' => 'background-color:\#A0C79B;color:white;font-weight:700']);
$q1ColumnResume->editor("function (e) {                              
            $('#resume').data('kendoGrid').closeCell();}
        ");

$q2FieldResume = new \Kendo\Data\DataSourceSchemaModelField('Q2');

$q2ColumnResume = new \Kendo\UI\GridColumn();
$q2ColumnResume->title('Q2');
$q2ColumnResume->field('Q2');
$q2ColumnResume->attributes(['style' => 'background-color:\#A0C79B;color:white;font-weight:700']);
$q2ColumnResume->editor("function (e) {                              
            $('#resume').data('kendoGrid').closeCell();}
        ");

$q3FieldResume = new \Kendo\Data\DataSourceSchemaModelField('Q3');

$q3ColumnResume = new \Kendo\UI\GridColumn();
$q3ColumnResume->title('Q3');
$q3ColumnResume->field('Q3');
$q3ColumnResume->attributes(['style' => 'background-color:\#A0C79B;color:white;font-weight:700']);
$q3ColumnResume->editor("function (e) {                              
            $('#resume').data('kendoGrid').closeCell();}
        ");

$q4FieldResume = new \Kendo\Data\DataSourceSchemaModelField('Q4');

$q4ColumnResume = new \Kendo\UI\GridColumn();
$q4ColumnResume->field('Q4');
$q4ColumnResume->title('Q4');
$q4ColumnResume->attributes(['style' => 'background-color:\#A0C79B;color:white;font-weight:700']);
$q4ColumnResume->editor("function (e) {                              
            $('#resume').data('kendoGrid').closeCell();}
        ");

$totalFieldResume = new \Kendo\Data\DataSourceSchemaModelField('Total');

$totalColumnResume = new \Kendo\UI\GridColumn();
$totalColumnResume->title('Total');
$totalColumnResume->field('Total');
$totalColumnResume->editor("function (e) {                              
            $('#resume').data('kendoGrid').closeCell();}
        ");




// ****************************** DEFINE DataSourceSchemaModel *******************************

$schemaPlan = new \Kendo\Data\DataSourceSchemaModel();

$schemaPlan->addField($campaignIdField);
$schemaPlan->addField($codeField);
$schemaPlan->addField($tradeProductIdField);
$schemaPlan->addField($clientProductIdField);
$schemaPlan->addField($productField);
$schemaPlan->addField($eneField);
$schemaPlan->addField($febField);
$schemaPlan->addField($marField);
$schemaPlan->addField($q1Field);
$schemaPlan->addField($abrField);
$schemaPlan->addField($junField);
$schemaPlan->addField($q2Field);
$schemaPlan->addField($julField);
$schemaPlan->addField($agoField);
$schemaPlan->addField($sepField);
$schemaPlan->addField($q3Field);
$schemaPlan->addField($octField);
$schemaPlan->addField($novField);
$schemaPlan->addField($dicField);
$schemaPlan->addField($q4Field);
$schemaPlan->addField($totalField);
$schemaPlan->addField($performanceCenterIdField);
$schemaPlan->addField($valueCenterIdField);

$schemaPlan->id('ProductName');

$schema = new \Kendo\Data\DataSourceSchema();
$schema->model($schemaPlan);


$schema->aggregates([
    'January' => 'sum'
    , 'February' => 'sum'
    , 'March' => 'sum'
    , 'April' => 'sum'
    , 'May' => 'sum'
    , 'June' => 'sum'
    , 'July' => 'sum'
    , 'August' => 'sum'
    , 'September' => 'sum'
    , 'October' => 'sum'
    , 'November' => 'sum'
    , 'December' => 'sum'
    , 'Q1' => 'sum'
    , 'Q2' => 'sum'
    , 'Q3' => 'sum'
    , 'Q4' => 'sum'
]);



// Resume
$schemaResume = new \Kendo\Data\DataSourceSchemaModel();

$schemaResume->addField($q1FieldResume);
$schemaResume->addField($q2FieldResume);
$schemaResume->addField($q3FieldResume);
$schemaResume->addField($q4FieldResume);
$schemaResume->addField($totalFieldResume);

$schemaR = new \Kendo\Data\DataSourceSchema();
$schemaR->model($schemaResume)
        ->aggregates(['Total' => 'sum']);

// ***************************** DEFINE toolbars ***************************************** 

$saveCommand = new \Kendo\UI\GridToolbarItem('save');
$cancelCommand = new \Kendo\UI\GridToolbarItem('cancel');

$detailExcelCommand = new \Kendo\UI\GridToolbarItem('excelDetail');
$detailExcelCommand->text('<span class="k-icon k-i-excel"></span> Exportar Excel');

$viewCleanDatasFilterCommand = new \Kendo\UI\GridToolbarItem('clean');
$viewCleanDatasFilterCommand->text('<span class="k-icon k-i-ungroup"></span> Limpiar Filtros');


// **************************** LOAD array with information *********************************

$transport = new \Kendo\Data\DataSourceTransport();

$create = new \Kendo\Data\DataSourceTransportCreate();

$create->url(\yii\helpers\Url::to('save'))
        ->type('POST');

$read = new \Kendo\Data\DataSourceTransportRead();

$read->url(\yii\helpers\Url::to('list'))
        ->cache(FALSE)
        ->dataType('json')
        ->type('GET');

$update = new \Kendo\Data\DataSourceTransportUpdate();

$update->url(\yii\helpers\Url::to('save'))
        //->contentType('application/json')
        ->type('POST');

$destroy = new \Kendo\Data\DataSourceTransportDestroy();

$destroy->url(\yii\helpers\Url::to('delete'))
        //->contentType('application/json')
        ->type('POST');

$transport
        ->read(['url' => \yii\helpers\Url::to('list'), 'type' => 'GET', 'success' => new Kendo\JavaScriptFunction("function(){  }"), 'complete' => new Kendo\JavaScriptFunction("function(){ calculateResume(); }")])
        ->update(['url' => \yii\helpers\Url::to('save'), 'type' => 'POST', 'beforeSend' => new Kendo\JavaScriptFunction("function(){  displayLoading('#plan',true);}"), 'complete' => new Kendo\JavaScriptFunction("function(){  $('#changesModal').modal('show'); displayLoading('#plan',false); }")])
        ->create(['url' => \yii\helpers\Url::to('save'), 'type' => 'POST', 'beforeSend' => new Kendo\JavaScriptFunction("function(){  displayLoading('#plan',true);}"), 'complete' => new Kendo\JavaScriptFunction("function(){  $('#changesModal').modal('show'); displayLoading('#plan',false); }")])
        ->destroy($destroy)
;


$dataSource = new \Kendo\Data\DataSource();
$dataSource->schema($schema);
$dataSource->batch(TRUE);
$dataSource->transport($transport);


/* * ************************ Aggregates  ****************************** */

$dataSource->addAggregateItem(["field" => "Q1", "aggregate" => "sum"]);
$dataSource->addAggregateItem(["field" => "Q2", "aggregate" => "sum"]);
$dataSource->addAggregateItem(["field" => "Q3", "aggregate" => "sum"]);
$dataSource->addAggregateItem(["field" => "Q4", "aggregate" => "sum"]);
$dataSource->addAggregateItem(["field" => "January", "aggregate" => "sum"]);
$dataSource->addAggregateItem(["field" => "February", "aggregate" => "sum"]);
$dataSource->addAggregateItem(["field" => "March", "aggregate" => "sum"]);
$dataSource->addAggregateItem(["field" => "April", "aggregate" => "sum"]);
$dataSource->addAggregateItem(["field" => "May", "aggregate" => "sum"]);
$dataSource->addAggregateItem(["field" => "June", "aggregate" => "sum"]);
$dataSource->addAggregateItem(["field" => "July", "aggregate" => "sum"]);
$dataSource->addAggregateItem(["field" => "August", "aggregate" => "sum"]);
$dataSource->addAggregateItem(["field" => "September", "aggregate" => "sum"]);
$dataSource->addAggregateItem(["field" => "October", "aggregate" => "sum"]);
$dataSource->addAggregateItem(["field" => "November", "aggregate" => "sum"]);
$dataSource->addAggregateItem(["field" => "December", "aggregate" => "sum"]);



$dataResumeSource = new \Kendo\Data\DataSource();
$dataResumeSource->schema($schemaR);
$dataResumeSource->data($dataResume);


// **************************** DEFINE grid and bindings ***********************************

$plan = new \Kendo\UI\Grid('plan');
$resumeMoney = new \Kendo\UI\Grid('resumeMoney');
$resumeUnit = new \Kendo\UI\Grid('resumeUnit');
$gridFilterable = new \Kendo\UI\GridFilterable();



$plan->attr('id', 'plan');
\Yii::$app->utilcomponents->enableOrDisableColumnsPlan([$eneColumn,$febColumn,$marColumn,$abrColumn,$mayColumn,$junColumn,$julColumn,$agoColumn,$sepColumn,$octColumn,$novColumn,$dicColumn]);
$plan->addColumn(//$codeColumn
                //, $tradeProductIdColumn             
                //    , $priceColumn
                //   , $margenColumn
                $productColumn
                , $eneColumn
                , $febColumn
                , $marColumn
                , $q1Column
                , $abrColumn
                , $mayColumn
                , $junColumn
                , $q2Column
                , $julColumn
                , $agoColumn
                , $sepColumn
                , $q3Column
                , $octColumn
                , $novColumn
                , $dicColumn
                , $q4Column
                , $totalColumn)
        ->dataSource($dataSource)
        ->edit(new \Kendo\JavaScriptFunction("function(){ if($('#ClientId').val() == '') this.closeCell(); }"))
        ->addToolbarItem(
                $saveCommand->text(Yii::t('app', 'Save Changes'))
                , $cancelCommand->text(Yii::t('app', 'Cancel Changes'))                
                , $viewCleanDatasFilterCommand
                , $detailExcelCommand
        )
        ->columnMenu(true)
        ->save('onSave')
        //->toolbarTemplateId('toolbar')
        ->reorderable(true)
        ->resizable(true)
        ->navigatable(true)
        ->scrollable(true)
        ->editable(true)
        ->sortable(true)
        ->filterable(true)
        ->height(7400)
        ->allowCopy(true);

$productColumn->filterable([
                        'operators'=>['string'=>['contains'=>'Contiene','startswith'=>"Comienza con","eq" => "Es igual a","neq" => "No contiene","doesnotcontain"=>"No contiene","endswith"=>"Termina En"]] 
                      ]);

$resumeMoney->attr('id', 'resumeMoney');
$monthColumn->format('{0:c0}');
$q1ColumnResume->format('{0:c0}');
$q2ColumnResume->format('{0:c0}');
$q3ColumnResume->format('{0:c0}');
$q4ColumnResume->format('{0:c0}');
$totalColumnResume->format('{0:c0}');
$resumeMoney->addColumn($conceptColumn
                , $monthColumn
                , $q1ColumnResume
                , $q2ColumnResume
                , $q3ColumnResume
                , $q4ColumnResume
                , $totalColumnResume)
        ->dataSource($dataResumeSource)
        ->navigatable(true)
        ->scrollable(false)
        ->editable(false)
;

$resumeUnit->attr('id', 'resumeUnit');
$monthColumn->format('{0:n0}');
$q1ColumnResume->format('{0:n0}');
$q2ColumnResume->format('{0:n0}');
$q3ColumnResume->format('{0:n0}');
$q4ColumnResume->format('{0:n0}');
$totalColumnResume->format('{0:n0}');

$resumeUnit->addColumn($conceptColumn
                , $monthColumn
                , $q1ColumnResume
                , $q2ColumnResume
                , $q3ColumnResume
                , $q4ColumnResume
                , $totalColumnResume)
        ->dataSource($dataResumeSource)
        ->navigatable(true)
        ->scrollable(false)
        ->editable(false)
;


/* SELECT FOR CLIENT */

$dropDownClient = new \Kendo\UI\DropDownList('ClientId');
$dropDownClient->dataSource($clients)
        ->filter("startswith")
        ->autoBind(false)
        ->dataTextField('Description')
        ->dataValueField('ClientId')
        ->optionLabel(Yii::t('app','All Clients'))
        ->change('refreshGrid')
        ->attr('style', 'width:100%');


?>




<style>
    #grid .k-grid-toolbar
    {
        padding: .6em 1.3em;
    }
    .category-label
    {
        vertical-align: middle;
        padding-right: .5em;
    }
    #category
    {
        vertical-align: middle;
    }
    .toolbar {
        float: right;
        margin-right: .8em;
    }
</style>                    




<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingOne">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <?= Html::encode($this->title) ?>
                </a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
                <div class="row small">



                    <div class="col-md-9">
                        <div class="panel">
                            <div class="panel-body" >


                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs"   role="tablist">
                                    <li role="presentation" class="active"><a href="#money" aria-controls="money" role="tab" data-toggle="tab">USD</a></li>
                                    <li role="presentation"><a href="#unit" aria-controls="unit" role="tab" data-toggle="tab"><?=Yii::t('app','Units') ?></a></li>

                                </ul>


                                <div class="tab-content" style="border: 1px solid #DDD;">

                                    <div role="tabpanel" class="tab-pane active" id="money"><?= $resumeMoney->render() ?></div>
                                    <div role="tabpanel" class="tab-pane" id="unit"><?= $resumeUnit->render() ?></div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>


</div>
<div class="row">
    <div class="col-xs-13 col-sm-13 col-md-8 col-lg-12">
<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong><?=Yii::t('app','Warning!') ?></strong> <?=Yii::t('app','You are modifying the plan for the following year.') ?>
</div>    
        </div>
</div>

<div class="row">
    <div class="col-xs-13 col-sm-13 col-md-3 col-lg-3">
        <?php echo '<span>Cliente </span>' . $dropDownClient->render(); ?>          
    </div>


    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 pull-right">
        <?php
        $selectPerformances = new \Kendo\UI\DropDownList('PerformanceCenter');
        $selectPerformances->dataSource(common\models\PerformanceCenter::find()->asArray()->all())
                ->autoBind(false)
                ->cascadeFrom('ValueCenter')
                ->dataTextField('Description')
                ->dataValueField('PerformanceCenterId')
                ->change('performanceChange')
        ;
        $selectPerformances->optionLabel('Seleccione');
        echo '<span>Performance Center</span>' . $selectPerformances->render();
        ?>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2  pull-right">
        <?php
        $selectCategory = new \Kendo\UI\DropDownList('ValueCenter');
        $selectCategory->dataSource(common\models\ValueCenter::find()->asArray()->all());
        $selectCategory->dataTextField('Description');
        $selectCategory->dataValueField('ValueCenterId');
        $selectCategory->optionLabel('Seleccione');
        $selectCategory->change('performanceChange');

        echo '<span>Value Center </span>' . $selectCategory->render();
        ?>     
    </div>


</div>
<br/>





<div class="small">
    <?php echo $plan->render(); ?>
</div>

<script type="text/javascript">



    function onSave(data) {



        var ene = (String(data.values.January) !== 'undefined') ? data.values.January : data.model.January;
        var feb = (String(data.values.February) !== 'undefined') ? data.values.February : data.model.February;
        var mar = (String(data.values.March) !== 'undefined') ? data.values.March : data.model.March;


        ene = (!ene || isNaN(ene)) ? 0 : parseFloat(ene);
        feb = (!feb || isNaN(feb)) ? 0 : parseFloat(feb);
        mar = (!mar || isNaN(mar)) ? 0 : parseFloat(mar);



        var abr = (String(data.values.April) !== 'undefined') ? data.values.April : data.model.April;
        var may = (String(data.values.May) !== 'undefined') ? data.values.May : data.model.May;
        var jun = (String(data.values.June) !== 'undefined') ? data.values.June : data.model.June;


        abr = (!abr || isNaN(abr)) ? 0 : parseFloat(abr);
        may = (!may || isNaN(may)) ? 0 : parseFloat(may);
        jun = (!jun || isNaN(jun)) ? 0 : parseFloat(jun);





        var jul = (String(data.values.July) !== 'undefined') ? data.values.July : data.model.July;
        var ago = (String(data.values.August) !== 'undefined') ? data.values.August : data.model.August;
        var sep = (String(data.values.September) !== 'undefined') ? data.values.September : data.model.September;

        jul = (!jul || isNaN(jul)) ? 0 : parseFloat(jul);
        ago = (!ago || isNaN(ago)) ? 0 : parseFloat(ago);
        sep = (!sep || isNaN(sep)) ? 0 : parseFloat(sep);




        var oct = (String(data.values.October) !== 'undefined') ? data.values.October : data.model.October;
        var nov = (String(data.values.November) !== 'undefined') ? data.values.November : data.model.November;
        var dic = (String(data.values.December) !== 'undefined') ? data.values.December : data.model.December;


        oct = (!oct || isNaN(oct)) ? 0 : parseFloat(oct);
        nov = (!nov || isNaN(nov)) ? 0 : parseFloat(nov);
        dic = (!dic || isNaN(dic)) ? 0 : parseFloat(dic);



        if (typeof data.values.Total == "undefined"
                && typeof data.values.Q1 == "undefined"
                && typeof data.values.Q2 == "undefined"
                && typeof data.values.Q3 == "undefined"
                && typeof data.values.Q4 == "undefined"
                )
        {
           
            data.model.set("Q1", ene + feb + mar);
            data.model.set("Q2", abr + may + jun);
            data.model.set("Q3", jul + ago + sep);
            data.model.set("Q4", oct + nov + dic);
            data.model.set("<?= $actual ?>",<?= $actualAbrv ?>);
            data.model.set("Total", ene + feb + mar + abr + may + jun + jul + ago + sep + oct + nov + dic);             

        }

        calculateResume();
    }




    function calculateResume()
    {
        /* CALCULATE GRID RESUME */

        var collection = $('#plan').data('kendoGrid').dataSource.data();

        var sumQ1Price = 0;
        var sumQ2Price = 0;
        var sumQ3Price = 0;
        var sumQ4Price = 0;
        var actualMonth = 0;


        var sumQ1Unit = 0;
        var sumQ2Unit = 0;
        var sumQ3Unit = 0;
        var sumQ4Unit = 0;
        var actualMonthUnit = 0;

        for (var i = 0; i < collection.length; i++)
        {

            var q1 = (!collection[i].Q1 || isNaN(collection[i].Q1)) ? 0 : parseFloat(collection[i].Q1);
            var q2 = (!collection[i].Q2 || isNaN(collection[i].Q2)) ? 0 : parseFloat(collection[i].Q2);
            var q3 = (!collection[i].Q3 || isNaN(collection[i].Q3)) ? 0 : parseFloat(collection[i].Q3);
            var q4 = (!collection[i].Q4 || isNaN(collection[i].Q4)) ? 0 : parseFloat(collection[i].Q4);
            var price = (!collection[i].PlanPrice || isNaN(collection[i].PlanPrice)) ? 0 : parseFloat(collection[i].PlanPrice);
            var actual = (!collection[i].get('<?= $actual ?>') || isNaN(collection[i].get('<?= $actual ?>'))) ? 0 : parseFloat(collection[i].get('<?= $actual ?>'));

            sumQ1Unit = sumQ1Unit + q1;
            sumQ2Unit = sumQ2Unit + q2;
            sumQ3Unit = sumQ3Unit + q3;
            sumQ4Unit = sumQ4Unit + q4;
            actualMonthUnit = actualMonthUnit + actual;

            sumQ1Price = sumQ1Price + (q1 * price);
            sumQ2Price = sumQ2Price + (q2 * price);
            sumQ3Price = sumQ3Price + (q3 * price);
            sumQ4Price = sumQ4Price + (q4 * price);
            actualMonth = actualMonth + (actual * price);
        }
        
        var planItem = $('#resumeMoney').data().kendoGrid.dataSource.data()[0];
        
        kendo.culture("en-US");
        /********************************* TABLE RESUME MONEY OR USD ***********************************/
        
        planItem.set('Q1', sumQ1Price);
        planItem.set('Q2', sumQ2Price);
        planItem.set('Q3', sumQ3Price);
        planItem.set('Q4', sumQ4Price);
        planItem.set('ActualMonth', actualMonth);
        planItem.set('Total', sumQ1Price + sumQ2Price + sumQ3Price + sumQ4Price);
        
        /************************  TABLE UNIT ****************************/

        var planItem = $('#resumeUnit').data().kendoGrid.dataSource.data()[0];
       

        planItem.set('Q1', sumQ1Unit);
        planItem.set('Q2', sumQ2Unit);
        planItem.set('Q3', sumQ3Unit);
        planItem.set('Q4', sumQ4Unit);
        planItem.set('ActualMonth', actualMonthUnit);
        planItem.set('Total', sumQ1Unit + sumQ2Unit + sumQ3Unit + sumQ4Unit);
      
    }



  

    $("body").on("click", ".k-grid-clean", function () {

        $("#ClientId").data("kendoDropDownList").value("");
        $("#ValueCenter").data("kendoDropDownList").value("");
        $("#PerformanceCenter").data("kendoDropDownList").value("");
        refreshGrid();
    });

    $("body").on("click", ".k-grid-excelDetail", function () {
        var ClientId = $("#ClientId").val();
        window.location = "<?= yii\helpers\Url::to(['plan/export-report-detail']) ?>?ClientId=" + ClientId;
    });

  
    function refreshGrid()
    {

        var ClientId = $("#ClientId").val();
        var planeItemUnit = $('#resumeUnit').data().kendoGrid.dataSource.data();
        var planeItemUSD = $('#resumeMoney').data().kendoGrid.dataSource.data();

        var grid = $("#plan").data("kendoGrid");
        grid.editable = false;


        /*************************  RELOAD GRID PLAN    ******************************************/
        var url = "<?= yii\helpers\Url::to(['plan/list']) ?>?ClientId=" + ClientId;
        $('#plan').data('kendoGrid').dataSource.options.transport.read.url = url;
        $('#plan').data('kendoGrid').dataSource.read();
        performanceChange();
    }

    function performanceChange() {

        var grid = $("#plan").data("kendoGrid");
        var valueCenter = $("#ValueCenter").data("kendoDropDownList").value();
        var performanceCenter = $("#PerformanceCenter").data("kendoDropDownList").value();

        var filter = new Array();

        if (valueCenter !== "") {
            filter.push({field: "ValueCenterId", operator: "eq", value: valueCenter});
        }

        if (performanceCenter !== "") {
            filter.push({field: "PerformanceCenterId", operator: "eq", value: performanceCenter});
        }


        if (filter.length == 0)
            filter = {};

        grid.dataSource.filter(filter);
    }


</script>

<div id="containerLoad" class="k-widget"></div>

<div class="modal fade" id="changesModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t("app", "Result") ?></h4>
            </div>
            <div class="modal-body">
                <p><?= Yii::t("app", "The information was saved correctly!"); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t("app", "Close") ?></button>
            </div>
        </div>

    </div>
</div>
<script>
    function displayLoading(target, boolean) {
        var element = $(target);
        kendo.ui.progress(element, boolean);
    }
</script>

<?= $this->registerJS(' refreshGrid();                                
', \yii\web\View::POS_LOAD) ?>



