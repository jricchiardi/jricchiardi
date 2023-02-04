<?php

namespace common\services;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\CSV\Reader;
use common\models\Import;
use common\models\sap\DupontSale;
use common\models\sap\Elemento;
use common\models\TypeImport;

class AutoSapImportDupontSalesService extends AutoSapImportService
{
    const ARCHIVOS_IMPORTADOS_FOLDER_PATH = "@console/importacion_automatica_sap/dupont/archivos_importados";
    //const FILENAME = "@console/importacion_automatica_sap/dupont/sales.csv";

//    const FILENAME = "Y:\\\\sales.csv";
	const FILENAME = "\\\\dow.forecast\\ReportesBW\\DP\\YGV11348.csv";

    /**
     * @return string
     */
    public function getJobName()
    {
        return "SAP Dupont Sales";
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
        return TypeImport::AUTOMATIC_DUPONT_SALE;
    }

    /**
     * @param array $data
     * @param $campaignId
     * @param $importId
     * @return Elemento
     */
    protected function createElemento(array $data, $campaignId, $importId, int $rowNumber)
    {
        return DupontSale::fromCSV($data, $campaignId, $importId, $rowNumber);
    }

    /**
     * @param Import $import
     * @param array $elementosValidos
     * @return array
     */
    protected function doImportToDb(Import $import, array $elementosValidos)
    {
        return $import->importToDBSalesFromAutomaticImportDupont($elementosValidos);
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
            55711127,
            55501595,
            58000067,
        ];
    }

    /**
     * @return array
     */
    protected function getGmidsExcluidos()
    {
        return [
			0,
            15544901,
            60001428,
			99058447,
			11034854,
			97083744,
			97080525,
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
