<?php

namespace common\models\sis;

use common\models\Gmid;
use common\models\Ingredient;
use common\models\TradeProduct;
use Yii;

class SisDropdown
{

    static function getIngredients()
    {

        $tradeProducts = Ingredient::find()->select('Ingredient')->groupBy('Ingredient')->orderBy('Ingredient')->asArray()->all();

        foreach($tradeProducts as &$tradeProduct){
            if(strlen($tradeProduct['Ingredient']) == strlen((int)$tradeProduct['Ingredient'])){
                $tradeProduct['Ingredient'] = 'AI'.$tradeProduct['Ingredient'];
            }
        }
        $dropDownIngredient = new \Kendo\UI\DropDownList('Ingredient');
        $dropDownIngredient->dataSource($tradeProducts)
            ->filter("contains")
            ->autoBind(false)
            ->change('filterGmidDropDown')
            ->dataTextField('Ingredient')
            ->dataValueField('Ingredient')
            ->optionLabel('Todos los Ingredientes Activos')
            ->attr('style', 'width:100%');

        $filters = (new SisFilters());

        if($filters->hasIngredientFilter()){
            $dropDownIngredient->value($filters->getFilterIngredient());
        }

        return $dropDownIngredient;
    }

    static function getProduct()
    {
        $products = Gmid::find()
            ->select('Gmid.GmidId, Gmid.Description, Ingredient.Ingredient, Ingredient.CountryCode as CountryId')
            ->innerJoin('vw_gmid_ingredient_country Ingredient', 'Ingredient.GmidId = Gmid.GmidId')
            ->groupBy(['Gmid.GmidId', 'Gmid.Description', 'Ingredient.Ingredient', 'Ingredient.CountryCode'])
            ->orderBy('Description')->asArray()->all();
        foreach($products as &$product){
            if(strlen($product['Description']) == strlen((int)$product['Description'])){
                $product['Description'] = 'GMID:'.$product['Description'];
            }
        }

        $dropDownProduct = new \Kendo\UI\DropDownList('GmidId');
        $dropDownProduct->dataSource($products)
            ->filter("contains")
            ->autoBind(false)
            ->dataTextField('Description')
            ->dataValueField('GmidId')
            ->optionLabel('Todos los productos')
            ->attr('style', 'width:100%');

        $filters = (new SisFilters());

        if($filters->hasProductFilter()){
            $dropDownProduct->value($filters->getFilterProduct());
        }

        return $dropDownProduct;
    }

    public static function getCountry()
    {

        $countries = Ingredient::find()->select('CountryCode')->groupBy('CountryCode')->orderBy('CountryCode')->asArray()->all();
        foreach($countries as &$country){
            $realCountry = CountryTranslate::toCountry($country['CountryCode']);
            $country = [
                'CountryId' => $country['CountryCode'],
                'Description' => $realCountry->Description
            ];
        }

        $dropDownCountry = new \Kendo\UI\DropDownList('CountryId');
        $dropDownCountry->dataSource($countries)
            ->filter("contains")
            ->autoBind(false)
            //->change('()=>{jQuery("#GmidId").data("kendoDropDownList").enable(true);}')
            ->dataTextField('Description')
            ->dataValueField('CountryId')
            ->optionLabel('Todos los paises')
            ->cascade('filterGmidDropDown')
            ->attr('style', 'width:100%');

        $filters = (new SisFilters());

        if($filters->hasCountryFilter()){
            $dropDownCountry->value(Yii::$app->request->get(SisFilters::FILTER_COUNTRY_ID));
        }

        return $dropDownCountry;
    }

}