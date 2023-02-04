<?php

namespace common\services;

use common\models\Import;
use common\models\sap\DasCyo;
use common\models\sap\Elemento;
use common\models\TypeImport;

class AutoSapImportDasCyoService extends AutoSapImportService
{
    const ARCHIVOS_IMPORTADOS_FOLDER_PATH = "@console/importacion_automatica_sap/das/archivos_importados";
    //const FILENAME = "@console/importacion_automatica_sap/das/Consignment.csv";

//    const FILENAME = "Y:\\\\Consignment.csv";
	const FILENAME = "\\\\PHAZP0072fs\\dataexchange\\BW\\PD0\\100\\ISC\\Consignment.csv";

    /**
     * @return string
     */
    public function getJobName()
    {
        return "SAP DAS CyOs";
    }

    /**
     * @return int
     */
    protected function getTypeImportId()
    {
        return TypeImport::AUTOMATIC_DAS_CYO;
    }

    /**
     * @param array $data
     * @param $campaignId
     * @param $importId
     * @param int $rowNumber
     * @return Elemento
     */
    protected function createElemento(array $data, $campaignId, $importId, int $rowNumber)
    {
        return DasCyo::fromCSV($data, $campaignId, $importId, $rowNumber);
    }

    /**
     * @param Import $import
     * @param array $elementosValidos
     * @return array
     */
    protected function doImportToDb(Import $import, array $elementosValidos)
    {
        return $import->importToDBCyOFromAutomaticImport($elementosValidos);
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
     * @inheritDoc
     */
    protected function exitWhenGmidNotFound()
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

    /**
     * @inheritDoc
     */
    protected function exitWhenFailedElement()
    {
        return false;
    }
}
