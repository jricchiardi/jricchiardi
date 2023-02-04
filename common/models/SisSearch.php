<?php

namespace common\models;

use common\models\sis\DrillLevel;
use common\models\sis\MonthTranslate;
use common\models\sis\query\SisTotals;
use common\models\sis\query\SqlContabilizacionPendiente;
use common\models\sis\query\SqlCyO;
use common\models\sis\query\SqlFacturacionPendiente;
use common\models\sis\query\SqlForecast;
use common\models\sis\query\SqlOpenOrder;
use common\models\sis\query\SqlPlan;
use common\models\sis\query\SqlRealSale;
use common\models\sis\query\SqlSaleInput;
use common\models\sis\scripts\FixOpenOrderNulls;
use common\models\sis\RoleVerify;
use common\models\sis\SisCampaignFilter;
use common\models\sis\SisFilters;
use common\models\sis\SqlBase;
use DateTime;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\Expression;
class SisSearch
{
    /**
     * @var SisFilters
     */
    public $filters;

    private $availableTables = [
        SqlSaleInput::TABLE_NAME => SqlSaleInput::class,
        SqlForecast::TABLE_NAME => SqlForecast::class,
        SqlFacturacionPendiente::TABLE_NAME => SqlFacturacionPendiente::class,
        SqlContabilizacionPendiente::TABLE_NAME => SqlContabilizacionPendiente::class,
        SqlRealSale::TABLE_NAME => SqlRealSale::class,
        SqlOpenOrder::TABLE_NAME => SqlOpenOrder::class,
        SqlCyO::TABLE_NAME => SqlCyO::class,
		SqlPlan::TABLE_NAME => SqlPlan::class,
    ];
	private $metaColumns = null;
	
    public function __construct($filters = null)
    {
        $this->filters = $filters ?? new SisFilters();
    }

    public function getResults(){
        $this->runPrevScripts();
//        var_dump($this->getSql());die;
//        $results = Yii::$app->db->createCommand($this->getSql())->cache(3600)->queryAll();
        $results = Yii::$app->db->createCommand($this->getSql())->queryAll();
        $this->addSaldos($results);
        return $results;
    }

    private function getSql(){
        $base = new SqlBase();
        $tables = $this->getTables();
        $sql = [];

        $sql[] = "SELECT";
        $selects = $base->getSelect();
        foreach ($tables as $table){
            $selects = array_merge($selects, $table->getSelect());
        }

        $sql[] = implode(', ', $selects);
        $sql[] = $base->getFrom();

//        $joins = $base->getInitialJoins();
//        foreach ($tables as $table){
//            $joins[] = (string) $table->getJoin();
//        }
//        $sql[] = implode(' ', $joins);
        $openOrders = new SqlOpenOrder();
        $sql[] = (string) $openOrders->getJoin();

        $sql[] = $this->getSqlWhere();

        $sql[] = "GROUP BY";
        $sql[] = $base->getGroupBy();

        $sql[] = "HAVING";
        $having = [];
        foreach ($tables as $table){
            $having = array_merge($having, $table->getHaving());
        }

        $sql[] = implode(' OR ', $having);

        return implode(' ', $sql) . ";";
    }
    public function getMetaData(){

        if(!$this->metaColumns){
            return [];
        }

        $gmidIds = $this->getGmidIdListFromFilter();

        $clientIds = $this->getClientListFromFilter();

        return Yii::$app->db->createCommand($this->metaColumns->getMetaTable($clientIds, $gmidIds))->queryAll();
    }

    private function getClientListFromFilter() : array
    {
        $sql = 'SELECT ClientId FROM sis_report as Report';

        $sql .= $this->getSqlWhere();

        $sql .= " GROUP BY ClientId";
        $results =  Yii::$app->db->createCommand($sql)->queryAll();

        $clientIds = [];
        foreach($results as $result){
            $clientIds[$result['ClientId']] = $result['ClientId'];
        }
        return array_values($clientIds);
    }

    private function getGmidIdListFromFilter() : array
    {

        $sql = 'SELECT GmidId FROM sis_report as Report';

        $sql .= $this->getSqlWhere();

        $sql .= " GROUP BY GmidId";
        $results =  Yii::$app->db->createCommand($sql)->queryAll();

        $gmidIds = [];
        foreach($results as $result){
            $gmidIds[$result['GmidId']] = $result['GmidId'];
        }
        return array_values($gmidIds);
    }
	
