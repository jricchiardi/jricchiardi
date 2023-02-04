<?php

namespace common\models\sis\query;

use common\models\sis\HasSaleMonth;

trait HasMonthlySelect
{
    use HasSaleMonth;

    public function getSelect() : array
    {

        $sumColumns = '0';
        foreach ($this->getSaleInputMonths() as $saleInputMonth) {
            $sumColumns .= sprintf('+SUM(COALESCE(%s%s,0))', self::TABLE_NAME, $saleInputMonth);
        }
        return [
            sprintf('%s AS "%s"',$sumColumns, self::TABLE_NAME)
        ];
    }

    public function getHaving() : array
    {
        $cols = [];
        foreach ($this->getSaleInputMonths() as $saleInputMonth) {
            $cols[] = sprintf('+SUM(COALESCE(%s%s,0)) != 0', self::TABLE_NAME, $saleInputMonth);
        }
        return $cols;
    }
}