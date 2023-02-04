<?php

use common\models\Country;
use Kendo\Data\DataSource;
use Kendo\Data\DataSourceSchema;
use Kendo\Data\DataSourceSchemaModel;
use Kendo\Data\DataSourceSchemaModelField;
use Kendo\Data\DataSourceTransport;
use Kendo\JavaScriptFunction;
use Kendo\UI\DropDownList;
use Kendo\UI\Grid;
use Kendo\UI\GridColumn;
use Kendo\UI\GridToolbarItem;
use yii\helpers\Html;
use yii\helpers\Url;

$requiredValidationNumber = ['required' => [true, 'message' => Yii::t('app', 'Required')]];

/**
 * @param string $field
 * @return DataSourceSchemaModelField
 */
function createMonthDatasourceSchemaModelField($field)
{
    $requiredValidationNumber = ['required' => [true, 'message' => Yii::t('app', 'Required')]];

    return (new DataSourceSchemaModelField($field))
        ->nullable(true)
        ->editable(true)
        ->type('number')
        ->validation($requiredValidationNumber);
}

/**
 * @param string $field
 * @return DataSourceSchemaModelField
 */
function createQuarterDatasourceSchemaModelField($field)
{
    return (new DataSourceSchemaModelField($field))
        ->nullable(true)
        ->type('number');
}

/**
 * @param string $field
 * @param string $title
 * @return GridColumn
 */
function createMonthGridColumn($field, $title)
{
    $column = (new GridColumn())
        ->field($field)
        ->title($title)
        ->format('{0:n0}')
        ->width(80)
        ->footerTemplate("<div>Total: #= data.$field.sum #</div>");

    Yii::$app->utilcomponents->isColumnActiveForecastMarketing($column);

    return $column;
}

/**
 * @param string $field
 * @param string $title
 * @return GridColumn
 */
function createQuarterGridColumn($field, $title)
{
    return (new GridColumn())
        ->field($field)
        ->title($title)
        ->format('{0:n0}')
        ->attributes(['style' => 'background-color:\#A0C79B;color:white;font-weight:700'])
        ->editor(new JavaScriptFunction("function (e) { $('#forecast').data('kendoGrid').closeCell(); }"))
        ->width(80)
        ->footerTemplate("<div>Total: #= data.$field.sum #</div>");
}

$this->title = Yii::t('app', 'Forecast');

$countries = Country::find()->where(['!=', 'Description', 'Paraguay'])->asArray()->all();

//********************* DEFINE the columns and models ********************
/* CLIENTPRODUCTID */
$clientMarketingProductIdField = (new DataSourceSchemaModelField('ClientMarketingProductId'))->type('number');

/* CLIENT */
$clientField = (new DataSourceSchemaModelField('Client'))->type('string');
$clientColumn = (new GridColumn())
    ->field('Client')
    ->title('Cliente')
    ->width(150)
    ->lockable(false)
    ->locked(true)
    ->editor("function (e) { $('#forecast').data('kendoGrid').closeCell(); }")
    ->filterable([
        'operators' => ['string' => ['contains' => 'Contiene', 'startswith' => "Comienza con", "eq" => "Es igual a", "neq" => "No contiene", "doesnotcontain" => "No contiene", "endswith" => "Termina En"]]
    ]);

/* COLUMN CAMPAIGNID */
$campaignIdField = (new DataSourceSchemaModelField('CampaignId'))->type('number');

/* COLUMN TRADEPRODUCTID */
$tradeProductIdField = (new DataSourceSchemaModelField('TradeProductId'))->type('string');

/* COLUMN GMIDID */
$gmidIdField = (new DataSourceSchemaModelField('GmidId'))->type('string');

/* COLUMN PERFORMANCECENTERID */
$performanceCenterIdField = (new DataSourceSchemaModelField('PerformanceCenterId'))->type('string');

/* COLUMN VALUECENTERID */
$valueCenterIdField = (new DataSourceSchemaModelField('ValueCenterId'))->type('string');

/* COLUMN FORECASTDESCRIPTION */
$productField = (new DataSourceSchemaModelField('ForecastDescription'))
    ->type('string')
    ->nullable(true);
$productColumn = (new GridColumn())
    ->field('ForecastDescription')
    ->title('Producto')
    ->width(200)
    ->lockable(false)
    ->locked(true)
    ->editor("function (e) { $('#forecast').data('kendoGrid').closeCell(); }")
    ->filterable([
        'operators' => ['string' => ['contains' => 'Contiene', 'startswith' => "Comienza con", "eq" => "Es igual a", "neq" => "No contiene", "doesnotcontain" => "No contiene", "endswith" => "Termina En"]]
    ]);

