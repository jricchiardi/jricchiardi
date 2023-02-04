<?php

namespace common\models\sap;

abstract class Sale implements Elemento
{
    use ArrayKeyExists;

    public $rowNumber;

    public $country;
    public $gmid;
    public $calendarYear;
    public $calendarMonth;
    public $netSales;
    public $volume;
    public $importId;
    public $cliente;

    /** @var ClienteUnificado */
    public $clienteUnificado;

    /**
     * Sale constructor.
     * @param int $rowNumber
     * @param $country
     * @param $gmid
     * @param $calendarYear
     * @param $calendarMonth
     * @param $netSales
     * @param $volume
     * @param $importId
     * @param $cliente
     * @param ClienteUnificado|null $clienteUnificado
     */
    public function __construct(
        int $rowNumber,
        $country,
        $gmid,
        $calendarYear,
        $calendarMonth,
        $netSales,
        $volume,
        $importId,
        $cliente,
        ClienteUnificado $clienteUnificado = null
    )
    {
        $this->rowNumber = $rowNumber;

        $this->country = $country;
        $this->gmid = $gmid;
        $this->calendarYear = $calendarYear;
        $this->calendarMonth = $calendarMonth;
        $this->netSales = $netSales;
        $this->volume = $volume;
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
            $this->country,
            $this->clienteUnificado->conversionCode, // Liable Customer
            $this->clienteUnificado->description, // F3
            $this->gmid, // GMID
            null, // F5
            null, // Field Seller
            null, // F7
            $this->calendarYear, // Calendar year
            $this->calendarMonth, // Calendar month
            $this->volume, // Actual
            $this->netSales, // Total
            null, // Actual2
            $this->importId,
        ];
    }

    /**
     * @return array
     */
    public function toArrayNoValido()
    {
        return [
            'gmid' => $this->gmid,
            'description' => "La venta de la fila {$this->rowNumber} no es válida (volúmen: {$this->volume} // net sale: {$this->netSales}",
            'month' => $this->calendarMonth,
            'client' => $this->clienteUnificado->conversionCode,
            'cause' => 'VENTA NO ES VALIDA',
            'ImportId' => $this->importId,
        ];
    }

    /**
     * @return bool
     */
    public function esValidoParaCargar()
    {
        return $this->volume !== 0.00 || $this->netSales !== 0.000;
    }
}
