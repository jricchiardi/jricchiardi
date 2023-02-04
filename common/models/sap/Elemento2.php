<?php

namespace common\models\sap;

interface Elemento2
{
    /**
     * @param array $data
     * @param $campaignId
     * @param $importId
     * @param int $rowNumber
     * @return Elemento
     */
    public static function fromCSV(array $data, $campaignId, $importId, int $rowNumber);

    /**
     * @return array
     */
    public function toArrayValido();

    /**
     * @return array
     */
    public function toArrayNoValido();

    /**
     * @return bool
     */
    public function esValidoParaCargar();
}
