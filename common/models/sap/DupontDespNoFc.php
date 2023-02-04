<?php

namespace common\models\sap;

use Exception;
use DateTime;

class DupontDespNoFc extends DespNoFc
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
        if (!self::array_keys_exist($data, '0', '4')) {
            throw new Exception("Some element is missing");
	       }
		   
	
        $SalesDoc = $data[0];
		$SalesItem = $data[1];
		//$SalesDocType = $data[2];
		$SoldToCustNumber = $data[3];
        $SoldToCustName = $data[4];		
        $MaterialCode = $data[5];
        $MaterialDescript = $data[6];          
        $DeliveryQ = floatval ($data[7]);
        $SalesUoM = $data[8];        
                

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

        $el->clientName = $data[4];
        $el->DocumentNumber = $data[0];

        return $el;
    }
}
