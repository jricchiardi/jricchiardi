<?php

namespace common\models\sap;

use Exception;

class DasSale extends Sale
{
    /**
     * @param array $data
     * @param $campaignId
     * @param $importId
     * @param int $rowNumber
     * @return Elemento
     * @throws Exception
     */
    public static function fromCSV(array $data, $campaignId, $importId, int $rowNumber)
    {
        if (!self::array_keys_exist($data, '1', '3', '5', '6', '7', '9', '10')) {
            throw new Exception("Some element is missing");
        }

        $netSales = floatval($data[1]);
        if (preg_match("/-$/", $data[1])) {
            $netSales = -1 * $netSales;
        }

        $volume = floatval($data[3]);
        if (preg_match("/-$/", $data[3])) {
            $volume = -1 * $volume;
        }

        $country = $data[5];
        $cliente = $data[6];
        $gmid = intval($data[7]);
        $calendarYear = intval($data[9]);
        $calendarMonth = intval($data[10]);

        return new self(
            $rowNumber,
            $country,
            $gmid,
            $calendarYear,
            $calendarMonth,
            $netSales,
            $volume,
            $importId,
            $cliente
        );
    }
}
