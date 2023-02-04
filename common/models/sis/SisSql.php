<?php

namespace common\models\sis;

abstract class SisSql
{
    public function getSelect() : array
    {
        return [
            sprintf('SUM(COALESCE(%s,0)) AS %s',static::TABLE_NAME, static::TABLE_NAME)
        ];
    }

    public function getJoin() : SisSqlJoin
    {
        return new SisSqlJoin();
    }

    public function groupBy() : array
    {
        return [];
    }

    public function getHaving() : array
    {
        return [
            sprintf('SUM(COALESCE(%s,0)) != 0', static::TABLE_NAME),
        ];
    }
}