<?php

namespace common\models\sap;

use Exception;
use DateTime;

class DelivOpenOrders extends OpenOrders
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
        if (!self::array_keys_exist($data, '2', '5')) {
            throw new Exception("Some element is missing");
	       }
		   
        $SalesOrg = $data[14];
        $Item = $data[1];
        $OrderType = $data[3];
        $OrderNo = $data[2];
        $DelivNo = $data[0];
        $CredBlock = null;
        $SoldToCustNumber = $data[4];
        $SoldToCustName = $data[5];
        $MaterialCode = $data[6];
        $MaterialDescript = $data[7];  
        $PlantCode = $data[8];
        $OpenQConfirmedQ = floatval ($data[9]);
        $OrderQ = floatval ($data[9]);
        $SalesUoM = $data[10];
        $ConfirmedDelvDate = !empty($data[11]) ? date_format ($data[11], 'd/m/Y'):"";
        $ShipToCustNumber = $data[12];
        $ShipToCustName = $data[13];
        $CustPurchaseOrdNo = null;
        $ConfirmedShipDate = !empty($data[15]) ? date_format ($data[15], 'd/m/Y'):"";        

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

        $el->clientName = $data[5];
        $el->orderNumber = $data[2];
		
        return $el;
    }
}
