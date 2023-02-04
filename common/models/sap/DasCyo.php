<?php

namespace common\models\sap;

use Exception;

class DasCyo extends Cyo
{
    public $country;

    /**
     * @param array $data
     * @param $campaignId
     * @param $importId
     * @param int $rowNumber
     * @return Elemento
     */
    public static function fromCSV(array $data, $campaignId, $importId, int $rowNumber)
    {
        if (!self::array_keys_exist($data, '0', '2', '4', '6')) {
            throw new Exception("Some element is missing");
        }

        $inventoryBalance = floatval(preg_replace('/[^0-9]/', '', $data[0])) / 1000;
        $cliente = $data[4];
        $gmid = intval($data[6]);

        $el = new self(
            $rowNumber,
            $inventoryBalance,
            $gmid,
            $campaignId,
            $importId,
            $cliente
        );

        $el->country = $data[2];

        return $el;
    }
}
