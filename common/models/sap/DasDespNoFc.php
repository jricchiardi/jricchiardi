<?php

namespace common\models\sap;

use Exception;
use DateTime;

class DasDespNoFc extends DespNoFc
{
    public $clientName;
    public $DocumentNumber;
	
    /**
     * @param array $data
     * @param $campaignId
     * @param $importId
     * @param int $rowNumber
     * @return Elemento
     */
   public static function fromCSV(array $data, $SalesDoc, $SoldToCustName, int $rowNumber)
    {
        if (!self::array_keys_exist($data, '4', '1')) {
            throw new Exception("Some element is missing");
	       }
		   
	
        $SalesDoc = $data[4];
		$SalesItem = $data[5];
		$SalesDocType = $data[2];
		$SoldToCustNumber = $data[0];
        $SoldToCustName = $data[1];		
        $MaterialCode = $data[6];
        $MaterialDescript = $data[7];          
        $DeliveryQ = floatval ($data[16]);
        $SalesUoM = $data[15];        
                

        $el = new self(
        $rowNumber,
        $SalesDoc,
        $SalesItem,
        $SalesDocType,      	
        $SoldToCustNumber,
        $SoldToCustName,
        $MaterialCode,
        $MaterialDescript,
        $DeliveryQ,
        $SalesUoM
        );

        $el->clientName = $data[1];
        $el->DocumentNumber = $data[4];

        return $el;
    }
}
