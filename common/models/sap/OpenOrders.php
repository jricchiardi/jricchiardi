<?php

namespace common\models\sap;

abstract class OpenOrders implements Elemento
{
    use ArrayKeyExists;

   public $rowNumber;

   public $SalesOrg;
   public $Item;
   public $OrderNo;
   public $DelivNo;
   public $CredBlock;
   public $OrderType;	
   public $SoldToCustNumber;
   public $SoldToCustName;
   public $MaterialCode;
   public $MaterialDescript;  
   public $PlantCode; 
   public $OpenQConfirmedQ; 
   public $OrderQ;
   public $SalesUoM;
   public $ConfirmedDelvDate;
   public $ShipToCustNumber; 
   public $ShipToCustName; 
   public $CustPurchaseOrdNo;
   public $ConfirmedShipDate;

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
        
        /**ClienteUnificado $clienteUnificado = null*/
    )
    {
        $this->rowNumber = $rowNumber;

        $this->SalesOrg = $SalesOrg;
        $this->Item = $Item;
        $this->OrderNo = $OrderNo;
        $this->DelivNo = $DelivNo;
        $this->CredBlock = $CredBlock;
        $this->OrderType = $OrderType;	
        $this->SoldToCustNumber = $SoldToCustNumber;
        $this->SoldToCustName = $SoldToCustName;
        $this->MaterialCode = $MaterialCode;
        $this->MaterialDescript = $MaterialDescript;  
        $this->PlantCode = $PlantCode;
        $this->OpenQConfirmedQ = $OpenQConfirmedQ; 
        $this->OrderQ = $OrderQ;
        $this->SalesUoM = $SalesUoM;
        $this->ConfirmedDelvDate = $ConfirmedDelvDate;
        $this->ShipToCustNumber = $ShipToCustNumber;
        $this->ShipToCustName = $ShipToCustName;
        $this->CustPurchaseOrdNo = $CustPurchaseOrdNo;
        $this->ConfirmedShipDate = $ConfirmedShipDate;
    }

    /**
     * @return array
     */
    public function toArrayValido()
    {
        return [
                     'SalesOrg' => $this ->SalesOrg,
                     'Item' => $this -> Item,
                     'OrderNo' => $this ->OrderNo,
                     'DelivNo' => $this ->DelivNo,
                     'CredBlock' => $this ->CredBlock,
                     'OrderType'  => $this ->OrderType,	
                     'SoldToCustNumber'=> $this ->SoldToCustNumber,
                     'SoldToCustName' => $this ->SoldToCustName,
                     'MaterialCode'=> $this ->MaterialCode,
                     'MaterialDescript' => $this ->MaterialDescript,  
                     'PlantCode' => $this ->PlantCode, 
                     'OpenQConfirmedQ' => $this ->OpenQConfirmedQ, 
                     'OrderQ' => $this ->OrderQ,
                     'SalesUoM' => $this ->SalesUoM,
                     'ConfirmedDelvDate' => $this ->ConfirmedDelvDate,
                     'ShipToCustNumber' => $this ->ShipToCustNumber, 
                     'ShipToCustName' => $this ->ShipToCustName, 
                     'CustPurchaseOrdNo' => $this ->CustPurchaseOrdNo,
                     'ConfirmedShipDate' => $this ->ConfirmedShipDate,
            
        ];
    }

    /**
     * @return array
     */
    public function toArrayNoValido()
    {
        return [
            'OrderNo' => $this ->OrderNo,
            'description' => "La OA de la fila {$this->rowNumber} no es válida",
            'MaterialCode' => $this ->MaterialCode,
            'OrderType' => $this->OrderType,
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
