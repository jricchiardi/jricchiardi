<?php

use common\models\sis\DrillLevel;
use common\models\sis\SisCampaignFilter;
use common\models\sis\SisFilters;
use common\models\sis\SisView;
use common\models\sis\UserLevel;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app','SIS');

$sisView = new SisView();

$filterMonths = $sisView->filters->getFilterMonths();
$filterQuarters = $sisView->filters->getFilterQuarters();
$baseUserUrl = $sisView->drillLevel->getDrillDownUrl();

$filterUserLvl = $sisView->filters->getFilterUserLevel();

$breadcrumbs = $sisView->drillLevel->getBreadCrumb();

$data = $sisView->data->getResults();

$grid = \common\models\sis\SisGrid::getGrid($data);

$tradeProducts = \common\models\TradeProduct::find()->orderBy('Description')->asArray()->all();

$dropDownIngredient = \common\models\sis\SisDropdown::getIngredients();
$dropDownProduct = \common\models\sis\SisDropdown::getProduct();
$dropDownCountry = \common\models\sis\SisDropdown::getCountry();

$lastUpdated = $sisView->data->getLastUpdated();
$lastImported = $sisView->data->getLastImported();

$totals = $sisView->data->getTotals($data);
?>

<script id="myTemplate" type="text/x-kendo-template">
    <tr data-uid="#= UserId #">
        <td colspan="1">
		<span class="traffic-light">#: kendo.toString(SaldoParaDespachoPerc, "0.000") #</span>
            <?php if($filterUserLvl == 'Dsm' || empty($filterUserLvl)){ ?>
                <a href="<?= $baseUserUrl ?>#: UserId #">#: Usuario #</a>
            <?php } elseif($filterUserLvl == 'Tam'){ ?>
                <a href="<?= $baseUserUrl ?>#: UserId #">#: Usuario #</a>
            <?php } elseif($filterUserLvl == 'Client'){ ?>
                <a href="<?= $baseUserUrl ?>#: UserId #">#: Usuario #</a>
            <?php } else{ ?>
                #: Usuario #
            <?php } ?>
        </td>
		<td colspan="1" data-column="Plan">
            <strong>#: kendo.toString(Plan, "0,") #</strong>
        </td>
        <td colspan="1" data-column="SaleInput">
            <strong>#: kendo.toString(SaleInput, "0,") #</strong>
        </td>
        <td colspan="1" data-column="Forecast">
            <strong>#: kendo.toString(Forecast, "0,") #</strong>
        </td>
        <td colspan="1" data-column="FactPendiente">
            <strong>#: kendo.toString(FactPendiente, "0,") #</strong>
        </td>
        <td colspan="1" data-column="ContPendiente">
            <strong>#: kendo.toString(ContPendiente, "0,") #</strong>
        </td>
        <td colspan="1" data-column="RealSale">
            <strong>#: kendo.toString(RealSale, "0,") #</strong>
        </td>
        <td colspan="1" data-column="Pedidos">
            <strong>#: kendo.toString(Pedidos, "0,") #</strong>
        </td>
        <td colspan="1" data-column="PedidosFuturos">
            <strong>#: kendo.toString(PedidosFuturos, "0,") #</strong>
        </td>
        <td colspan="1" data-column="CyO">
            <strong>#: kendo.toString(CyO, "0,") #</strong>
        </td>
        <td colspan="1">
            <strong>#: kendo.toString(SaldoParaIngresar, "0,") #</strong>
        </td>
        <td colspan="1">
            <strong>#: kendo.toString(SaldoParaDespacho, "0,") #</strong>
        </td>
        <td colspan="1">
            <strong>#: kendo.toString(SaldoParaDespachoPerc, "0.0%") #</strong>
        </td>
        <td colspan="1">
            <strong>#: kendo.toString(SaldoAjustado, "0,") #</strong>
        </td>
        <td colspan="1">
            <strong>#: kendo.toString(SaldoAjustadoPerc, "0.0%") #</strong>
        </td>
    </tr>
</script>

