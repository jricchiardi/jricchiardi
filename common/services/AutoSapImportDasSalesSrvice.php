<?php

namespace common\services;

use common\models\Import;
use common\models\sap\DasSale;
use common\models\sap\Elemento;
use common\models\TypeImport;
use Exception;

class AutoSapImportDasSalesSrvice extends AutoSapImportService
{
    const ARCHIVOS_IMPORTADOS_FOLDER_PATH = "@console/importacion_automatica_sap/das/archivos_importados";
    //const FILENAME = "@console/importacion_automatica_sap/das/sales.csv";

//    const FILENAME = "Y:\\\\sales.csv";
	const FILENAME = "\\\\PHAZP0072fs\\dataexchange\\BW\\PD0\\100\\ISC\\sales.csv";

    /**
     * @return string
     */
    public function getJobName()
    {
        return "SAP DAS Sales";
    }

    /**
     * @return int
     */
    protected function getTypeImportId()
    {
        return TypeImport::AUTOMATIC_DAS_SALE;
    }

    /**
     * @param array $data
     * @param $campaignId
     * @param $importId
     * @param int $rowNumber
     * @return Elemento
     * @throws Exception
     */
    protected function createElemento(array $data, $campaignId, $importId, int $rowNumber)
    {
        return DasSale::fromCSV($data, $campaignId, $importId, $rowNumber);
    }

    /**
     * @param Import $import
     * @param array $elementosValidos
     * @return array
     */
    protected function doImportToDb(Import $import, array $elementosValidos)
    {
        return $import->importToDBSalesFromAutomaticImport($elementosValidos);
    }

    /**
     * @inheritDoc
     */
    protected function exitWhenClientNotFound()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    protected function exitWhenExistsInvalidElements()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    protected function considerClientNotFoundAsError()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function considerInvalidElementsAsError()
    {
        return false;
    }

    /**
     * @return array
     */
    protected function getClientesExcluidos()
    {
        return [
            0,
            447444,
            267648,
            61615,
            422326,
            551656,
            312172,
            704066,
            1,
			950335,
			1122776,
            471318,
            1131890,
            1793218,
            1481851,
            10006168,
            10016449,
            10022211,
			10021155,
			10021162,
			10021165,
			10021166,
			10021168,
			10021156,
			10021157,
			10021161,
			10021163,
			10021170,
			10021178,
			10021179,
			10021169,
			10021171,
			10021172,
			10021173,
			10021174,
			10021175,
			10021176,
			10021181,
			10021182,
			10021183,
			10021154,
        ];
    }

    /**
     * @return array
     */
    protected function getGmidsExcluidos()
    {
        return [
            0,
            366551,
            15522,
            170589,
            11020951,
            170586,
            134740,
            170588,
            11092681,
            170585,
            143572,
            143567,
            134470,
            11092537,
			11041106,
            11034851,
            11034867,
			188349,
            97066188,
            99029205,
            97062977,
			97083744,
			99058447,
			170587,
        ];
    }
	
	/**
     * @inheritDoc
     */
    protected function exitWhenGmidNotFound()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    protected function exitWhenFailedElement()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    protected function considerGmidNotFoundAsError()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function considerFailedElementsAsError()
    {
        return false;
    }
}
