<?php

namespace common\models\sis;

use common\models\Country;

class CountryTranslate
{
    static $countries = [
        'AR' => 'ARG',
        'BO' => 'BOL',
        'CL' => 'CHL',
        'UY' => 'URY',
        'PY' => 'PRY',
    ];

    public static function toAbbr(string $countryCode)
    {
        return self::$countries[$countryCode] ?? $countryCode;
    }

    public static function toCountry(string $countryCode)
    {
        return Country::findOne(['Abbreviation'=>self::toAbbr($countryCode)]);
    }
	
    public static function toShort(string $countryCode)
    {
        foreach (self::$countries as $short => $long) {
            if ($long === $countryCode) {
                return $short;
            }
        }
        return $countryCode;
    }
}
/*
CREATE VIEW vw_gmid_ingredient_country AS
SELECT Ingredient, CONVERT(INT, REPLACE(OldProductId, 'D','')) AS  GmidId, ShipToCountry AS CountryCode
FROM FCASTIBP
GROUP BY Ingredient, OldProductId,ShipToCountry 
 * */