<style>
    #spinner {
        position: fixed;
        top: 0; left: 0;
        width: 100vw; height: 100vh;
        z-index: 9999;
        background: rgba(0, 0, 0, 0.7);
        transition: opacity 0.2s;
    }

    #spinner span {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%);
        font-size: 5rem;
        animation: spin 3s linear infinite;
        color: white;
    }

    #spinner {
        visibility: hidden;
        opacity: 0;
    }
    #spinner.show {
        visibility: visible;
        opacity: 1;
    }
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>
<script id="rowClick">
    rowClick = function (e) {
        var grid = this;
        grid.tbody.find("tr").dblclick(function (e) {
            var dataItem = grid.dataItem(this);
            loadDetail(dataItem);
            // console.log(e,dataItem);
        });
    }

    function loadDetail(dataItem){
        <?php if((new SisFilters)->getFilterUserLevel() == UserLevel::LAST){ ?>
        return;
        <?php } ?>

        var column = $('#grid_active_cell').attr('data-column');
        if(!column){
            return;
        }
        //console.log('<?//= $sisView->drillLevel->getDrillDownApiUrl() ?>//' + dataItem.UserId + '&column=' + column);
        jQuery.ajax({
            url: '<?= $sisView->drillLevel->getDrillDownApiUrl() ?>' + dataItem.UserId + '&column=' + column,
            beforeSend: function (){
                loadingShow();
            },
            success: function (data){
                loadingHide();
                $('#sis-detail .modal-body').html('');
				
                $('#sis-detail h4.modal-title').text('Detalle');
                if(data.product){
                    $('#sis-detail h4.modal-title').html('Detalle <small><small>| '+data.product+'</small></small>');
                }
                // var str = JSON.stringify(data.data, null, 3)
                var table = document.createElement('table');
                table.classList.add('table');
                table.classList.add('w-100');
                var headerRow = document.createElement('tr');
                var userHeader = document.createElement('th');
				userHeader.textContent = data.userColumn ?? 'Usuario';
                var dataHeader = document.createElement('th');
                dataHeader.textContent = column;
                headerRow.appendChild(userHeader);
                headerRow.appendChild(dataHeader);
                table.appendChild(headerRow);
                for (const dataRow of data.data) {
                    const row = document.createElement('tr');
                    var userData = document.createElement('td');
                    userData.textContent = dataRow.Usuario;
                    var valueData = document.createElement('td');
                    valueData.textContent = parseFloat(dataRow[column]).toLocaleString();
                    row.appendChild(userData);
                    row.appendChild(valueData);
                    table.appendChild(row);
                }
                document.querySelector('#sis-detail .modal-body').appendChild(table);
				buildColors();
				
                if(data.metaData && data.metaData.length > 0){
                    var meta = loadMetadata(data.metaData);
                    document.querySelector('#sis-detail .modal-body').appendChild(meta)
                }
                $('#sis-detail').modal('show');
            }
        });
    }
	
    function loadMetadata(data){
        var container = document.createElement('div');
        container.classList.add('metadata');

        var title = document.createElement('h5');
        title.textContent = 'Detalle importacion';
        container.appendChild(title);

        var table = document.createElement('table');
        table.classList.add('table');
        table.classList.add('table-striped');
        table.classList.add('table-bordered');
        var headerRow = document.createElement('tr');
        var minWidth = 0;

        for (var key in data[0]) {
            if (Object.prototype.hasOwnProperty.call(data[0], key)) {
                var colHeader = document.createElement('th');
                colHeader.textContent = key;
                headerRow.appendChild(colHeader);
                minWidth += 150;
            }
        }
        table.style.minWidth = minWidth + 'px';
        table.appendChild(headerRow);

        for (const dataRow of data) {
            const row = document.createElement('tr');
            for (var key in dataRow) {
                const dataCol = dataRow[key];
                var value = document.createElement('td');
                value.textContent = dataCol;
                row.appendChild(value);
            }
            table.appendChild(row);
        }

        container.appendChild(table);
        return container;
    }
    function loadingShow () {
        document.getElementById("spinner").classList.add("show");
    }
    function loadingHide () {
        document.getElementById("spinner").classList.remove("show");
    }
	
    function filterGmidDropDown(){
        var countryValue = jQuery("#CountryId").data("kendoDropDownList").value();
        var ingredientValue = jQuery("#Ingredient").data("kendoDropDownList").value();
        var gmidValue = jQuery("#GmidId").data("kendoDropDownList").value();

        let filters = [];
        if(countryValue){
            filters.push({field: "CountryId", operator: "eq", value: countryValue});
        }
        if(ingredientValue){
            filters.push({field: "Ingredient", operator: "eq", value: ingredientValue});
        }
        let gmidData = jQuery("#GmidId")
            .data("kendoDropDownList");
        gmidData.dataSource.filter(filters);
        gmidData.dataSource.read().done(function (e) {
        });
    }
