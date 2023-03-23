<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;

?>

<div id="divBlack" style="display:none;">
    <div id="loading">
        <img src="<?= Yii::$app->request->baseUrl ?>/images/loading.gif" width="60"/>
        <br>
        <?= Yii::t('app', 'Processing...') ?>
    </div>
</div>

<div id="containerTec" class="container full-width">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            
        </div>
    </div>
    <?php

$this->title = Yii::t('app', 'Forecast');

$dataResume = array(
    array('Concepto' => Yii::t('app','Plan'),
        'ActualMonth' => 0,
        'Q1' => 0,
        'Q2' => 0,
        'Q3' => 0,
        'Q4' => 0,
        'Total' => 0,
    ),
    array('Concepto' => Yii::t('app','Forecast + Sales'),
        'ActualMonth' => 0,
        'Q1' => 0,
        'Q2' => 0,
        'Q3' => 0,
        'Q4' => 0,
        'Total' => 0,
    ),
    array(
        'Concepto' => Yii::t('app','Difference'),
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
$requiredValidationNumber = array('required' => array(true, 'message' => Yii::t('app','Required')));

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
            $('#forecast').data('kendoGrid').closeCell();}
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
            $('#forecast').data('kendoGrid').closeCell();}
        ");
$tradeProductIdColumn->width(80);


/* COLUMN PRICE */
$priceField = new \Kendo\Data\DataSourceSchemaModelField('ForecastPrice');
$priceField->type('number')->validation($requiredValidationNumber);
$priceField->editable(false);
$priceColumn = new \Kendo\UI\GridColumn();
$priceColumn->field('ForecastPrice');
$priceColumn->hidden(TRUE);
$priceColumn->title('Precio');
$priceColumn->editor("function (e) {                              
            $('#forecast').data('kendoGrid').closeCell();}
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
            $('#forecast').data('kendoGrid').closeCell();}
        ");
$margenColumn->width(80);

/* COLUMN PRODUCTO */
$productField = new \Kendo\Data\DataSourceSchemaModelField('ForecastDescription');
$productField->type('string')
        ->editable(false)
        ->nullable(true);
$productColumn = new \Kendo\UI\GridColumn();
$productColumn->field('ForecastDescription');
$productColumn->title('Product');
$productColumn->width(200);
$productColumn->lockable(false);
$productColumn->locked(true);

$productColumn->editor("function (e) {                              
            $('#forecast').data('kendoGrid').closeCell();}
        ");


/* COLUMN ENE */
$eneField = new \Kendo\Data\DataSourceSchemaModelField('January');
$eneField->nullable(TRUE);
$eneField->editable(TRUE);
$eneField->type('number');
$eneColumn = new \Kendo\UI\GridColumn();
$eneColumn->field('January');
$eneColumn->title('Jan');
$eneColumn->format('{0:n0}');
$eneColumn->width(80);

Yii::$app->utilcomponents->isColumnActive($eneColumn);

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
Yii::$app->utilcomponents->isColumnActive($febColumn);

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
Yii::$app->utilcomponents->isColumnActive($marColumn);

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
            $('#forecast').data('kendoGrid').closeCell();}
        ");
$q1Column->width(80);

/* COLUMN Abr */
$abrField = new \Kendo\Data\DataSourceSchemaModelField('April');
$abrField->nullable(TRUE);
$abrField->editable(TRUE);
$abrField->type('number');
$abrColumn = new \Kendo\UI\GridColumn();
$abrColumn->field('April');
$abrColumn->title('Apr');
$abrColumn->format('{0:n0}');
$abrColumn->width(80);
Yii::$app->utilcomponents->isColumnActive($abrColumn);
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
Yii::$app->utilcomponents->isColumnActive($mayColumn);

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
Yii::$app->utilcomponents->isColumnActive($junColumn);
/* COLUMN Q2 */

$q2Field = new \Kendo\Data\DataSourceSchemaModelField('Q2');
$q2Field->nullable(TRUE);
$q2Field->editable(TRUE);
$q2Field->type('number')
        ->validation($requiredValidationNumber)
        ->editable(false);
