<?php

namespace common\models\sap;

use DateTime;
use Exception;

class DupontSale extends Sale
{
    public $clientName;

    /**
     * @param array $data
     * @param $campaignId
     * @param $importId
     * @param int $rowNumber
     * @return Elemento
     * @throws Exception
     */
    public static function fromCSV(array $data, $campaignId, $importId, int $rowNumber)
    {
        if (!self::array_keys_exist($data, '0', '2', '3', '10', '13', '15', '17')) {
            throw new Exception("Some element is missing");
        }

        $companyCodeToCountry = [
            '0196' => 'CHL',
            '2310' => 'ARG',
        ];
        $country = $companyCodeToCountry[$data[0]];

        $cliente = $data[2];

        $billingDate = DateTime::createFromFormat('Ymd', $data[10]);
        if (gettype($billingDate) === "boolean") {
            throw new Exception("Billing date is malformed");
        }
        $calendarYear = intval($billingDate->format("Y"));
        $calendarMonth = intval($billingDate->format("m"));

        $gmid = self::parseGmid($data[13]);

        $volume = floatval($data[15]);
        if (preg_match("/-$/", $data[15])) {
            $volume = -1 * $volume;
        }

        $netSales = floatval($data[17]);
        if (preg_match("/-$/", $data[17])) {
            $netSales = -1 * $netSales;
        }

        $el = new self(
            $rowNumber,
            $country,
            $gmid,
            $calendarYear,
            $calendarMonth,
            $netSales,
            $volume,
            $importId,
            $cliente
        );

        $el->clientName = $data[3];

        return $el;
    }

    /**
     * @param string $gmid
     * @return int
     */
    private static function parseGmid(string $gmid)
    {
        if (in_array($gmid, self::getGmidStringsToExclude())) {
            return 0;
        }

        return intval(preg_replace("/[^0-9]/", "", $gmid));
    }

    /**
     * @return string[]
     */
    private static function getGmidStringsToExclude()
    {
        return [
            'F8SERV-TM173',
            'F8SERV-TAXADJUST',
            'F8SERV-REJECTCHECK',
        ];
    }
}
