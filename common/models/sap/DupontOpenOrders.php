<?php

namespace common\models\sap;

use Exception;
use DateTime;

class DupontOpenOrders extends OpenOrders
{
    public $clientName;
    public $orderNumber;

    /**
     * @param array $data
     * @param $campaignId
     * @param $importId
     * @param int $rowNumber
     * @return Elemento
     */
   public static function fromCSV(array $data, $OrderNo, $SoldToCustName, int $rowNumber)
    {
        if (!self::array_keys_exist($data, '2', '7')) {
            throw new Exception("Some element is missing");
	       }
		   
	
        $SalesOrg = $data[0];
        $Item = $data[1];
        $OrderType = $data[5];
        $OrderNo = $data[2];
        $DelivNo = $data[3];
        $CredBlock = $data[4];
        $SoldToCustNumber = $data[6];
        $SoldToCustName = $data[7];
        $MaterialCode = $data[8];
        $MaterialDescript = $data[9];  
        $PlantCode = $data[10];
        $OpenQConfirmedQ = floatval ($data[11]); 
        $OrderQ = floatval ($data[12]);
        $SalesUoM = $data[13];
        $ConfirmedDelvDate = !empty($data[14]) ? date_format ($data[14], 'd/m/Y'):"";
        $ShipToCustNumber = $data[15];
        $ShipToCustName = $data[16];
        $CustPurchaseOrdNo = $data[17];
        $ConfirmedShipDate = !empty($data[18]) ? date_format ($data[18], 'd/m/Y'):"";        

        $el = new self(
        $rowNumber,
        $SalesOrg,
        $Item,
        $OrderNo,
        $DelivNo,
        $CredBlock,
        $OrderType,	
        $SoldToCustNumber,
        $SoldToCustName,
        $MaterialCode,
        $MaterialDescript,  
        $PlantCode,
        $OpenQConfirmedQ, 
        $OrderQ,
        $SalesUoM,
        $ConfirmedDelvDate,
        $ShipToCustNumber,
        $ShipToCustName,
        $CustPurchaseOrdNo,
        $ConfirmedShipDate
        );

        $el->clientName = $data[7];
        $el->orderNumber = $data[2];

        return $el;
    }
}
