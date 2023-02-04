<?php

namespace common\models\sis\query;

use common\models\sis\CountryTranslate;
use common\models\sis\HasSaleMonth;
use common\models\sis\SisCampaignFilter;
use common\models\sis\SisFilters;
use common\models\sis\SisSql;
use common\models\sis\SisSqlJoin;
use Exception;
use Yii;

class SisTotals
{
    use HasMonthlySelect;

    const TABLE_NAME = 'Forecast';

    public $data = [
        'Forecast' => [
            'imported'=> 0,
            'name' => 'Forecast S&OP',
            'diff' => 0,
        ],
        'FactPendiente' => [
            'imported'=> 0,
            'name' => 'Facturacion Pendiente',
            'diff' => 0,
        ],
        'ContPendiente' => [
            'imported'=> 0,
            'name' => 'Contabilizacion Pendiente',
            'diff' => 0,
        ],
        'Pedidos' => [
            'imported'=> 0,
            'name' => 'Pedidos',
            'diff' => 0,
        ],
    ];

    private $filters;
    private $sisResults;

    public function __construct($data)
    {
        $this->sisResults = $data;
        $this->filters = $filters ?? new SisFilters();
    }

    private function getSisTotal($column){
        $total = 0;
        foreach ($this->sisResults as $result) {
            $total += $result[$column];
        }
        return $total;
    }


    public function getMonthSelect() : string
    {
        $sumColumns = '0';
        foreach ($this->getSaleInputMonths() as $saleInputMonth) {
            $sumColumns .= sprintf('+SUM(COALESCE(%s,0))', $saleInputMonth);
        }
        return $sumColumns;
    }

    public function getTotals() : array
    {
        try{
            $total = $this->getForecastIbp();
            $diff = $this->getSisTotal('Forecast') - $total;
            $this->data['Forecast']['diff'] = ($diff>0) ? '+' . number_format($diff) : number_format($diff);
            $this->data['Forecast']['imported'] = number_format($total);
        }catch (Exception $e){
            $this->data['Forecast']['imported'] = 'No calculable según filtros';
            $this->data['Forecast']['diff'] = 'No calculable según filtros';
        }

        try{
            $total = $this->getFactPendiente();
            $diff = $this->getSisTotal('FactPendiente') - $total;
            $this->data['FactPendiente']['diff'] = ($diff>0) ? '+' . number_format($diff) : number_format($diff);
            $this->data['FactPendiente']['imported'] = number_format($total);
        }catch (Exception $e){
            $this->data['FactPendiente']['imported'] = 'No calculable según filtros';
            $this->data['FactPendiente']['diff'] = 'No calculable según filtros';
        }

        try{
            $total = $this->getContPendiente();
            $diff = $this->getSisTotal('ContPendiente') - $total;
            $this->data['ContPendiente']['diff'] = ($diff>0) ? '+' . number_format($diff) : number_format($diff);
            $this->data['ContPendiente']['imported'] = number_format($total);
        }catch (Exception $e){
            $this->data['ContPendiente']['imported'] = 'No calculable según filtros';
            $this->data['ContPendiente']['diff'] = 'No calculable según filtros';
        }

        try{
            $total = $this->getPedidos();
            $diff = $this->getSisTotal('Pedidos') + $this->getSisTotal('PedidosFuturos') - $total;
            $this->data['Pedidos']['diff'] = ($diff>0) ? '+' . number_format($diff) : number_format($diff);
            $this->data['Pedidos']['imported'] = number_format($total);
        }catch (Exception $e){
            $this->data['Pedidos']['imported'] = 'No calculable según filtros';
            $this->data['Pedidos']['diff'] = 'No calculable según filtros';
        }



        //$this->data['FactPendiente'] = $this->getSisTotal('FactPendiente') - $this->getForecastIbp();
        return $this->data;
    }