/* COLUMN ENE */
$eneField = createMonthDatasourceSchemaModelField('January');
$eneColumn = createMonthGridColumn('January', 'Ene');

/* COLUMN FEB */
$febField = createMonthDatasourceSchemaModelField('February');
$febColumn = createMonthGridColumn('February', 'Feb');

/* COLUMN MAR */
$marField = createMonthDatasourceSchemaModelField('March');
$marColumn = createMonthGridColumn('March', 'Mar');

/* COLUMN APR */
$abrField = createMonthDatasourceSchemaModelField('April');
$abrColumn = createMonthGridColumn('April', 'Abr');

/* COLUMN MAY */
$mayField = createMonthDatasourceSchemaModelField('May');
$mayColumn = createMonthGridColumn('May', 'May');

/* COLUMN JUN */
$junField = createMonthDatasourceSchemaModelField('June');
$junColumn = createMonthGridColumn('June', 'Jun');

/* COLUMN JUL */
$julField = createMonthDatasourceSchemaModelField('July');
$julColumn = createMonthGridColumn('July', 'Jul');

/* COLUMN AGO */
$agoField = createMonthDatasourceSchemaModelField('August');
$agoColumn = createMonthGridColumn('August', 'Ago');

/* COLUMN SEP */
$sepField = createMonthDatasourceSchemaModelField('September');
$sepColumn = createMonthGridColumn('September', 'Sep');

/* COLUMN OCT */
$octField = createMonthDatasourceSchemaModelField('October');
$octColumn = createMonthGridColumn('October', 'Oct');

/* COLUMN NOV */
$novField = createMonthDatasourceSchemaModelField('November');
$novColumn = createMonthGridColumn('November', 'Nov');

/* COLUMN DIC */
$dicField = createMonthDatasourceSchemaModelField('December');
$dicColumn = createMonthGridColumn('December', 'Dic');

/* COLUMN Q1 */
$q1Field = createQuarterDatasourceSchemaModelField('Q1');
$q1Column = createQuarterGridColumn('Q1', 'Q1');

/* COLUMN Q2 */
$q2Field = createQuarterDatasourceSchemaModelField('Q2');
$q2Column = createQuarterGridColumn('Q2', 'Q2');

/* COLUMN Q3 */
$q3Field = createQuarterDatasourceSchemaModelField('Q3');
$q3Column = createQuarterGridColumn('Q3', 'Q3');

/* COLUMN Q4 */
$q4Field = createQuarterDatasourceSchemaModelField('Q4');
$q4Column = createQuarterGridColumn('Q4', 'Q4');

/* COLUMN TOTAL */
$totalField = (new DataSourceSchemaModelField('Total'))
    ->nullable(true)
    ->type('number');
$totalColumn = (new GridColumn())
    ->field('Total')
    ->title('Total')
    ->format('{0:n0}')
    ->editor(new JavaScriptFunction("function (e) { $('#forecast').data('kendoGrid').closeCell(); }"))
    ->width(80)
    ->footerTemplate("<div>total: #= data.Total.sum #</div>");

$nullColumn = (new GridColumn())
    ->field('')
    ->title('')
    ->format('{0:n0}')
    ->editor(new JavaScriptFunction("function (e) { $('#forecast').data('kendoGrid').closeCell(); }"))
    ->width(80);

// ****************************** DEFINE DataSourceSchemaModel *******************************
$modelForecast = (new DataSourceSchemaModel())
    ->addField(
        $clientMarketingProductIdField,
        $clientField,
        $campaignIdField,
        $tradeProductIdField,
        $gmidIdField,
        $performanceCenterIdField,
        $valueCenterIdField,
        $eneField,
        $febField,
        $marField,
        $abrField,
        $mayField,
        $junField,
        $julField,
        $agoField,
        $sepField,
        $octField,
        $novField,
        $dicField,
        $q1Field,
        $q2Field,
        $q3Field,
        $q4Field,
        $totalField
    );

$schema = (new DataSourceSchema())->model($modelForecast);

// ***************************** DEFINE toolbars *****************************************
$saveCommand = new GridToolbarItem('save');
$cancelCommand = new GridToolbarItem('cancel');

// **************************** LOAD array with information *********************************
$transport = (new DataSourceTransport())
    ->read([
        'url' => Url::to(['forecast-marketing/get-empty-products']),
        'type' => 'GET'
    ])
    ->update([
        'url' => Url::to(['forecast-marketing/save']),
        'type' => 'POST',
        'beforeSend' => new JavaScriptFunction("function() { displayLoading('#forecast',true); }"),
        'complete' => new JavaScriptFunction("function() { $('#changesModal').modal('show'); displayLoading('#forecast',false); }")
    ])
    ->create([
        'url' => Url::to(['forecast-marketing/save']),
        'type' => 'POST',
        'beforeSend' => new JavaScriptFunction("function() { displayLoading('#forecast',true); }"),
        'complete' => new JavaScriptFunction("function() { $('#changesModal').modal('show'); displayLoading('#forecast',false); reloadDataFromGrid(); }")
    ])
    ->destroy([
        'url' => Url::to(['forecast-marketing/delete']),
        'type' => 'POST'
    ]);

