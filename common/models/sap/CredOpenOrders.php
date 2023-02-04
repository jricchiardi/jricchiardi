<?php

namespace common\models\sap;

use Exception;
use DateTime;

class CredOpenOrders extends OpenOrders
{
    public $clientNumber;
    public $orderNumber;

    /**
     * @param array $data
     * @param $campaignId
     * @param $importId
     * @param int $rowNumber
     * @return Elemento
     */
   public static function fromCSV(array $data, $OrderNo, $SoldToCustNumber, int $rowNumber)
    {
        if (!self::array_keys_exist($data, '1', '0')) {
            throw new Exception("Some element is missing");
	       }
		   
	
        //$SalesOrg = $data[14];
        //$Item = $data[1];
        //$OrderType = $data[3];
        $OrderNo = $data[1];
        //$DelivNo = $data[0];
        $CredBlock = $data[2];
        $SoldToCustNumber = $data[0];
        //$SoldToCustName = $data[5];
        //$MaterialCode = $data[6];
        //$MaterialDescript = $data[7];  
        //$PlantCode = $data[8];
        //$OpenQConfirmedQ = floatval ($data[11]); 
        //$OrderQ = floatval ($data[9]);
        //$SalesUoM = $data[10];
        //$ConfirmedDelvDate = $data[11];
        //$ShipToCustNumber = $data[12];
        //$ShipToCustName = $data[13];
        //$CustPurchaseOrdNo = $data[17];
        //$ConfirmedShipDate = $data[15];        

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

        $el->clientNumber = $data[0];
        $el->orderNumber = $data[1];

        return $el;
    }
}
