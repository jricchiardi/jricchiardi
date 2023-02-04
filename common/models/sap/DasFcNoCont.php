<?php

namespace common\models\sap;

use Exception;
use DateTime;

class DasFcNoCont extends FcNoCont
{
    public $clientName;
    public $BillingNumber;

    /**
     * @param array $data
     * @param $campaignId
     * @param $importId
     * @param int $rowNumber
     * @return Elemento
     */
   public static function fromCSV(array $data, $BillingNo, $SoldToPartyName, int $rowNumber)
    {
        if (!self::array_keys_exist($data, '8', '14')) {
            throw new Exception("Some element is missing");
	       }
		   
	
        $SalesOrg = $data[0];
		$BillingNo = $data[8];
		$BillingType = $data[4];
		$SoldToPartyNumber = $data[7];
        $SoldToPartyName = $data[14];
		$BillingDate = date_format ($data[3], "d/m/y");
        //$Item = $data[1];
        //$MaterialCode = $data[2];
        //$MaterialDescript = $data[3];          
        //$BilledQ = floatval ($data[4]);
        //$BaseUoM = $data[5];  

        $Item = null;
        $MaterialCode = null;
        $MaterialDescript = null;          
        $BilledQ = null;
        $BaseUoM = null;     		
                

        $el = new self(
        $rowNumber,
        $SalesOrg,
        $BillingNo,
        $BillingType,      	
        $SoldToPartyNumber,
        $SoldToPartyName,
        $Item,
        $MaterialCode,
        $MaterialDescript,
        $BilledQ,
        $BaseUoM,
        $BillingDate 
        );

        $el->clientName = $data[14];
        $el->BillingNumber = $data[8];

        return $el;
    }
}