$dataSource = (new DataSource())
    ->schema($schema)
    ->batch(true)
    ->transport($transport);

/* * ************************ Aggregates  ****************************** */
$dataSource->addAggregateItem(["field" => "Q1", "aggregate" => "sum"])
    ->addAggregateItem(["field" => "Q2", "aggregate" => "sum"])
    ->addAggregateItem(["field" => "Q3", "aggregate" => "sum"])
    ->addAggregateItem(["field" => "Q4", "aggregate" => "sum"])
    ->addAggregateItem(["field" => "January", "aggregate" => "sum"])
    ->addAggregateItem(["field" => "February", "aggregate" => "sum"])
    ->addAggregateItem(["field" => "March", "aggregate" => "sum"])
    ->addAggregateItem(["field" => "April", "aggregate" => "sum"])
    ->addAggregateItem(["field" => "May", "aggregate" => "sum"])
    ->addAggregateItem(["field" => "June", "aggregate" => "sum"])
    ->addAggregateItem(["field" => "July", "aggregate" => "sum"])
    ->addAggregateItem(["field" => "August", "aggregate" => "sum"])
    ->addAggregateItem(["field" => "September", "aggregate" => "sum"])
    ->addAggregateItem(["field" => "October", "aggregate" => "sum"])
    ->addAggregateItem(["field" => "November", "aggregate" => "sum"])
    ->addAggregateItem(["field" => "December", "aggregate" => "sum"])
    ->addAggregateItem(["field" => "Total", "aggregate" => "sum"]);

