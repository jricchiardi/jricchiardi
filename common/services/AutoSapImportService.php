<?php

namespace common\services;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\CSV\Reader;
use common\models\Campaign;
use common\models\Import;
use common\models\sap\ClienteUnificado;
use common\models\sap\Elemento;
use common\models\TypeImport;
use Exception;
use Yii;
use yii\db\Exception as YiiDbException;
use yii\db\Query;

require_once Yii::$app->basePath . './../frontend/spout-3.1.0/src/Spout/Autoloader/autoload.php';

abstract class AutoSapImportService
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
    protected abstract function createElemento(array $data, $campaignId, $importId, int $rowNumber);

    /**
     * @param Import $import
     * @param array $elementosValidos
     * @return mixed
     */
    protected abstract function doImportToDb(Import $import, array $elementosValidos);

    /**
     * @return bool
     */
    protected abstract function exitWhenClientNotFound();

    /**
     * @return bool
     */
    protected abstract function exitWhenExistsInvalidElements();

    /**
     * @return bool
     */
    protected abstract function exitWhenGmidNotFound();

    /**
     * @return bool
     */
    protected abstract function exitWhenFailedElement();

    /**
     * @return bool
     */
    protected abstract function considerClientNotFoundAsError();

    /**
     * @return bool
     */
    protected abstract function considerInvalidElementsAsError();

    /**
     * @return bool
     */
    protected abstract function considerGmidNotFoundAsError();

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
        $campaignId = $this->getActualCampaignId();
        $clientesUnificados = ClienteUnificado::getClientesUnificadosForAutoImport();
        $clientesExcluidos = $this->getClientesExcluidos();
        $gmidsExcluidos = $this->getGmidsExcluidos();
        $gmids = $this->getAllGmids();
        $readerCSV = $this->createReader($this->file);
        $elementosValidos = [];
        $gmidsNoEncontrados = [];
        $clientesNoEncontrados = [];

        $readerCSV->open($this->file);
        foreach ($readerCSV->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $n => $row) {
                if ($n === 1) {
				    continue;
                }				
				
                try {
                    $elemento = $this->createElemento($row->toArray(), $campaignId, $import->ImportId, $n);
                } catch (Exception $e) {
                    continue;
                }

                 if (property_exists($elemento,'cliente')) {
                    if ($this->isClienteExcluido($clientesExcluidos, $elemento->cliente)) {
                        continue;
                    }
                }

                  if (property_exists($elemento,'gmid')) {
                    if ($this->isGmidExcluido($gmidsExcluidos, $elemento->gmid)) {
                        continue;
                    }
                }
				
				if (!$elemento->esValidoParaCargar()) {
					
                    continue;
                }
		
			
				
				if (property_exists($elemento,'gmid') && $elemento->gmid != null) {                   
                  if (!$this->gmidEncontrado($gmids, $elemento->gmid)) {
                      $this->pushGmidNoEncontrado($gmidsNoEncontrados, $elemento, $n, $import->ImportId);
                      continue;
                  }
                }
                  if (property_exists($elemento,'cliente') && $elemento->cliente != null) {             
                    if (!$this->clienteEncontrado($clientesUnificados, $elemento->cliente)) {
                        $this->pushClienteNoEncontrado($clientesNoEncontrados, $elemento, $import->ImportId, $n);
                        continue;
                    }
                }
                     if (property_exists($elemento,'cliente')) {
                    $elemento->clienteUnificado = $clientesUnificados[intval($elemento->cliente)];
                }
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
     * @return array
     */
    protected function getClientesExcluidos()
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getGmidsExcluidos()
    {
        return [];
    }
    /**
     * @return \Box\Spout\Reader\ReaderInterface
     */
    protected function createReader($pathFile)
    {
        if($this->forceReaderFromFile()){
            $reader = ReaderEntityFactory::createReaderFromFile($pathFile);
            $reader->open($pathFile);

            return $reader;
        }
        return $this->createCSVReader();
    }
	
    /**
     * @return Reader
     */
    protected function createCSVReader()
    {
        $readerCSV = ReaderEntityFactory::createCSVReader();
//        $readerCSV->setFieldDelimiter(';');

        return $readerCSV;
    }

    private function forceReaderFromFile(){
        return in_array($this->getTypeImportId(), [
            TypeImport::AUTOMATIC_DUPONT_OPEN_ORDERS,
            TypeImport::AUTOMATIC_DAS_OPEN_ORDERS,
            TypeImport::AUTOMATIC_DELIV_OPEN_ORDERS,
            TypeImport::AUTOMATIC_DUPONT_FC_NOCONT,
            TypeImport::AUTOMATIC_DAS_FC_NOCONT,
            TypeImport::AUTOMATIC_DAS_SHORT_FC_NOCONT,
            TypeImport::AUTOMATIC_DUPONT_SHORT_FC_NOCONT,
            TypeImport::AUTOMATIC_CRED_OPEN_ORDERS,
            TypeImport::FCNOCONT,
            TypeImport::DESPNOFC,
            TypeImport::AUTOMATIC_DUPONT_DESP_NOFC,
            TypeImport::AUTOMATIC_DAS_DESP_NOFC,
            TypeImport::FCASTIBP,
            TypeImport::AUTOMATIC_FCASTIBP,
        ]);
    }

    /**
     * @return Import
     */
    private function createImport()
    {
        $nuevoFilename = $this->slugify($this->getJobName()) . '-' . $this->getSuffixFilename();

        $import = new Import();
        $import->FinishedCorrectly = true;
        $import->WithErrors = false;
        $import->Name = $nuevoFilename;
        $import->TypeImportId = $this->getTypeImportId();
        $import->save();

        return $import;
    }

    /**
     * @return int
     */
    private function getActualCampaignId()
    {
        return Campaign::getActualCampaign()->CampaignId;
    }

    /**
     * @param $string
     * @return string
     */
    private function slugify($string)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }

    /**
     * @return string
     */
    private function getSuffixFilename()
    {
        $extension = 'csv';
        if($this->file){
            $extension = pathinfo($this->file, PATHINFO_EXTENSION);
        }
        return time() . '-' . uniqid() . '.' . $extension;
    }

    /**
     * @param array $gmids
     * @param string $gmid
     * @return bool
     */
    private function gmidEncontrado(array $gmids, string $gmid)
    {
        return isset($gmids[intval($gmid)]);
    }

    /**
     * @param array $elementosNoValidos
     * @param string $gmid
     * @param int $rowNumber
     * @param int $importId
     */
    private function pushGmidNoEncontrado(array &$elementosNoValidos, $elemento, int $rowNumber, int $importId)
    {
        $gmid = $elemento->gmid;

        if (!isset($elementosNoValidos[intval($gmid)])) {
            $elementosNoValidos[intval($gmid)] = [
                'gmid' => $gmid,
                'description' => "GMID no encontrado",
                'ImportId' => $importId,
                'rowNumber' => $rowNumber,
                'gmidDescription' => $elemento->gmidDescription ?? null,
                'country' => $elemento->country ?? null,
            ];
        }
    }

    /**
     * @param array $clientesUnificados
     * @param $client
     * @return bool
     */
    private function clienteEncontrado(array $clientesUnificados, string $client)
    {
        return isset($clientesUnificados[intval($client)]);
    }

    /**
     * @param array $clientesNoEncontrados
     * @param $elemento
     * @param int $importId
     * @param int $rowNumber
     */
    private function pushClienteNoEncontrado(array &$clientesNoEncontrados, $elemento, int $importId, int $rowNumber)
    {
        $client = $elemento->cliente;
        if (!isset($clientesNoEncontrados[intval($client)])) {
            $clientesNoEncontrados[intval($client)] = [
                'description' => "Cliente no encontrado",
                'ImportId' => $importId,
                'rowNumber' => $rowNumber,
                'client' => intval($client),
                'clientName' => $elemento->clientName ?? null,
                'country' => $elemento->country ?? null,
            ];
        }
    }

    /**
     * @param array $clientesNoEncontrados
     * @throws YiiDbException
     */
    private function guardarErroresDeClientesNoEncontrados(array $clientesNoEncontrados)
    {
        $connection = Yii::$app->db;

        $lots = array_chunk($clientesNoEncontrados, 1000);

        foreach ($lots as $lot) {
            $connection->createCommand()->batchInsert('import_error', [
                '[description]',
                '[ImportId]',
                '[rowNumber]',
                '[client]',
                '[clientName]',
                '[country]',
            ], $lot)->execute();
        }
    }

    /**
     * @return array
     */
    private function getAllGmids()
    {
        return (new Query())
            ->select('GmidId')
            ->from('gmid')
            ->indexBy('GmidId')
            ->all();
    }

    /**
     * @param array $clientesExcluidos
     * @param $client
     * @return bool
     */
    private function isClienteExcluido(array $clientesExcluidos, $client)
    {
        return in_array(intval($client), $clientesExcluidos);
    }

    /**
     * @param array $gmidsExcluidos
     * @param $gmid
     * @return bool
     */
    private function isGmidExcluido(array $gmidsExcluidos, $gmid)
    {
        return in_array(intval($gmid), $gmidsExcluidos);
    }

    /**
     * @param array $gmidsNoEncontrados
     * @throws YiiDbException
     */
    private function guardarErroresDeGmidsNoEncontrados(array $gmidsNoEncontrados)
    {
        $connection = Yii::$app->db;

        $lots = array_chunk($gmidsNoEncontrados, 1000);

        foreach ($lots as $lot) {
            $connection->createCommand()->batchInsert('import_error', [
                '[gmid]',
                '[description]',
                '[ImportId]',
                '[rowNumber]',
                '[gmidDescription]',
                '[country]',
            ], $lot)->execute();
        }
    }
}
