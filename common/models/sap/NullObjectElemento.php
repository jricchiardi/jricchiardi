<?php

namespace common\models\sap;

use Exception;
use DateTime;

class NullObjectElemento implements Elemento
{

    public static function fromCSV(array $data, $campaignId, $importId, int $rowNumber)
    {
        // TODO: Implement fromCSV() method.
    }

    public function toArrayValido()
    {
        // TODO: Implement toArrayValido() method.
    }

    public function toArrayNoValido()
    {
        // TODO: Implement toArrayNoValido() method.
    }

    public function esValidoParaCargar()
    {
        return false;
    }
}