    private function getSqlWhere(){

        $sql = " WHERE 1=1 ";
        if($this->filters->hasDsmFilter()){
            $sql .= " AND DsmUserId = ".(int)$this->filters->getFilterUser();
        }
        if($this->filters->hasTamFilter()){
            $sql .= " AND TamUserId = ".(int)$this->filters->getFilterUser();
        }
        if($this->filters->hasClientFilter()){
            $sql .= " AND TamUserId = ".(int)$this->filters->getFilterUserTam();
            $sql .= " AND Report.ClientId = ".(int)$this->filters->getFilterUser();
        }
        if($this->filters->hasProductFilter()){
            $sql .= " AND Report.GmidId = ".(int)$this->filters->getFilterProduct();
        }
        if($this->filters->hasIngredientFilter()){
            $sql .= sprintf(" AND Report.Ingredient = '%s'", $this->filters->getFilterIngredient());
        }
        if($this->filters->hasCountryFilter()){
            $sql .= " AND Report.CountryId = ".(int)$this->filters->getFilterCountry();
        }
        $sql .= " AND Report.CampaignId = ".(int)SisCampaignFilter::getFilteredCampaign()->CampaignId;

        return $sql;
    }

    private function getTables(){
        $tables = [];
        foreach ($this->availableTables as $column => $table){
            if(!$this->filters->selectedColumn($column)){
                continue;
            }
            if($this->filters->getFilterColumn()){
                if(in_array($this->filters->getFilterColumn(), ['Pedidos','PedidosFuturos', 'ContPendiente', 'FactPendiente'])){
//                if(in_array($this->filters->getFilterColumn(), ['FactPendiente'])){
                    $this->metaColumns = new $table;
                }
            }
            $tables[] = new $table;
        }
        return $tables;
    }


    private function addSaldos(&$results)
    {
		
        if($this->filters->hasFilterColumn()){
            return;
        }
        $totalToDiscount = 0;
        $totalToAdd = 0;
        foreach ($results as &$result){
            $result['SaldoParaIngresar'] = $result['Forecast'] - $result['FactPendiente'] - $result['ContPendiente'] - $result['RealSale'] - $result['Pedidos'] - $result['PedidosFuturos'] - $result['CyO'];
            $result['SaldoParaDespacho'] = $result['Forecast'] - $result['FactPendiente'] - $result['ContPendiente'] - $result['RealSale'] - $result['CyO'];
            $result['SaldoParaDespachoPerc'] = ($result['Forecast']!=0) ? $result['SaldoParaDespacho'] / $result['Forecast'] : 0;
            $result['SaldoAjustado'] = 0;
            $result['SaldoAjustadoPerc'] = 0;
            if($result['SaldoParaDespacho'] > 0){
                $totalToAdd += $result['SaldoParaDespacho'];
            }else{
                $totalToDiscount -= $result['SaldoParaDespacho'];
            }
        }
        if($totalToAdd == 0 || $totalToDiscount == 0){
            return;
        }

        $realToDiscount = $totalToDiscount;
        if($totalToDiscount > $totalToAdd){
            $realToDiscount = $totalToAdd;
        }
        $netValue = $totalToAdd - $totalToDiscount;
        if($netValue == 0){
            return;
        }
		
        foreach ($results as &$result){

            if($result['SaldoParaDespacho'] > 0){
                $atribution = $result['SaldoParaDespacho'] / $totalToAdd;
                $percSign = 1;
            }else {
                $atribution = $result['SaldoParaDespacho'] / $totalToDiscount; //-
                $percSign = -1;
            }
            $diff = $atribution * $realToDiscount;
            $result['SaldoAjustado'] = $result['SaldoParaDespacho'] - $diff;
            $result['SaldoAjustadoPerc'] = $percSign * ($result['SaldoAjustado'] / $netValue);

        }
    }

    private function runPrevScripts(){
        FixOpenOrderNulls::run();
		$roleVerify = new RoleVerify($this->filters);
        $roleVerify->validate();

    }

    public function getLastUpdated() : string
    {
        $data = SisMetadata::find()
            ->where(['code' => 'last_exec'])
            ->limit(1)->asArray()->all();

        if(empty($data)){
            return '';
        }

        return (new DateTime($data[0]['value']))->format('d/m/Y H:i:s');
    }

    public function getLastImported() : string
    {
        $data = Import::find()
            ->where(['FinishedCorrectly' => 1])
            ->orderBy('CreatedAt DESC')
            ->limit(1)->asArray()->all();

        if(empty($data)){
            return '';
        }

        return (new DateTime($data[0]['CreatedAt']))->format('d/m/Y H:i:s');
    }

    public function getTotals($data) : SisTotals
    {
        return new SisTotals($data);
    }

}
