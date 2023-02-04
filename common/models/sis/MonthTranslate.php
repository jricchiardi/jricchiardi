<?php

namespace common\models\sis;

class MonthTranslate
{
    static $monthNumbers = [
        'January' => 1,
        'February' => 2,
        'March' => 3,
        'April' => 4,
        'May' => 5,
        'June' => 6,
        'July' => 7,
        'August' => 8,
        'September' => 9,
        'October' => 10,
        'November' => 11,
        'December' => 12,
    ];
    static $monthToSpanish = [
        'January' => "Enero",
        'February' => "Febrero",
        'March' => "Marzo",
        'April' => "Abril",
        'May' => "Mayo",
        'June' => "Junio",
        'July' => "Julio",
        'August' => "Agosto",
        'September' => "Septiembre",
        'October' => "Octubre",
        'November' => "Noviembre",
        'December' => "Diciembre",
    ];

    public static function getMonthNumber($monthText){
        return self::$monthNumbers[$monthText] ?? 1;
    }

    public static function toSpanish(string $monthText)
    {
        return self::$monthToSpanish[$monthText] ?? $monthText;
    }

    public static function getQuarter(string $month)
    {
        $numMonth = MonthTranslate::getMonthNumber($month);
        return 'Q' . ceil($numMonth / 3);
    }
}