    private function getForecastIbp() : float
    {
        if($this->filters->getFilterUser()){
            throw new Exception('Not calculable');
        }

        $sql = sprintf('SELECT %s as count
                FROM FCASTIBP f 
                WHERE 1=1', $this->getMonthSelect());

        if($this->filters->hasCountryFilter()) {
            $countryShort = CountryTranslate::toShort(Yii::$app->request->get(SisFilters::FILTER_COUNTRY_ID));
            $sql .= sprintf(' AND ShipToCountry = \'%s\'', $countryShort);
        }

        if($this->filters->hasIngredientFilter()) {
            $sql .= sprintf(' AND Ingredient = \'%s\'', $this->filters->getFilterIngredient());
        }

        if($this->filters->hasProductFilter()) {
            $sql .= sprintf(' AND ' . Convert::materialCodeToGmid('OldProductID') . ' = \'%s\'', $this->filters->getFilterProduct());
        }

        $results = Yii::$app->db->createCommand($sql)->queryAll();
        return (int)$results[0]['count'] ?? 0;

    }

    private function getFactPendiente() : float
    {
        $sql = 'SELECT
            SUM((CASE WHEN SalesDocType IN (\'ZRE\', \'ZARE\') THEN -1 ELSE 1 END)* DeliveryQ) as count
        FROM
            DESPNOFC
            WHERE 1=1 ';

        if($this->filters->hasProductFilter()) {
            $sql .= sprintf(' AND ' . Convert::materialCodeToGmid('MaterialCode') . ' = \'%s\'', $this->filters->getFilterProduct());
        }elseif ($this->filters->hasIngredientFilter()){
            throw new Exception('Not calculable');
        }

        if($this->filters->hasClientFilter()) {
            $sql .= sprintf(' AND ' . Convert::int('SoldToCustNumber') . ' IN (SELECT SoldToParty FROM unificacion_cliente WHERE ConversionCode = \'%s\') ', $this->filters->getFilterUser());
        }elseif ($this->filters->hasDsmFilter() || $this->filters->hasTamFilter()){
            throw new Exception('Not calculable');
        }

        $results = Yii::$app->db->createCommand($sql)->queryAll();

        return (int)$results[0]['count'] ?? 0;
    }

    private function getContPendiente() : float
    {
        $sql = '
            SELECT
                SUM((CASE WHEN BillingType IN (  \'ZRE\', \'ZARE\' ) THEN -1 ELSE 1 END)* BilledQ) as count
            FROM FCNOCONT
            WHERE 1=1 ';

        if($this->filters->hasProductFilter()) {
            $sql .= sprintf(' AND ' . Convert::materialCodeToGmid('MaterialCode') . ' = \'%s\'', $this->filters->getFilterProduct());
        }elseif ($this->filters->hasIngredientFilter()){
            throw new Exception('Not calculable');
        }

        if($this->filters->hasClientFilter()) {
            $sql .= sprintf(' AND ' . Convert::int('SoldToPartyNumber') . ' IN (SELECT SoldToParty FROM unificacion_cliente WHERE ConversionCode = \'%s\') ', $this->filters->getFilterUser());
        }elseif ($this->filters->hasDsmFilter() || $this->filters->hasTamFilter()){
            throw new Exception('Not calculable');
        }

        $results = Yii::$app->db->createCommand($sql)->queryAll();
        return (int)$results[0]['count'] ?? 0;
    }

    private function getPedidos() : float
    {

        $sql = sprintf('
            SELECT 
                SUM(OpenQConfirmedQ) as count
            FROM OPENORDERS
            WHERE 1=1
            ');

        if($this->filters->hasProductFilter()) {
            $sql .= sprintf(' AND ' . Convert::materialCodeToGmid('MaterialCode') . ' = \'%s\'', $this->filters->getFilterProduct());
        }elseif ($this->filters->hasIngredientFilter()){
            throw new Exception('Not calculable');
        }

        if($this->filters->hasClientFilter()) {
            $sql .= sprintf(' AND ' . Convert::int('SoldToCustNumber') . ' IN (SELECT SoldToParty FROM unificacion_cliente WHERE ConversionCode = \'%s\') ', $this->filters->getFilterUser());
        }elseif ($this->filters->hasDsmFilter() || $this->filters->hasTamFilter()){
            throw new Exception('Not calculable');
        }

        $results = Yii::$app->db->createCommand($sql)->queryAll();
        return (int)$results[0]['count'] ?? 0;
    }

}