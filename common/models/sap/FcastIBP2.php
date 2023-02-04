<?php

namespace common\models\sap;

use Exception;
use DateTime;

class FcastIBP2 extends FcastIBP
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
   public static function fromCSV(array $data, $ShipToCountry, $OldProductID, int $rowNumber)
    {
        if (!self::array_keys_exist($data, '0', '3')) {
            throw new Exception("Some element is missing");
	       }
		   
	
        $ShipToCountry = $data[0];
        $Portfolio = $data[1];
        $Ingredient = $data[2];
        $OldProductID = $data[3];
        $ProductDesc = $data[4];
        $KeyFigure = $data[5];
        $January = floatval ($data[6]);
        $February = floatval ($data[7]);
        $March = floatval ($data[8]);
        $April = floatval ($data[9]);  
        $May = floatval ($data[10]);
        $June = floatval ($data[11]); 
        $July = floatval ($data[12]);
        $August = floatval ($data[13]);
        $September = floatval($data[14]);
        $October = floatval($data[15]);
        $November = floatval($data[16]);
        $December = floatval($data[17]);
        $TotalYear = floatval($data[18]);
        $Año = date ('Y');   

        $el = new self(
        $rowNumber,
        $ShipToCountry,
        $Portfolio,
		$Ingredient,
        $OldProductID,
        $ProductDesc,
        $KeyFigure,
        $January,
        $February,
        $March,
        $April,  
        $May,
        $June, 
        $July,
        $August,
        $September,
        $October,
        $November,
        $December,
        $TotalYear,
		$Año
        );

        $el->OldProductID = $data[3];
        $el->ShipToCountry = $data[0];

        return $el;
    }
}