$q2Column = new \Kendo\UI\GridColumn();
$q2Column->field('Q2');
$q2Column->title('Q2');
$q2Column->format('{0:n0}');
$q2Column->attributes(['style' => 'background-color:\#A0C79B;color:white;font-weight:700']);
$q2Column->editor("function (e) {                              
            $('#forecast').data('kendoGrid').closeCell();}
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
Yii::$app->utilcomponents->isColumnActive($julColumn);

/* COLUMN Ago */
$agoField = new \Kendo\Data\DataSourceSchemaModelField('August');
$agoField->nullable(TRUE);
$agoField->editable(TRUE);
$agoField->type('number');
$agoColumn = new \Kendo\UI\GridColumn();
$agoColumn->field('August');
$agoColumn->title('Aug');
$agoColumn->format('{0:n0}');
$agoColumn->width(80);
Yii::$app->utilcomponents->isColumnActive($agoColumn);

/* COLUMN SEP */
$sepField = new \Kendo\Data\DataSourceSchemaModelField('September');
$sepField->nullable(TRUE);
$sepField->editable(TRUE);
$sepField->type('number');
$sepColumn = new \Kendo\UI\GridColumn();
$sepColumn->field('September');
$sepColumn->title('Sept');
$sepColumn->format('{0:n0}');
$sepColumn->width(80);
Yii::$app->utilcomponents->isColumnActive($sepColumn);
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
            $('#forecast').data('kendoGrid').closeCell();}
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
Yii::$app->utilcomponents->isColumnActive($octColumn);
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
Yii::$app->utilcomponents->isColumnActive($novColumn);
/* COLUMN DIC */
$dicField = new \Kendo\Data\DataSourceSchemaModelField('December');
$dicField->nullable(TRUE);
$dicField->editable(TRUE);
$dicField->type('number');
$dicColumn = new \Kendo\UI\GridColumn();
$dicColumn->field('December');
$dicColumn->title('Dec');
$dicColumn->format('{0:n0}');
$dicColumn->width(80);
Yii::$app->utilcomponents->isColumnActive($dicColumn);

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
            $('#forecast').data('kendoGrid').closeCell();}
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
            $('#forecast').data('kendoGrid').closeCell();}
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
$monthColumn->title('ActualMonth');
$monthColumn->field('ActualMonth');

/* COLUMN CONCEPT */

$conceptField = new \Kendo\Data\DataSourceSchemaModelField('Concepto');
$conceptColumn = new \Kendo\UI\GridColumn();
$conceptColumn->field('Concepto');
$conceptColumn->title('Concept');

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
$schemaForecast = new \Kendo\Data\DataSourceSchemaModel();

$schemaForecast->addField($campaignIdField);
$schemaForecast->addField($codeField);
$schemaForecast->addField($tradeProductIdField);
$schemaForecast->addField($clientProductIdField);
$schemaForecast->addField($productField);
$schemaForecast->addField($eneField);
$schemaForecast->addField($febField);
$schemaForecast->addField($marField);
$schemaForecast->addField($q1Field);
$schemaForecast->addField($abrField);
$schemaForecast->addField($junField);
$schemaForecast->addField($q2Field);
$schemaForecast->addField($julField);
$schemaForecast->addField($agoField);
$schemaForecast->addField($sepField);
$schemaForecast->addField($q3Field);
$schemaForecast->addField($octField);
$schemaForecast->addField($novField);
$schemaForecast->addField($dicField);
$schemaForecast->addField($q4Field);
$schemaForecast->addField($totalField);
$schemaForecast->addField($performanceCenterIdField);
$schemaForecast->addField($valueCenterIdField);

$schemaForecast->id('ProductName');

$schema = new \Kendo\Data\DataSourceSchema();
$schema->model($schemaForecast);


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
//$schemaResume->addField($diffColumn);
$schemaResume->addField($totalFieldResume);

