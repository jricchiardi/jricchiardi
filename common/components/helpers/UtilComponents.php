<?php

namespace common\components\helpers;

use common\models\Campaign;
use common\models\LockForecast;
use common\models\LockForecastMarketing;
use common\models\Setting;
use DateTime;
use Yii;
use yii\base\Component;

class UtilComponents extends Component
{
    public function existOrCero($value)
    {
        return isset($value) ? $value : 0;
    }

    public function getOneSetting($name)
    {
        return Setting::findOne(['Name' => $name]);
    }

    public function lockTable($tableName)
    {
        Yii::$app->db->createCommand("LOCK TABLES {$tableName} READ;");
    }

    public function unLockTable()
    {
        Yii::$app->db->createCommand("UNLOCK TABLES {$tableName}");
    }

    public function isLock()
    {
        return $this->checkIfAreCurrentLocks(
            LockForecast::find()->asArray()->all()
        );
    }

    public function isMarketingForecastLocked()
    {
        return $this->checkIfAreCurrentLocks(
            LockForecastMarketing::find()->asArray()->all()
        );
    }

    public function LastMonthDay()
    {
        $month = date('m');
        $year = date('Y');
        $day = date("d", mktime(0, 0, 0, $month + 1, 0, $year));

        return date('d', mktime(0, 0, 0, $month, $day, $year));
    }

    /* ACTIVE OR DISABLE COLUMN FROM GRID KENDO DEPENDING THE ACTUAL MONTH */
    public function isColumnActive($column)
    {
        $properties = $column->properties();
        $monthActual = Setting::getValue(Setting::FORECAST_ENABLE_FROM);
        $isLock = $this->isLock();

        if (($this->getNumberMonth($properties["field"]) < $monthActual) || ($isLock)) {
            $column->attributes(['style' => 'background-color:\#DCDEE4;color:\#666;font-weight:500;border-bottom:1px solid \#CCC;']);
            $column->editor("function (e) {                              
                             $('#forecast').data('kendoGrid').closeCell();}
                            ");
        }
    }

    /* ACTIVE OR DISABLE COLUMN FROM GRID KENDO DEPENDING THE ACTUAL MONTH */
    public function isColumnActiveForecastMarketing($column)
    {
        $properties = $column->properties();
        $monthActual = Setting::getValue(Setting::FORECAST_ENABLE_FROM);
        $isLock = $this->isMarketingForecastLocked();

        if (($this->getNumberMonth($properties["field"]) < $monthActual) || ($isLock)) {
            $column->attributes(['style' => 'background-color:\#DCDEE4;color:\#666;font-weight:500;border-bottom:1px solid \#CCC;']);
            $column->editor("function (e) {                              
                             $('#forecast').data('kendoGrid').closeCell();}
                            ");
        }
    }

    /* ACTIVE OR DISABLE COLUMN FROM PLAN */
    public function enableOrDisableColumnsPlan($columns)
    {
        $today = new DateTime("now");
        $datesEnable = Campaign::find()->where(['IsActual' => true])->asArray()->one();
        $from = new DateTime($datesEnable["PlanDateFrom"]);
        $to = new DateTime($datesEnable["PlanDateTo"]);
        $isLock = true;
        if ($from <= $today && $to >= $today) {
            $isLock = false;
        }

        foreach ($columns as $column) :
            $properties = $column->properties();
            if ($isLock) {
                $column->attributes(['style' => 'background-color:\#DCDEE4;color:\#666;font-weight:500;border-bottom:1px solid \#CCC;']);
                $column->editor("function (e) {                              
                             $('#plan').data('kendoGrid').closeCell();}
                            ");
            }
        endforeach;
    }

    public function isPlanEnable()
    {
        $today = new DateTime("now");
        $datesEnable = Campaign::find()->where(['IsActual' => true])->asArray()->one();
        $from = new DateTime($datesEnable["PlanDateFrom"]);
        $to = new DateTime($datesEnable["PlanDateTo"]);
        $isLock = true;
        if ($from <= $today && $to >= $today) {
            return false;
        }
        return true;
    }

    /* RETURN TRUE OR FALSE DEPENDING THE MONTH ACTUAL */
    public function isMonthActive($month)
    {
        $isLock = $this->isLock();
        $monthActual = Setting::getValue(Setting::FORECAST_ENABLE_FROM);
        if (!is_null($this->getNumberMonth($month)) || $isLock) {
            if ($this->getNumberMonth($month) >= $monthActual)
                return true;
            else
                return false;
        } else
            return true;
    }

    /* GET THE NUMBER OF MONTH BY NAME */
    public function getNumberMonth($month)
    {
        $months = array('January' => 1, 'February' => 2, 'March' => 3, 'April' => 4, 'May' => 5, 'June' => 6, 'July' => 7, 'August' => 8, 'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12);
        if (isset($months[$month]))
            return (int)$months[$month];
        else
            return NULL;
    }

    /* GET MONTH IN SPAN */
    public function getMonthES($number)
    {
        $months = array(1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre');
        return ($months[$number]);
    }

    /* GET MONTH */
    public function getMonth($number)
    {
        $months = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');
        return ($months[$number]);
    }

    public function getMonthAbr($number)
    {
        $months = array(1 => 'ene', 2 => 'feb', 3 => 'mar', 4 => 'abr', 5 => 'may', 6 => 'jun', 7 => 'jul', 8 => 'ago', 9 => 'sep', 10 => 'oct', 11 => 'nov', 12 => 'dic');
        return ($months[$number]);
    }

    public function getAmountQuarter($month)
    {
        $quarter = 0;
        if ($month >= 4 && $month <= 6)
            $quarter = 1;
        elseif ($month >= 7 && $month <= 9)
            $quarter = 2;
        elseif ($month >= 10 && $month <= 12)
            $quarter = 3;
        return $quarter;
    }

    public function _getNumberQuarter($month)
    {
        $quarter = 0;
        if ($month < 4)
            $quarter = 1;
        elseif ($month < 7)
            $quarter = 2;
        elseif ($month < 10)
            $quarter = 3;
        elseif ($month <= 12)
            $quarter = 4;
        return $quarter;
    }

    private function checkIfAreCurrentLocks(array $locks)
    {
        $today = new DateTime("now");
        $isLock = false;
        foreach ($locks as $lock) {
            $from = new DateTime($lock["DateFrom"]);
            $to = new DateTime($lock["DateTo"]);
            if ($from <= $today && $to >= $today) {
                $isLock = true;
            }
        }
        return $isLock;
    }
}
