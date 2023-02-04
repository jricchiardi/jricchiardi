<?php

namespace common\models\sap;

abstract class Cyo implements Elemento
{
    use ArrayKeyExists;

    public $rowNumber;

    public $inventoryBalance;
    public $gmid;
    public $campaignId;
    public $importId;
    public $cliente;

    /** @var ClienteUnificado */
    public $clienteUnificado;

    /**
     * Cyo constructor.
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
        $inventoryBalance,
        $gmid,
        $campaignId,
        $importId,
        $cliente,
        ClienteUnificado $clienteUnificado = null
    )
    {
        $this->rowNumber = $rowNumber;

        $this->inventoryBalance = $inventoryBalance;
        $this->gmid = $gmid;
        $this->campaignId = $campaignId;
        $this->importId = $importId;
        $this->cliente = $cliente;
        $this->clienteUnificado = $clienteUnificado;
    }

    /**
     * @return array
     */
    public function toArrayValido()
    {
        return [
            'ClientId' => $this->clienteUnificado->conversionCode,
            'GmidId' => $this->gmid,
            'CampaignId' => $this->campaignId,
            'InventoryBalance' => $this->inventoryBalance,
            'ImportId' => $this->importId,
        ];
    }

    /**
     * @return array
     */
    public function toArrayNoValido()
    {
        return [
            'gmid' => $this->gmid,
            'description' => "La CyO de la fila {$this->rowNumber} no es válida (inventory balance: {$this->inventoryBalance})",
            'month' => null,
            'client' => $this->clienteUnificado->conversionCode,
            'cause' => 'CYO NO ES VALIDA',
            'ImportId' => $this->importId,
        ];
    }

    /**
     * @return bool
     */
    public function esValidoParaCargar()
    {
        return $this->inventoryBalance > 0;
    }
}