$schemaR = new \Kendo\Data\DataSourceSchema();
$schemaR->model($schemaResume)
        ->aggregates(['Total' => 'sum']);

// ***************************** DEFINE toolbars ***************************************** 

$saveCommand = new \Kendo\UI\GridToolbarItem('save');
$cancelCommand = new \Kendo\UI\GridToolbarItem('cancel');
$detailExcelCommand = new \Kendo\UI\GridToolbarItem('excelDetail');
$detailExcelCommand->text('<span class="k-icon k-i-excel"></span> Export Excel');

$consolidExcelCommand = new \Kendo\UI\GridToolbarItem('excelConsolid');
$consolidExcelCommand->text('<span class="k-icon k-i-excel"></span> Consolidated Excel');

$viewExtraDatasFilterCommand = new \Kendo\UI\GridToolbarItem('foreextra');
$viewExtraDatasFilterCommand->text('<span class="k-icon k-i-search"></span> Sales');

$viewCleanDatasFilterCommand = new \Kendo\UI\GridToolbarItem('clean');
$viewCleanDatasFilterCommand->text('<span class="k-icon k-i-ungroup"></span> clear filters');


$viewCalculateDatasFilterCommand = new \Kendo\UI\GridToolbarItem('calculateResume');
$viewCalculateDatasFilterCommand->text('<span class="k-icon k-i-sum"></span> Resume');

// **************************** LOAD array with information *********************************

$transport = new \Kendo\Data\DataSourceTransport();

$create = new \Kendo\Data\DataSourceTransportCreate();

$create->url(\yii\helpers\Url::to('save'))

        //->contentType('application/json')
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
        ->read(['url' => \yii\helpers\Url::to('list'), 'type' => 'GET', 'success' => new Kendo\JavaScriptFunction("function(){ alert('grabo ok'); }"), 'complete' => new Kendo\JavaScriptFunction("function(){ calculateResume();  }")])
        ->update(['url' => \yii\helpers\Url::to('save'), 'type' => 'POST', 'beforeSend' => new Kendo\JavaScriptFunction("function(){  displayLoading('#forecast',true);}"), 'complete' => new Kendo\JavaScriptFunction("function(){  $('#changesModal').modal('show'); displayLoading('#forecast',false); }")])
        ->create(['url' => \yii\helpers\Url::to('save'), 'type' => 'POST', 'beforeSend' => new Kendo\JavaScriptFunction("function(){  displayLoading('#forecast',true);}"), 'complete' => new Kendo\JavaScriptFunction("function(){  $('#changesModal').modal('show'); displayLoading('#forecast',false); }")])
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

$sales = new \Kendo\UI\Grid('sales');
$forecast = new \Kendo\UI\Grid('forecast');
$resumeMoney = new \Kendo\UI\Grid('resumeMoney');
$resumeUnit = new \Kendo\UI\Grid('resumeUnit');
$gridFilterable = new \Kendo\UI\GridFilterable();
$productColumn->filterable([
                        'operators'=>['string'=>['contains'=>'Contiene','startswith'=>"Comienza con","eq" => "Es igual a","neq" => "No contiene","doesnotcontain"=>"No contiene","endswith"=>"Termina En"]] 
                      ]);


$forecast->attr('id', 'forecast');

