<?php

namespace common\models\sis;

use common\models\User;
use Yii;

class SisFilters
{
    use HasSaleMonth;

    const FILTER_LEVEL = 'lvl';
    const FILTER_SELECTED_USER = 'selectedUser';
    const FILTER_TAM_ID = 'TamId';
    const FILTER_COUNTRY_ID = 'CountryId';
    const FILTER_PRODUCT = 'GmidId';
    const FILTER_INGREDIENT = 'Ingredient';
    const FILTER_DAYS = 'days';
    const FILTER_COLUMN = 'column';
	
    static $filteredCountryId = null;

    static $filterMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    public function getFilterUserLevel(){
        return Yii::$app->request->get(self::FILTER_LEVEL) ?? UserLevel::FIRST;
    }

    public function hasDsmFilter(){
        return $this->getFilterUserLevel()===UserLevel::TAM && !empty($this->getFilterUser());
    }

    public function hasTamFilter(){
        return $this->getFilterUserLevel()===UserLevel::CLIENT && !empty($this->getFilterUser());
    }

    public function hasClientFilter(){
        return $this->getFilterUserLevel()===UserLevel::PRODUCT && !empty($this->getFilterUser());
    }

    public function getFilterUser(){
        return (int)Yii::$app->request->get(self::FILTER_SELECTED_USER) ?? null;
    }

    public function getFilterUserTam(){
        return (int)Yii::$app->request->get(self::FILTER_TAM_ID) ?? null;
    }

    public function getFilteredUser(){
        return User::findIdentity($this->getFilterUser());
    }

    public function hasCountryFilter(){
        return !empty(Yii::$app->request->get(self::FILTER_COUNTRY_ID));
    }

    public function getFilterCountry(){
        if($this->hasCountryFilter()){
            return $this->getFilterCountryFromCode();
        }
        return null;
    }

    private function getFilterCountryFromCode(){
        if(empty(self::$filteredCountryId)){
            $countryCode = Yii::$app->request->get(self::FILTER_COUNTRY_ID);
            $country = CountryTranslate::toCountry($countryCode);
            self::$filteredCountryId = (int)$country->CountryId;
        }
        return self::$filteredCountryId;
    }

    public function hasProductFilter(){
        return !empty($this->getFilterProduct());
    }

    public function getFilterProduct(){
        return Yii::$app->request->get(self::FILTER_PRODUCT) ?? null;
    }

    public function hasIngredientFilter(){
        return !empty($this->getFilterIngredient());
    }

    public function getFilterIngredient(){
        return Yii::$app->request->get(self::FILTER_INGREDIENT) ?? null;
    }

    public function getFilterDays(){
        return Yii::$app->request->get(self::FILTER_DAYS) ?? 10;
    }

    public function getFilterMonths(){
        return self::$filterMonths;
    }

    public function getFilterColumn(){
        return Yii::$app->request->get(self::FILTER_COLUMN);
    }

    public function hasFilterColumn(){
        return !empty($this->getFilterColumn());
    }

    public function selectedColumn($column){
        if(!$this->hasFilterColumn()){
            return true;
        }

        if($column === 'OpenOrders'){
            return in_array($this->getFilterColumn(), ['Pedidos','PedidosFuturos']);
        }

        return $this->getFilterColumn() == $column;
    }

    public function getFilterQuarters()
    {
        $quarters = [];

        foreach(self::$filterMonths as $filterMonth){
            $quarter = MonthTranslate::getQuarter($filterMonth);

            if(empty($quarters[$quarter])){
                $quarters[$quarter] = [
                    'months' => [],
                    'selected' => false,
                ];
            }

            $quarters[$quarter]['months'][] = $filterMonth;
        }

        foreach($quarters as &$quarter){
            foreach($quarter['months'] as $month){
                if(!in_array($month, $this->getSelectedFilterMonths())){
                    continue 2;
                }
            }
            $quarter['selected'] = true;
        }

        return $quarters;
    }
}