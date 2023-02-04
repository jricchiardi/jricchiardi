<?php

namespace common\models\sap;

use Exception;
use DateTime;

class DupontShortFcNoCont extends FcNoCont
{
    public $materialCode;
    public $BillingNumber;

    /**
     * @param array $data
     * @param $campaignId
     * @param $importId
     * @param int $rowNumber
     * @return Elemento
     */
   public static function fromCSV(array $data, $BillingNo, $MaterialCode, int $rowNumber)
    {
        if (!self::array_keys_exist($data, '0', '2')) {
            throw new Exception("Some element is missing");
	       }
		   
	
        $SalesOrg = null;
        $BillingNo = $data[0];
        $BillingType = null;
        $SoldToPartyNumber = null;
        $SoldToPartyName = null;
        $BillingDate = null;
        $Item = $data[1];
        $MaterialCode = $data[2];
        $MaterialDescript = $data[3];          
        $BilledQ = floatval ($data[4]);
        $BaseUoM = $data[5];        
                

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

        $el->BillingNumber = $data[0];
        $el->materialCode = $data[2];

        return $el;
    }
}