// **************************** DEFINE grid and bindings ***********************************
$forecastGrid = (new Grid('forecast'))
    ->addColumn(
        $clientColumn,
        $productColumn,
        $eneColumn,
        $febColumn,
        $marColumn,
        $q1Column,
        $abrColumn,
        $mayColumn,
        $junColumn,
        $q2Column,
        $julColumn,
        $agoColumn,
        $sepColumn,
        $q3Column,
        $octColumn,
        $novColumn,
        $dicColumn,
        $q4Column,
        $totalColumn
    )
    ->dataSource($dataSource)
    ->edit(new JavaScriptFunction("function() { if($('#ClientId').val() == '') this.closeCell(); }"))
    ->addToolbarItem(
        $saveCommand->text(Yii::t('app', 'Save Changes')),
        $cancelCommand->text(Yii::t('app', 'Cancel Changes'))
    )
    ->columnMenu(true)
    ->save('onSave')
    ->reorderable(true)
    ->resizable(true)
    ->navigatable(true)
    ->editable(true)
    ->sortable(true)
    ->filterable(true)
    ->allowCopy(true)
    ->dataBound(new JavaScriptFunction("
        /**
        * Fix grid header
        */
        function onDataBound(e) {
            var wrapper = this.wrapper;
            var toolbar = wrapper.find(\".k-grid-toolbar\");
            var header = wrapper.find(\".k-grid-header\");

            function scrollFixed() {
                var offset = $('.navbar-fixed-top').offset().top + $('.navbar-fixed-top').outerHeight();
                var tableOffsetTop = wrapper.offset().top;
                var top = $('.navbar-fixed-top').height() + 21;

                if (offset >= tableOffsetTop) {
                    header.addClass(\"fixed-header\");
                    if (toolbar.length > 0) {
                        toolbar.addClass(\"fixed-header\");
                        toolbar.css({ 'top': top, width: \"100%\" });
                        header.css({ 'top': top + toolbar.innerHeight() });
                    }
                    else {
                        header.css({ 'top': top });
                    }
                }
                else {
                    header.removeClass(\"fixed-header\");
                    if (toolbar.length > 0)
                        toolbar.removeClass(\"fixed-header\");
                }
            }
            $(window).scroll(scrollFixed);
			
			function resizeGrid(grid) {
                setTimeout(function() {
                    var lockedContent = grid.wrapper.children(\".k-grid-content-locked\")
                    var content = grid.wrapper.children(\".k-grid-content\");
                    
                    grid.wrapper.height(\"\");
                    lockedContent.height(\"\");
                    content.height(\"\");
                    
                    grid.wrapper.height(grid.wrapper.height());
                    
                    grid.resize();
                });
            }
            resizeGrid(e.sender);
        }"
    ));

$this->title = "Forecast Marketing"
?>

<div id="divBlack">
    <div id="loading">
        <img src="<?= Yii::$app->request->baseUrl ?>/images/loading.gif" width="60"/>
        <br>
        <?= Yii::t('app', 'Processing...') ?>
    </div>
</div>

<style>
    #grid .k-grid-toolbar {
        padding: .6em 1.3em;
    }

    .category-label {
        vertical-align: middle;
        padding-right: .5em;
    }

    #category {
        vertical-align: middle;
    }

    .toolbar {
        float: right;
        margin-right: .8em;
    }

    #divBlack {
        display: none;
        position: fixed;
        width: 100%;
        height: 100%;
        left: 0;
        top: 0;
    }

    .fixed-header {
        position: fixed;
        width: auto;
        z-index: 1;
    }
</style>

<div class="row" style="margin-bottom: 2rem;">
    <div class="col-lg-12">
        <h2><?= Html::encode($this->title) ?></h2>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
        <?php
        $selectCountry = (new DropDownList('Country'))
            ->dataSource($countries)
            ->autoBind(false)
            ->dataTextField('Description')
            ->dataValueField('CountryId')
            ->optionLabel(Yii::t('app', 'Select'))
            ->change('countryChange');
        echo '<span>Country</span>' . $selectCountry->render();
        ?>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style="margin-top: 18px;">
        <button id="buscarProductos" type="button" class="btn btn-success">Buscar productos</button>
    </div>
</div>

<br>

<div class="row">
    <div class="col-xs-13 col-sm-13 col-md-3 col-lg-3">
        <?php
        $dropDownProduct = (new DropDownList('TradeProductId'))
            ->dataSource([])
            ->filter("startswith")
            ->autoBind(false)
            ->dataTextField('Description')
            ->dataValueField('GmidId')
            ->optionLabel(Yii::t('app', 'All products'))
            ->change('refreshGrid')
            ->attr('style', 'width:100%')
            ->enable(false);
        echo '<span>' . Yii::t('app', 'Product') . '</span>' . $dropDownProduct->render();
        ?>
    </div>
</div>

<br/>

<div class="small">
    <?php echo $forecastGrid->render(); ?>
</div>

<script type="text/javascript">
    var $body = $("body");
    var $loading = $('#divBlack');
    var $buscarProductos = $("#buscarProductos");

    $body.on("click", "#buscarProductos", refreshProductos);

    $buscarProductos.prop('disabled', true);

    function countryChange() {
        $buscarProductos.prop('disabled', true);

        if (this.value() === "") {
            return;
        }

        $buscarProductos.prop('disabled', false);
    }

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
        ) {
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

            data.model.set("Total", ene + feb + mar + abr + may + jun + jul + ago + sep + oct + nov + dic);
        }
    }

    function refreshProductos() {
        $loading.toggle();

        var $TradeProductId = $("#TradeProductId").data('kendoDropDownList');
        $TradeProductId.enable(false);

        var CountryId = $("#Country").data("kendoDropDownList").value();

        var url = "<?= Url::to(['forecast-marketing/get-products']) ?>?CountryId=" + CountryId;

        $.get(url, function (data) {
            $TradeProductId.dataSource.data(data);
            $TradeProductId.enable(true);

            setTimeout(function () {
                $loading.toggle();

                refreshGrid();
            }, 500);
        });
    }

    function reloadDataFromGrid() {
        $loading.toggle();

        var $forecastDataSource = $('#forecast').data('kendoGrid').dataSource;

        $forecastDataSource.read().then(function () {
            setTimeout(function () {
                $loading.toggle();
            }, 500);
        });
    }

    function refreshGrid(e) {
        $loading.toggle();

        var TradeProductId = "";
        var GmidId = "";

        if (e) {
            var dataItem = this.dataItem(e.item);

            TradeProductId = dataItem.TradeProductId || "";
            GmidId = dataItem.GmidId || "";
        }

        var CountryId = $("#Country").data("kendoDropDownList").value();

        $("#forecast").data("kendoGrid").editable = false;

        var $forecastDataSource = $('#forecast').data('kendoGrid').dataSource;

        var url = "<?= Url::to(['forecast-marketing/get-clients']) ?>?CountryId=" + CountryId +
            "&TradeProductId=" + TradeProductId +
            "&GmidId=" + GmidId;

        $forecastDataSource.options.transport.read.url = url;

        $forecastDataSource.read().then(function () {
            setTimeout(function () {
                $loading.toggle();
            }, 500);
        });
    }

    function displayLoading(target, boolean) {
        kendo.ui.progress($(target), boolean);
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
                <button type="button" class="btn btn-default"
                        data-dismiss="modal"><?= Yii::t("app", "Close") ?></button>
            </div>
        </div>
    </div>
</div>
