<?php

namespace common\models\sap;

abstract class FcastIBP implements Elemento
{
    use ArrayKeyExists;

   public $rowNumber;

   public $ShipToCountry;
   public $Portfolio;
   public $Ingredient;
   public $OldProductID;
   public $ProductDesc;
   public $KeyFigure;	
   public $January;
   public $February;
   public $March;
   public $April;  
   public $May; 
   public $June; 
   public $July;
   public $August;
   public $September;
   public $October; 
   public $November; 
   public $December;
   public $TotalYear;
   public $Año;

    /** @var ClienteUnificado */
    /* public $clienteUnificado;

    /**
     * OpenOrder constructor.
     * @param int $rowNumber
     * @param $inventoryBalance
     * @param $gmid
     * @param $campaignId
     * @param $importId
     * @param $cliente
     * @param ClienteUnificado|null $clienteUnificado
     */
    public function __construct(
        int $rowNumber,
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
        
        /**ClienteUnificado $clienteUnificado = null*/
    )
    {
        $this->rowNumber = $rowNumber;

        $this->ShipToCountry = $ShipToCountry;
        $this->Portfolio = $Portfolio;
        $this->Ingredient = $Ingredient;
        $this->OldProductID = $OldProductID;
        $this->ProductDesc = $ProductDesc;
        $this->KeyFigure = $KeyFigure;	
        $this->January = $January;
        $this->February = $February;
        $this->March = $March;
        $this->April = $April;  
        $this->May = $May;
        $this->June = $June; 
        $this->July = $July;
        $this->August = $August;
        $this->September = $September;
        $this->October = $October;
        $this->November = $November;
        $this->December = $December;
        $this->TotalYear = $TotalYear;
		$this->Año = $Año;
    }

    /**
     * @return array
     */
    public function toArrayValido()
    {
        return [
                     'ShipToCountry' => $this ->ShipToCountry,
                     'Portfolio' => $this -> Portfolio,
                     'Ingredient' => $this ->Ingredient,
                     'OldProductID' => $this ->OldProductID,
                     'ProductDesc' => $this ->ProductDesc,
                     'KeyFigure'  => $this ->KeyFigure,	
                     'January'=> $this ->January,
                     'February' => $this ->February,
                     'March'=> $this ->March,
                     'April' => $this ->April,  
                     'May' => $this ->May, 
                     'June' => $this ->June, 
                     'July' => $this ->July,
                     'August' => $this ->August,
                     'September' => $this ->September,
                     'October' => $this ->October, 
                     'November' => $this ->November, 
                     'December' => $this ->December,
                     'TotalYear' => $this ->TotalYear,
					 'Año' => $this ->Año,
            
        ];
    }

    /**
     * @return array
     */
    public function toArrayNoValido()
    {
        return [
            'OldProductID' => $this ->OldProductID,
            'description' => "La OA de la fila {$this->rowNumber} no es válida",
           
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
