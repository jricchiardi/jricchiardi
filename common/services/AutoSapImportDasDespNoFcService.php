<?php

namespace common\services;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\CSV\Reader;
use common\models\Import;
use common\models\sap\DasDespNoFc;
use common\models\sap\Elemento;
use common\models\TypeImport;
use common\models\sap\NullObjectElemento;


class AutoSapImportDasDespNoFcService extends AutoSapImportService
{
    const ARCHIVOS_IMPORTADOS_FOLDER_PATH = "@console/importacion_automatica_sap/das/archivos_importados";
    const FILENAME = "@console/importacion_automatica_sap/das/DasDespNoFc.csv";
	
	

//    const FILENAME = "Y:\\\\FcNoCont.csv";

    /**
     * @return string
     */
    public function getJobName()
    {
        return "SAP Das DespNoFc";
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
        return TypeImport::AUTOMATIC_DAS_DESP_NOFC;
    }

    /**
     * @param array $data
     * @param $campaignId
     * @param $importId
     * @param int $rowNumber
     * @return Elemento
     */
	 
	
    protected function createElemento(array $data, $SalesDoc, $SoldToCustName, int $rowNumber)
    {
		if ($rowNumber < 131) {
		return new NullObjectElemento();
		}
        return DasDespNoFc::fromCSV($data, $SalesDoc, $SoldToCustName, $rowNumber);
    }

    /**
     * @param Import $import
     * @param array $elementosValidos
     * @return array
     */
    protected function doImportToDb(Import $import, array $elementosValidos)
    {
        return $import->importToDBDespNoFcFromAutomaticImport($elementosValidos);
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

    /**
     * @return array
     */
    protected function getGmidsExcluidos()
    {
        return [];
    }
}
