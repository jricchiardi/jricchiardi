<?php

namespace common\models\sis;

class SisSqlJoin
{
    const FORMAT_WITH_BRACKETS = '%s (%s) AS %s ON %s';
    const FORMAT_WITHOUT_BRACKETS = '%s %s AS %s ON %s';

    public $table;
    public $tableName;
    public $union = 'LEFT JOIN ';
    public $on;
    public $format = self::FORMAT_WITH_BRACKETS;

    static function left($table, $tableName, $on, $useBrackets = true){
        $leftJoin = new self;

        $leftJoin->table = $table;
        $leftJoin->tableName = $tableName;
        $leftJoin->format = ($useBrackets) ? self::FORMAT_WITH_BRACKETS : self::FORMAT_WITHOUT_BRACKETS;
        $leftJoin->on = $on;
        $leftJoin->union = 'LEFT JOIN';

        return $leftJoin;
    }

    static function inner($table, $tableName, $on, $useBrackets = true){
        $innerJoin = new self;

        $innerJoin->table = $table;
        $innerJoin->tableName = $tableName;
        $innerJoin->format = ($useBrackets) ? self::FORMAT_WITH_BRACKETS : self::FORMAT_WITHOUT_BRACKETS;
        $innerJoin->on = $on;
        $innerJoin->union = 'INNER JOIN';

        return $innerJoin;
    }

    public function __toString()
    {
        return sprintf($this->format, $this->union, $this->table, $this->tableName, $this->on);
    }
}