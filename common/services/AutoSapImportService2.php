<?php

namespace common\services;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\CSV\Reader;
use common\models\Campaign;
use common\models\Import;
use common\models\sap\ClienteUnificado;
use common\models\sap\Elemento;
use Exception;
use Yii;
use yii\db\Exception as YiiDbException;
use yii\db\Query;

require_once Yii::$app->basePath . './../frontend/spout-3.1.0/src/Spout/Autoloader/autoload.php';

abstract class AutoSapImportService2
{
    protected $importadosFilesFolder;
    protected $file;

    /**
     * @return string
     */
    public abstract function getJobName();

    /**
     * @return int
     */
    protected abstract function getTypeImportId();

    /**
     * @param array $data
     * @param $campaignId
     * @param $importId
     * @return Elemento
     */
    protected abstract function createElemento(array $data, $OrderNo, $SoldToCustName, int $rowNumber);

    /**
     * @param Import $import
     * @param array $elementosValidos
     * @return mixed
     */
    protected abstract function doImportToDb(Import $import, array $elementosValidos);

    /**
     * @return bool
     */
   
    protected abstract function exitWhenExistsInvalidElements();

    /**
     * @return bool
     */
   
    protected abstract function considerInvalidElementsAsError();

     /**
     * @return bool
     */
    protected abstract function considerFailedElementsAsError();

    /**
     * AutomaticImportSap constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->importadosFilesFolder = Yii::getAlias(static::ARCHIVOS_IMPORTADOS_FOLDER_PATH);
        if (!is_readable($this->importadosFilesFolder)) {
            throw new Exception("No se encontró la carpeta para guardar archivos importados en el path: " . $this->importadosFilesFolder);
        }
    }

    /**
     * @param string|null $filePath
     * @return array
     * @throws Exception
     */
    public function doImport($filePath = null)
    {
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");

        $this->file = $filePath ?? Yii::getAlias(static::FILENAME);

        if (!is_readable($this->file)) {
            throw new Exception("No se encontró el archivo en el path: " . $this->file);
        }

        $import = $this->createImport();
        $readerCSV = $this->createCSVReader();
        $elementosValidos = [];
        

        $readerCSV->open($this->file);
        foreach ($readerCSV->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $n => $row) {
                if ($n === 1) {
                    continue;
                }

                try {
                    $elemento = $this->createElemento($row->toArray(), $import->ImportId, $n);
                } catch (Exception $e) {
                    continue;
                }

                

                if (!$elemento->esValidoParaCargar()) {
                    continue;
                }

                $elemento->clienteUnificado = $clientesUnificados[intval($elemento->cliente)];

                $elementosValidos[] = $elemento->toArrayValido();
            }
        }
        $readerCSV->close();

        $nuevoPath = $this->importadosFilesFolder . DIRECTORY_SEPARATOR . $import->Name;
        copy($this->file, $nuevoPath);

        if (count($gmidsNoEncontrados) > 0) {
            if ($this->considerGmidNotFoundAsError()) {
                $import->WithErrors = true;
                if ($this->exitWhenGmidNotFound()) {
                    $this->guardarErroresDeGmidsNoEncontrados($gmidsNoEncontrados);
                    $import->FinishedCorrectly = false;
                    $import->save();
                    return $gmidsNoEncontrados;
                }
            }
        }

        if (count($clientesNoEncontrados) > 0) {
            if ($this->considerClientNotFoundAsError()) {
                $import->WithErrors = true;
                if ($this->exitWhenClientNotFound()) {
                    $this->guardarErroresDeClientesNoEncontrados($clientesNoEncontrados);
                    $import->FinishedCorrectly = false;
                    $import->save();
                    return $clientesNoEncontrados;
                }
            }
        }

        $errors = $this->doImportToDb($import, $elementosValidos);

        if (count($errors) > 0) {
            $import->FinishedCorrectly = false;
            $import->WithErrors = true;
        }

        $this->guardarErroresDeGmidsNoEncontrados($gmidsNoEncontrados);
        $this->guardarErroresDeClientesNoEncontrados($clientesNoEncontrados);

        $import->save();

        return $errors;
    }

   

    /**
     * @return Reader
     */
    protected function createCSVReader()
    {
        return ReaderEntityFactory::createCSVReader();
    }

    /**
     * @return Import
     */
    private function createImport()
    {
        $nuevoFilename = $this->slugify($this->getJobName());

        $import = new Import();
        $import->FinishedCorrectly = true;
        $import->WithErrors = false;
        $import->Name = $nuevoFilename;
        $import->TypeImportId = $this->getTypeImportId();
        $import->save();

        return $import;
    }

      /**
     * @return string
     */
    private function getSuffixFilename()
    {
        return time() . '-' . uniqid() . '.csv';
    }
}