<?php

namespace common\models\sap;

use Exception;

class DupontCyo extends Cyo
{
    public $clientName;
    public $gmidDescription;

    /**
     * @param array $data
     * @param $campaignId
     * @param $importId
     * @param int $rowNumber
     * @return Elemento
     */
    public static function fromCSV(array $data, $campaignId, $importId, int $rowNumber)
    {
        if (!self::array_keys_exist($data, '1', '2', '4', '5', '10')) {
			var_dump($data);die;
            throw new Exception("Some element is missing");
        }

        $cliente = $data[1];
        $gmid = preg_replace("/[^0-9]/", "", $data[4]);
        $inventoryBalance = floatval(preg_replace('/[^0-9]/', '', $data[10])) / 1000;

        $el = new self(
            $rowNumber,
            $inventoryBalance,
            $gmid,
            $campaignId,
            $importId,
            $cliente
        );

        $el->clientName = $data[2];
        $el->gmidDescription = $data[5];

        return $el;
    }
}
