<?php

namespace common\models\sis\query;

class Convert
{
    static function int($column){
        return sprintf('CASE WHEN IsNumeric(%s)=1 THEN CONVERT(INT, %s) ELSE null END', $column, $column);
    }

    static function replace($column, $with){
        return sprintf('REPLACE(%s, \'%s\',\'\')', $column, $with);
    }

    static function materialCodeToGmid($column){
        return self::int(self::replace($column,'D'));
    }
}