</script>

<!-- Modal -->
<div class="modal fade" id="sis-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Detalle <span></span></h4>
            </div>
            <div class="modal-body">
                <table>
                    <tr>
                        <th>User</th>
                        <th>Value</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="spinner">
    <span class="glyphicon glyphicon-refresh"></span>
</div>

<div class="row">
    <div class="col-xs-12">
        <form action="">
            <div class="row">
                <div class="col-xs-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <?php $lastUrl = end($breadcrumbs); ?>
                            <?php foreach($breadcrumbs as $name => $url){ ?>
                                <?php if($lastUrl === $url){?>
                                        <li class="breadcrumb-item active">
                                            <?= $name ?>
                                        </li>
                                <?php } else { ?>
                                    <li class="breadcrumb-item">
                                        <a href="<?= $url ?>"><?= $name ?></a>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                        </ol>
                    </nav>
                </div>
            </div>
            <?php if($sisView->filters->hasDsmFilter() || $sisView->filters->hasTamFilter() || $sisView->filters->hasClientFilter()){ ?>
                <input type="hidden" name="selectedUser" value="<?= Yii::$app->request->get('selectedUser') ?>">
                <input type="hidden" name="lvl" value="<?= $sisView->filters->getFilterUserLevel() ?>">
                <?php if($sisView->filters->hasClientFilter()){ ?>
                    <input type="hidden" name="TamId" value="<?= Yii::$app->request->get('TamId') ?>">
                <?php } ?>

            <?php } ?>
            <div class="row">
                <div class="col-xs-2" id="month-select" style="max-height: 100px;overflow-y: scroll;">
                    <?php foreach($filterMonths as $month){ ?>
                        <label class="d-block">
                            <input type="checkbox" value="1" name="month_<?= $month ?>" <?= in_array($month, $sisView->getSelectedFilterMonths()) ? 'checked' : '' ?>>
                            <?= \common\models\sis\MonthTranslate::toSpanish($month) ?>
                        </label>
                    <?php } ?>
                </div>
                <div class="col-xs-2" style="max-height: 100px;overflow-y: scroll;" id="quarter-select">
                    <?php foreach($filterQuarters as $quarterName => $quarter){ ?>
                        <label class="d-block">
                            <input type="checkbox" value="1" <?= $quarter['selected'] ? 'checked' : '' ?> data-months="<?= implode('|', $quarter['months']) ?>">
                            <?= $quarterName ?>
                        </label>
                    <?php } ?>
                </div>
                <div class="col-xs-6">
                    <div class="row">

                        <div class="col-xs-2 hide" >
                            <p>Campaña</p>
                            <select name="campaign" id="campaign" class="form-control">
                                <?php
                                /** @var \common\models\Campaign[] $campaigns */
                                foreach($campaigns as $campaign){ ?>
                                    <option value="<?= $campaign->CampaignId ?>" <?= $campaign->CampaignId == SisCampaignFilter::getFilteredCampaign()->CampaignId ? 'selected' : '' ?>><?= $campaign->Name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-4">
                            <p>Pais</p>
                            <?= $dropDownCountry->render(); ?>
                        </div>
                        <div class="col-xs-4">
                            <p>Dias pedido</p>
                            <input type="number" class="form-control" name="days" id="days" step="1" min="0" value="<?= $sisView->filters->getFilterDays() ?>">
                        </div>
                        <div class="col-xs-4">
                            <p>
                                
                                <a href="<?= Url::to(['/sis/download?'. $_SERVER['QUERY_STRING']]); ?>" class="btn btn-info">Descargar XLSX</a>

                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xs-3">
                    <p>Ingrediente Activo</p>
                    <?=  $dropDownIngredient->render(); ?>
                </div>
                <div class="col-xs-3">
                    <p>Producto</p>
                    <?=  $dropDownProduct->render(); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12" style="padding-bottom: 30px;">
                    <input type="submit" value="Actualizar" class="btn btn-primary">
                </div>
            </div>
        </form>
    </div>
</div>


<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <?php if(empty($data)){ ?>
                <p class="text-center">No se encontraron registros con los filtros seleccionados.<br>
                <a href="/forecast/sis">Eliminar todos los filtros</a>
                </p>
            <?php }else{ ?>
                <?= $grid->render(); ?>
            <?php } ?>
        </div>
    </div>
</div>
<div class="row " style="margin-top: 30px;">
    <div class="col-xs-12 text-center">
        <b>&Uacute;ltima actualizaci&oacute;n</b>: <?= $lastUpdated ?><br>
        <b>&Uacute;ltima importaci&oacute;n</b>: <?= $lastImported ?>
    </div>
</div>

<div class="row " style="margin-top: 30px;">
    <div class="col-xs-8 col-xs-offset-2">
        <?php if(!empty($data)){ ?>
            <table class="table table-bordered table-striped">
                <tr>
                    <th >Importacion</th>
                    <th>Total</th>
                    <th>Diferencia reporte</th>
                </tr>
            <?php foreach ($totals->getTotals() as $total){ ?>
                <tr>
                    <td><?= $total['name'] ?> </td>
                    <td><?= $total['imported'] ?></td>
                    <td><?= $total['diff'] ?></td>
                </tr>
            <?php } ?>
            </table>
        <?php } ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="detail">

    </div>
</div>
<script>

	function buildColors(){
		jQuery('tr>td>strong').each((i, td) => {
			if($(td).text().startsWith('-')){
				$(td).addClass('text-danger');
			}
		});
		jQuery('tr>td').each((i, td) => {
			if($(td).text().startsWith('-')){
				$(td).addClass('text-danger');
			}
		});
		jQuery('.traffic-light').each((i, light) => {
			var value = parseFloat($(light).text().replace(',', '.'))*100;
			if(value <= 0.0){
				$(light).addClass('red');
			}else if(value <= 20.0){
				$(light).addClass('yellow');
			}else{
				$(light).addClass('green');
			}

		});

	}

    jQuery(function(){
        function refreshGmidDropDown() {
            jQuery("#GmidId").data("kendoDropDownList").enable(true);
            if(jQuery('#Ingredient').data("kendoDropDownList").value() === ''){
                //jQuery("#GmidId").data("kendoDropDownList").dataSource._filter = undefined;
                //jQuery("#GmidId").data("kendoDropDownList").dataSource.read()
            }
        }
        jQuery('.k-dropdown').click(function(){
            refreshGmidDropDown();
        });
    <?php

    $filters = (new SisFilters());


    if($filters->hasIngredientFilter()){ ?>
    jQuery('#Ingredient').data("kendoDropDownList").value("<?= $filters->getFilterIngredient() ?>");
    <?php }
    if($filters->hasProductFilter()){ ?>
        jQuery('#GmidId').data("kendoDropDownList").value("<?= $filters->getFilterProduct() ?>");
	<?php }
	if($filters->hasCountryFilter()){ ?>
	jQuery('#CountryId').data("kendoDropDownList").value("<?= Yii::$app->request->get(SisFilters::FILTER_COUNTRY_ID) ?>");
	<?php } ?>
        refreshGmidDropDown();

        jQuery(document).ready(function () {
            buildColors();
			document.querySelector('.k-pager-wrap').addEventListener('click', function(){buildColors()})
        });
    });
	
</script>
<script>
    var quarters = document.querySelectorAll('#quarter-select input');
    for (let quarter of quarters) {
        quarter.addEventListener('change', function(){

            const months = this.getAttribute('data-months').split('|');
            // console.log(months);
            for (let month of months) {
                // console.log(document.getElementsByName('month_' + month)[0].getAttribute('type'));
                const input = document.getElementsByName('month_' + month)[0];
                input.checked = this.checked;
            }
        })
    }

    var singleMonths = document.querySelectorAll('#month-select input');
    for (let singleMonth of singleMonths) {
        singleMonth.addEventListener('change', refreshSelectedQuarters)
    }

    function refreshSelectedQuarters() {
        var quarters = document.querySelectorAll('#quarter-select input');
        for (let quarter of quarters) {
            const months = quarter.getAttribute('data-months').split('|');
            let quarterSelected = true;
            for (let month of months) {
                const input = document.getElementsByName('month_' + month)[0];
                if(!input.checked){
                    quarterSelected = false;
                }
            }

            quarter.checked = quarterSelected;
        }
    }
</script>
<style>
    .text-danger{
        color: red !important;
    }
    .traffic-light{
        font-size: 0;
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        border: 1px solid #ccc;
    }
    .traffic-light.red{
        background-color: red;
    }
    .traffic-light.yellow{
        background-color: yellow;
    }
    .traffic-light.green{
        background-color: green;
    }
	.metadata{
        width: 100%;
        overflow-x: scroll;
    }
	
</style>