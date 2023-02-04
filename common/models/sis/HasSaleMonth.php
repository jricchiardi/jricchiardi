<?php

namespace common\models\sis;

use Yii;

trait HasSaleMonth
{

    private function getSaleInputMonths(){
        return $this->getSelectedFilterMonths();
    }

    public function getSelectedFilterMonths(){
        $cleanMonths = [];

        foreach(SisFilters::$filterMonths as $month){
            if(Yii::$app->request->get('month_'.$month)){
                $cleanMonths[] = $month;
            }
        }

        return !empty($cleanMonths) ? $cleanMonths : SisFilters::$filterMonths;
    }


    private function getSaleInputMonthsNumber(){
        $months = [];

        foreach($this->getSaleInputMonths() as $inputMonth){
            $months[] = MonthTranslate::getMonthNumber($inputMonth);
        }

        return $months;
    }
}