<?php

namespace common\models\sap;

abstract class DespNoFc implements Elemento
{
    use ArrayKeyExists;

   public $rowNumber;

   public $SalesDoc;
   public $SalesItem;
   public $SalesDocType;      	
   public $SoldToCustNumber;
   public $SoldToCustName;
   public $MaterialCode;
   public $MaterialDescript;
   public $DeliveryQ;
   public $SalesUoM;

         //Constructor
		 
    public function __construct(
        int $rowNumber,
        $SalesDoc,
        $SalesItem,
        $SalesDocType,      	
        $SoldToCustNumber,
        $SoldToCustName,
        $MaterialCode,
        $MaterialDescript,
        $DeliveryQ,
        $SalesUoM               
        )
    {
        $this->rowNumber = $rowNumber;

        $this->SalesDoc = $SalesDoc;
		$this->SalesItem = $SalesItem;
		$this->SalesDocType = $SalesDocType;
		$this->SoldToCustNumber = $SoldToCustNumber;
        $this->SoldToCustName = $SoldToCustName;
        $this->MaterialCode = $MaterialCode;
        $this->MaterialDescript = $MaterialDescript;          
        $this->DeliveryQ = $DeliveryQ;
        $this->SalesUoM = $SalesUoM;
    }

    /**
     * @return array
     */
    public function toArrayValido()
    {
        return [
                     'SalesDoc' => $this ->SalesDoc,
					 'SalesItem' => $this ->SalesItem,
					 'SalesDocType'  => $this ->SalesDocType,
					 'SoldToCustNumber'=> $this ->SoldToCustNumber,
                     'SoldToCustName' => $this ->SoldToCustName,
                     'MaterialCode'=> $this ->MaterialCode,
                     'MaterialDescript' => $this ->MaterialDescript,
                     'DeliveryQ' => $this ->DeliveryQ,
                     'SalesUoM' => $this ->SalesUoM,
            
        ];
    }

    /**
     * @return array
     */
    public function toArrayNoValido()
    {
        return [
            'SalesDoc' => $this ->SalesDoc,
            'description' => "El Sales Doc de la fila {$this->rowNumber} no es válida",
            'MaterialCode' => $this ->MaterialCode,
            'SalesDocType' => $this->SalesDocType,
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
