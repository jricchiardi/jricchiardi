<?php

namespace common\models\sap;

abstract class FcNoCont implements Elemento
{
    use ArrayKeyExists;

   public $rowNumber;

   public $SalesOrg;
   public $BillingNo;
   public $BillingType;      	
   public $SoldToPartyNumber;
   public $SoldToPartyName;
   public $Item;
   public $MaterialCode;
   public $MaterialDescript;
   public $BilledQ;
   public $BaseUoM;
   public $BillingDate;

         //Constructor
		 
    public function __construct(
        int $rowNumber,
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
        )
    {
        $this->rowNumber = $rowNumber;

        $this->SalesOrg = $SalesOrg;
		$this->BillingNo = $BillingNo;
		$this->BillingType = $BillingType;
		$this->SoldToPartyNumber = $SoldToPartyNumber;
        $this->SoldToPartyName = $SoldToPartyName;		
        $this->Item = $Item;        
        $this->MaterialCode = $MaterialCode;
        $this->MaterialDescript = $MaterialDescript;          
        $this->BilledQ = $BilledQ;
        $this->BaseUoM = $BaseUoM;        
        $this->BillingDate = $BillingDate;
    }

    /**
     * @return array
     */
    public function toArrayValido()
    {
        return [
                     'SalesOrg' => $this ->SalesOrg,
					 'BllingNo' => $this ->BillingNo,
					 'BillingType'  => $this ->BillingType,
					 'SoldToPartyNumber'=> $this ->SoldToPartyNumber,
                     'SoldToPartyName' => $this ->SoldToPartyName,					 
                     'Item' => $this -> Item,
                     'MaterialCode'=> $this ->MaterialCode,
                     'MaterialDescript' => $this ->MaterialDescript,
                     'BilledQ' => $this ->BilledQ,
                     'BaseUoM' => $this ->BaseUoM,
                     'BillingDate' => $this ->BillingDate,
            
        ];
    }

    /**
     * @return array
     */
    public function toArrayNoValido()
    {
        return [
            'BillingNo' => $this ->BillingNo,
            'description' => "La OA de la fila {$this->rowNumber} no es válida",
            'MaterialCode' => $this ->MaterialCode,
            'BillingType' => $this->BillingType,
        ];
    }

    /**
     * @return bool
     */
    public function esValidoParaCargar()
    {
        return true;
    }
}
