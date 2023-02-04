<?php

namespace common\services;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\CSV\Reader;
use common\models\Import;
use common\models\sap\DupontCyo;
use common\models\sap\Elemento;
use common\models\TypeImport;

class AutoSapImportDupontCyoService extends AutoSapImportService
{
    const ARCHIVOS_IMPORTADOS_FOLDER_PATH = "@console/importacion_automatica_sap/dupont/archivos_importados";
    //const FILENAME = "@console/importacion_automatica_sap/dupont/Consignment.csv";

//    const FILENAME = "Y:\\\\Consignment.csv";
	const FILENAME = "\\\\dow.forecast\\ReportesBW\\DP\\YGV10038.csv";

    /**
     * @return string
     */
    public function getJobName()
    {
        return "SAP Dupont CyOs";
    }

    /**
     * @return Reader
     */
    protected function createCSVReader()
    {
        $readerCSV = ReaderEntityFactory::createCSVReader();
        $readerCSV->setFieldDelimiter(';');
		
        return $readerCSV;
    }

    /**
     * @return int
     */
    protected function getTypeImportId()
    {
        return TypeImport::AUTOMATIC_DUPONT_CYO;
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
        return DupontCyo::fromCSV($data, $campaignId, $importId, $rowNumber);
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