$forecast->addColumn(//$codeColumn
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
                , $viewExtraDatasFilterCommand
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



        //->selectable('cell multiple')
        ->allowCopy(true)

;
if (!\Yii::$app->user->can(\common\models\AuthItem::ROLE_SELLER))
    $forecast->addToolbarItem($consolidExcelCommand);

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

$dropDownCampaign = new \Kendo\UI\DropDownList('CampaignId');
$dropDownCampaign->dataSource($campaigns)
        ->autoBind(false)
        ->dataTextField('Name')
        ->dataValueField('CampaignId')
        ->optionLabel(Yii::t('app','Select'));
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
    <div class="col-xs-13 col-sm-13 col-md-3 col-lg-3">
        <?php echo '<span>'.Yii::t('app','Client').'</span>' . $dropDownClient->render(); ?>          
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
        $selectPerformances->optionLabel(Yii::t('app','Select'));
        echo '<span>Performance Center</span>' . $selectPerformances->render();
        ?>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2  pull-right">
        <?php
        $selectCategory = new \Kendo\UI\DropDownList('ValueCenter');
        $selectCategory->dataSource(common\models\ValueCenter::find()->asArray()->all());
        $selectCategory->dataTextField('Description');
        $selectCategory->dataValueField('ValueCenterId');
        $selectCategory->optionLabel(Yii::t('app','Select'));
        $selectCategory->change('performanceChange');

        echo '<span>Value Center </span>' . $selectCategory->render();             
        ?>     
    </div>


</div>
<br/>





<div class="small">
    <?php echo $forecast->render(); ?>
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
            data.model.set("January", ene);
            data.model.set("February", feb);
            data.model.set("March", mar);
            data.model.set("April", abr);
            data.model.set("May", may);
            data.model.set("June", jun);
            data.model.set("July", jul);
            data.model.set("August", ago);
            data.model.set("September", sep);
            data.model.set("October", oct);
            data.model.set("November", nov);
            data.model.set("December", dic);
            
            data.model.set("Q1", ene + feb + mar);
            data.model.set("Q2", abr + may + jun);
            data.model.set("Q3", jul + ago + sep);
            data.model.set("Q4", oct + nov + dic);
            data.model.set("<?= $actual ?>",<?= $actualAbrv ?>);
            data.model.set("Total", ene + feb + mar + abr + may + jun + jul + ago + sep + oct + nov + dic);


        }

        calculateResume();
    }


    var Q1BlackUSDWs,Q2BlackUSDWs,Q3BlackUSDWs,Q4BlackUSDWs,TopTalBlackUSDWs = 0;

    function calculateResume()
    {
        /* CALCULATE GRID RESUME */

        var collection = $('#forecast').data('kendoGrid').dataSource.data();

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
        
        // tremendo bardo porque se les canta no coincidir cantidad x precio  para ventas
        var q1blackUSD = 0;
        var q2blackUSD = 0;
        var q3blackUSD = 0;
        var q4blackUSD = 0;
        
        for (var i = 0; i < collection.length; i++)
        {
            
            var q1 = (!collection[i].Q1 || isNaN(collection[i].Q1)) ? 0 : parseFloat(collection[i].Q1);
            var q2 = (!collection[i].Q2 || isNaN(collection[i].Q2)) ? 0 : parseFloat(collection[i].Q2);
            var q3 = (!collection[i].Q3 || isNaN(collection[i].Q3)) ? 0 : parseFloat(collection[i].Q3);
            var q4 = (!collection[i].Q4 || isNaN(collection[i].Q4)) ? 0 : parseFloat(collection[i].Q4);
            var price = (!collection[i].ForecastPrice || isNaN(collection[i].ForecastPrice)) ? 0 : parseFloat(collection[i].ForecastPrice);
            var actual = (!collection[i].get('<?= $actual ?>') || isNaN(collection[i].get('<?= $actual ?>'))) ? 0 : parseFloat(collection[i].get('<?= $actual ?>'));

            sumQ1Unit = sumQ1Unit + q1;
            sumQ2Unit = sumQ2Unit + q2;
            sumQ3Unit = sumQ3Unit + q3;
            sumQ4Unit = sumQ4Unit + q4;
            actualMonthUnit = actualMonthUnit + actual;

         
    
            
            var ene = (!collection[i].January || isNaN(collection[i].January)) ? 0 : parseFloat(collection[i].January);
            var feb = (!collection[i].February || isNaN(collection[i].February)) ? 0 : parseFloat(collection[i].February);
            var mar = (!collection[i].March || isNaN(collection[i].March)) ? 0 : parseFloat(collection[i].March);
            var abr = (!collection[i].April || isNaN(collection[i].April)) ? 0 : parseFloat(collection[i].April);
            var may = (!collection[i].May || isNaN(collection[i].May)) ? 0 : parseFloat(collection[i].May);
            var jun = (!collection[i].June || isNaN(collection[i].June)) ? 0 : parseFloat(collection[i].June);
            var jul = (!collection[i].July || isNaN(collection[i].July)) ? 0 : parseFloat(collection[i].July);       
            var ago = (!collection[i].August || isNaN(collection[i].August)) ? 0 : parseFloat(collection[i].August);
            var sep = (!collection[i].September || isNaN(collection[i].September)) ? 0 : parseFloat(collection[i].September);
            var oct = (!collection[i].October || isNaN(collection[i].October)) ? 0 : parseFloat(collection[i].October);
            var nov = (!collection[i].November || isNaN(collection[i].November)) ? 0 : parseFloat(collection[i].November);
            var dic = (!collection[i].December || isNaN(collection[i].December)) ? 0 : parseFloat(collection[i].December);
            
            <?php if(!isset($eneColumn->properties()["editor"])) : ?>     q1blackUSD = q1blackUSD + (ene* price);       <?php endif; ?>                
            <?php if(!isset($febColumn->properties()["editor"])) : ?>     q1blackUSD = q1blackUSD + (feb* price);       <?php endif; ?>
            <?php if(!isset($marColumn->properties()["editor"])) : ?>     q1blackUSD = q1blackUSD + (mar* price);       <?php endif; ?>
                
            <?php if(!isset($abrColumn->properties()["editor"])) : ?>     q2blackUSD = q2blackUSD + (abr* price);       <?php endif; ?>
            <?php if(!isset($mayColumn->properties()["editor"])) : ?>     q2blackUSD = q2blackUSD + (may* price);       <?php endif; ?>
            <?php if(!isset($junColumn->properties()["editor"])) : ?>     q2blackUSD = q2blackUSD + (jun* price);       <?php endif; ?>

            <?php if(!isset($julColumn->properties()["editor"])) : ?>     q3blackUSD = q3blackUSD + (jul* price);       <?php endif; ?>
            <?php if(!isset($agoColumn->properties()["editor"])) : ?>     q3blackUSD = q3blackUSD + (ago* price);       <?php endif; ?>
            <?php if(!isset($sepColumn->properties()["editor"])) : ?>     q3blackUSD = q3blackUSD + (sep* price);       <?php endif; ?>                
            
            <?php if(!isset($octColumn->properties()["editor"])) : ?>     q4blackUSD = q4blackUSD + (oct* price);       <?php endif; ?>                
             <?php if(!isset($novColumn->properties()["editor"])) : ?>     q4blackUSD = q4blackUSD + (nov* price);       <?php endif; ?>                
             <?php if(!isset($dicColumn->properties()["editor"])) : ?>     q4blackUSD = q4blackUSD + (dic* price);       <?php endif; ?>                                                          
           
    
             actualMonth = actualMonth + (actual * price)
        }
        
            sumQ1Price = sumQ1Price + q1blackUSD ;
            sumQ2Price = sumQ2Price + q2blackUSD ;
            sumQ3Price = sumQ3Price + q3blackUSD ;
            sumQ4Price = sumQ4Price + q4blackUSD;
           // actualMonth = actualMonth + (actual * price);
            
            if(isNaN(Q1BlackUSDWs)) Q1BlackUSDWs = 0;
            if(isNaN(Q2BlackUSDWs)) Q2BlackUSDWs = 0;
            if(isNaN(Q3BlackUSDWs)) Q3BlackUSDWs = 0;
            if(isNaN(Q4BlackUSDWs)) Q4BlackUSDWs = 0;
            
            sumQ1Price = sumQ1Price + Q1BlackUSDWs;
            sumQ2Price = sumQ2Price + Q2BlackUSDWs;
            sumQ3Price = sumQ3Price + Q3BlackUSDWs;
            sumQ4Price = sumQ4Price + Q4BlackUSDWs;
            
            
        var planeItem = $('#resumeMoney').data().kendoGrid.dataSource.data()[0];
        var forecastMoreSaleItem = $('#resumeMoney').data().kendoGrid.dataSource.data()[1];
        var diffItem = $('#resumeMoney').data().kendoGrid.dataSource.data()[2];

        /********************************* TABLE RESUME MONEY OR USD ***********************************/
        kendo.culture("en-US");
        
        forecastMoreSaleItem.set('Q1', sumQ1Price);
        forecastMoreSaleItem.set('Q2', sumQ2Price);
        forecastMoreSaleItem.set('Q3', sumQ3Price);
        forecastMoreSaleItem.set('Q4', sumQ4Price);
        forecastMoreSaleItem.set('ActualMonth', actualMonth);
        forecastMoreSaleItem.set('Total', sumQ1Price + sumQ2Price + sumQ3Price + sumQ4Price);

        /* Diff */

        var $diffActualMonth = planeItem.ActualMonth - forecastMoreSaleItem.ActualMonth;
        var $diffQ1 = planeItem.Q1 - forecastMoreSaleItem.Q1;
        var $diffQ2 = planeItem.Q2 - forecastMoreSaleItem.Q2;
        var $diffQ3 = planeItem.Q3 - forecastMoreSaleItem.Q3;
        var $diffQ4 = planeItem.Q4 - forecastMoreSaleItem.Q4;
        var $diffTotal = planeItem.Total - forecastMoreSaleItem.Total;

        var $diffActualMonthPorcentage = (planeItem.ActualMonth > 0) ? ($diffActualMonth * 100) / planeItem.ActualMonth * -1 : 100;
        var $diffQ1Porcentage = (planeItem.Q1 > 0) ? ($diffQ1 * 100) / planeItem.Q1 * -1 : 100;
        var $diffQ2Porcentage = (planeItem.Q2 > 0) ? ($diffQ2 * 100) / planeItem.Q2 * -1 : 100;
        var $diffQ3Porcentage = (planeItem.Q3 > 0) ? ($diffQ3 * 100) / planeItem.Q3 * -1 : 100;
        var $diffQ4Porcentage = (planeItem.Q4 > 0) ? ($diffQ4 * 100) / planeItem.Q4 * -1 : 100;
        var $diffTotalPorcentage = (planeItem.Total > 0) ? ($diffTotal * 100) / planeItem.Total * -1 : 100;
        
        
        diffItem.set('ActualMonth', kendo.toString($diffActualMonth, "c") + ' (' + kendo.toString($diffActualMonthPorcentage, "n2") + '%)');
        diffItem.set('Q1', kendo.toString($diffQ1, "c") + ' (' + kendo.toString($diffQ1Porcentage, "n2") + '%)');
        diffItem.set('Q2', kendo.toString($diffQ2, "c") + ' (' + kendo.toString($diffQ2Porcentage, "n2") + '%)');
        diffItem.set('Q3', kendo.toString($diffQ3, "c") + ' (' + kendo.toString($diffQ3Porcentage, "n2") + '%)');
        diffItem.set('Q4', kendo.toString($diffQ4, "c") + ' (' + kendo.toString($diffQ4Porcentage, "n2") + '%)');        
        diffItem.set('Total', kendo.toString($diffTotal, "c") + ' (' + kendo.toString($diffTotalPorcentage, "n2") + '%)');

     
        /************************  TABLE UNIT ****************************/

        var planeItem = $('#resumeUnit').data().kendoGrid.dataSource.data()[0];
        var forecastMoreSaleItem = $('#resumeUnit').data().kendoGrid.dataSource.data()[1];
        var diffItem = $('#resumeUnit').data().kendoGrid.dataSource.data()[2];

        /* Forecast + Sales */
        forecastMoreSaleItem.set('Q1', sumQ1Unit);
        forecastMoreSaleItem.set('Q2', sumQ2Unit);
        forecastMoreSaleItem.set('Q3', sumQ3Unit);
        forecastMoreSaleItem.set('Q4', sumQ4Unit);
        forecastMoreSaleItem.set('ActualMonth', actualMonthUnit);
        forecastMoreSaleItem.set('Total', sumQ1Unit + sumQ2Unit + sumQ3Unit + sumQ4Unit);

        /* Diff */

        var $diffActualMonth = planeItem.ActualMonth - forecastMoreSaleItem.ActualMonth;
        var $diffQ1 = planeItem.Q1 - forecastMoreSaleItem.Q1;
        var $diffQ2 = planeItem.Q2 - forecastMoreSaleItem.Q2;
        var $diffQ3 = planeItem.Q3 - forecastMoreSaleItem.Q3;
        var $diffQ4 = planeItem.Q4 - forecastMoreSaleItem.Q4;
        var $diffTotal = planeItem.Total - forecastMoreSaleItem.Total;

        var $diffActualMonthPorcentage = (planeItem.ActualMonth > 0) ? ($diffActualMonth * 100) / planeItem.ActualMonth * -1 : 100;
        var $diffQ1Porcentage = (planeItem.Q1 > 0) ? ($diffQ1 * 100) / planeItem.Q1 * -1 : 100;
        var $diffQ2Porcentage = (planeItem.Q2 > 0) ? ($diffQ2 * 100) / planeItem.Q2 * -1 : 100;
        var $diffQ3Porcentage = (planeItem.Q3 > 0) ? ($diffQ3 * 100) / planeItem.Q3 * -1 : 100;
        var $diffQ4Porcentage = (planeItem.Q4 > 0) ? ($diffQ4 * 100) / planeItem.Q4 * -1 : 100;
        var $diffTotalPorcentage = (planeItem.Total > 0) ? ($diffTotal * 100) / planeItem.Total * -1 : 100;


        diffItem.set('ActualMonth', kendo.toString($diffActualMonth, "n0") + ' (' + kendo.toString($diffActualMonthPorcentage, "n2") + '%)');
        diffItem.set('Q1', kendo.toString($diffQ1, "n0") + ' (' + kendo.toString($diffQ1Porcentage, "n2") + '%)');
        diffItem.set('Q2', kendo.toString($diffQ2, "n0") + ' (' + kendo.toString($diffQ2Porcentage, "n2") + '%)');
        diffItem.set('Q3', kendo.toString($diffQ3, "n0") + ' (' + kendo.toString($diffQ3Porcentage, "n2") + '%)');
        diffItem.set('Q4', kendo.toString($diffQ4, "n0") + ' (' + kendo.toString($diffQ4Porcentage, "n2") + '%)');
        diffItem.set('Total', kendo.toString( (isNaN($diffTotal)) ? 0 : $diffTotal, "n0") + ' (' + kendo.toString($diffTotalPorcentage, "n2") + '%)');
      
    }



    $("body").on("click", ".k-grid-foreextra", function () {
        var grid = $('#forecast').data('kendoGrid');
<?php
$monthEnableFrom = \common\models\Setting::getValue(\common\models\Setting::FORECAST_ENABLE_FROM);
$monthTo = \Yii::$app->utilcomponents->getAmountQuarter($monthEnableFrom - 1) + $monthEnableFrom;
?>

        if (grid.columns[1].hidden)
        {
            for (i = 1; i < <?= $monthTo ?>; i++) {
                grid.showColumn(i);
            }
        }
        else
        {
            for (i = 1; i < <?= $monthTo ?>; i++) {
                grid.hideColumn(i);
                grid.showColumn(4);
                grid.showColumn(8);
                grid.showColumn(11);
                grid.showColumn(15);
            }
        }
    });

    $("body").on("click", ".k-grid-clean", function () {

        $("#ClientId").data("kendoDropDownList").value("");
        $("#ValueCenter").data("kendoDropDownList").value("");
        $("#PerformanceCenter").data("kendoDropDownList").value("");
        refreshGrid();
    });

    $("body").on("click", ".k-grid-excelDetail", function () {
        var ClientId = $("#ClientId").val();
        window.location = "<?= yii\helpers\Url::to(['forecast/export-report-detail']) ?>?ClientId=" + ClientId;
    });

    $("body").on("click", ".k-grid-excelConsolid", function () {
        var ClientId = $("#ClientId").val();
        window.location = "<?= yii\helpers\Url::to(['forecast/export-report-consolid']) ?>?ClientId=" + ClientId;
    });

    function refreshGrid()
    {

        var ClientId = $("#ClientId").val();
        var planeItemUnit = $('#resumeUnit').data().kendoGrid.dataSource.data();
        var planeItemUSD = $('#resumeMoney').data().kendoGrid.dataSource.data();

        var grid = $("#forecast").data("kendoGrid");
        grid.editable = false;

        
        $.get("<?= yii\helpers\Url::to(['forecast/get-plan']) ?>?ClientId=" + ClientId, function (data) {

            if(isNaN(data.Q1)) data.Q1 = 0;
            if(isNaN(data.Q2)) data.Q2 = 0;
            if(isNaN(data.Q3)) data.Q3 = 0;
            if(isNaN(data.Q4)) data.Q4 = 0;
            
            
            /*************************  RELOAD RESUME GRID ******************************************/
            planeItemUnit[0].set('ActualMonth', parseFloat(isNaN(data.<?= $actual ?>) ? 0 : data.<?= $actual ?>));
            planeItemUnit[0].set('Q1', parseFloat(data.Q1));
            planeItemUnit[0].set('Q2', parseFloat(data.Q2));
            planeItemUnit[0].set('Q3', parseFloat(data.Q3));
            planeItemUnit[0].set('Q4', parseFloat(data.Q4));
            planeItemUnit[0].set('Total', kendo.toString(parseFloat(data.Q1) + parseFloat(data.Q2) + parseFloat(data.Q3) + parseFloat(data.Q4), "n0"));

            planeItemUSD[0].set('ActualMonth', parseFloat(data.<?= $actual ?>USD));
            planeItemUSD[0].set('Q1', parseFloat(data.Q1USD));
            planeItemUSD[0].set('Q2', parseFloat(data.Q2USD));
            planeItemUSD[0].set('Q3', parseFloat(data.Q3USD));
            planeItemUSD[0].set('Q4', parseFloat(data.Q4USD));
            planeItemUSD[0].set('Total', parseFloat(data.Q1USD) + parseFloat(data.Q2USD) + parseFloat(data.Q3USD) + parseFloat(data.Q4USD));

        });
  
        
        

            /*************************  RELOAD RESUME GRID ******************************************/          
     
           $.ajax({
               url:"<?= yii\helpers\Url::to(['forecast/get-sale']) ?>?ClientId=" + ClientId, 
               async:false,
               success: function (data) {
           Q1BlackUSDWs = parseFloat(data.Q1USD) ;
           Q2BlackUSDWs =  parseFloat(data.Q2USD);
           Q3BlackUSDWs =  parseFloat(data.Q3USD);
           Q4BlackUSDWs = parseFloat(data.Q4USD);           
           
        }});
       

        /*************************  RELOAD GRID FORECAST    ******************************************/
        var url = "<?= yii\helpers\Url::to(['forecast/list']) ?>?ClientId=" + ClientId;
        $('#forecast').data('kendoGrid').dataSource.options.transport.read.url = url;
        $('#forecast').data('kendoGrid').dataSource.read();
        performanceChange();
    }

    function performanceChange() {

        var grid = $("#forecast").data("kendoGrid");
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


<script>
		$("body").on("click", ".k-grid-excelDetail", function () {
				// Mostrar spinner de carga
				$("#divBlack").show();
            
				// Ocultar spinner de carga
				setTimeout(function() {$("#divBlack").hide();}, 5000)
            })

            $("body").on("click", ".k-grid-excelConsolid", function () {
				// Mostrar spinner de carga
				$("#divBlack").show();
            
				// Ocultar spinner de carga
				setTimeout(function() {$("#divBlack").hide();}, 180000)
            })     
            
				
	</script>


<?= $this->registerJS(' refreshGrid();                     
', \yii\web\View::POS_LOAD) ?>



