<?php

namespace common\models\sap;

use Yii;
use yii\db\Exception;

class ClienteUnificado
{
    public $soldToParty;
    public $description;
    public $conversionCode;

    /**
     * ClienteUnificado constructor.
     * @param $soldToParty
     * @param $description
     * @param $conversionCode
     */
    public function __construct($soldToParty, $description, $conversionCode)
    {
        $this->soldToParty = $soldToParty;
        $this->description = $description;
        $this->conversionCode = $conversionCode;
    }

    /**
     * @param array $data
     * @return ClienteUnificado
     */
    public static function fromXLSX(array $data)
    {
        return new self($data[1], $data[2], $data[5]);
    }

    /**
     * @param array $data
     * @return ClienteUnificado
     */
    public static function fromDB(array $data)
    {
        return new self($data["SoldToParty"], $data["Customer"], $data["ConversionCode"]);
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function getClientesUnificadosForAutoImport()
    {
        $connection = Yii::$app->db;

        $clientes = $connection->createCommand("
            SELECT
                SoldToParty,
                Customer,
                ConversionCode
            FROM dbo.unificacion_cliente uc
            INNER JOIN dbo.client c ON uc.ConversionCode = c.ClientId
        ")->queryAll();

        $clientesUnificados = [];

        foreach ($clientes as $cliente) {
            $clienteUnificado = self::fromDB($cliente);
            $clientesUnificados[$clienteUnificado->soldToParty] = $clienteUnificado;
        }

        return $clientesUnificados;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function getArrayOfClientesUnificadosForExcelExport()
    {
        $connection = Yii::$app->db;

        return $connection->createCommand("
            SELECT Country,
                SoldToParty,
                Customer,
                FieldSeller,
                DSM,
                ConversionCode,
                ConversionName,
                CUIT
            FROM dbo.unificacion_cliente
        ")->queryAll();
    }
}
