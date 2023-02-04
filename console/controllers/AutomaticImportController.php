<?php

namespace console\controllers;

use common\services\AutoSapImportDasCyoService;
use common\services\AutoSapImportDasSalesSrvice;
use common\services\AutoSapImportDupontCyoService;
use common\services\AutoSapImportDupontSalesService;
use common\services\AutoSapImportService;
use Exception;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Manage jobs to automatic import SAP csv files
 */
class AutomaticImportController extends Controller
{
    /**
     * @var boolean
     */
    private $showOutput;

    /**
     * @var AutoSapImportService
     */
    private $importService;

    /**
     * Import SAP DAS CyOs csv
     * @param bool $showOutput
     * @return int
     * @throws Exception
     */
    public function actionDasCyo($showOutput = false)
    {
        $this->showOutput = $showOutput;

        $this->importService = new AutoSapImportDasCyoService();

        return $this->doImport();
    }

    /**
     * Import SAP DAS Sales csv
     * @param bool $showOutput
     * @return int
     * @throws Exception
     */
    public function actionDasSales($showOutput = false)
    {
        $this->showOutput = $showOutput;

        $this->importService = new AutoSapImportDasSalesSrvice();

        return $this->doImport();
    }

    /**
     * Import SAP Dupont CyOs csv
     * @param bool $showOutput
     * @return int
     * @throws Exception
     */
    public function actionDupontCyo($showOutput = false)
    {
        $this->showOutput = $showOutput;

        $this->importService = new AutoSapImportDupontCyoService();

        return $this->doImport();
    }

    /**
     * Import SAP Dupont Sales csv
     * @param bool $showOutput
     * @return int
     * @throws Exception
     */
    public function actionDupontSales($showOutput = false)
    {
        $this->showOutput = $showOutput;

        $this->importService = new AutoSapImportDupontSalesService();

        return $this->doImport();
    }

    /**
     * @return int
     */
    private function doImport()
    {
        if ($this->showOutput) {
            $this->stdout("Corriendo importación automática {$this->importService->getJobName()}\n", Console::FG_YELLOW, Console::ITALIC);
        }

        try {
            $errors = $this->importService->doImport();

            if (count($errors) > 0) {
                if ($this->showOutput) {
                    $this->printErrors($errors);
                }
            }

            if ($this->showOutput) {
                $this->stdout("\nEl import finalizó\n", Console::FG_GREEN, Console::BOLD);
            }

            return self::EXIT_CODE_NORMAL;
        } catch (Exception $e) {
            $this->stdout("Ocurrió un error inesperado durante la ejecución\n", Console::FG_YELLOW, Console::ITALIC);
            $this->stdout($e->getMessage());
            return self::EXIT_CODE_ERROR;
        }
    }

    /**
     * @param array $errors
     */
    private function printErrors(array $errors)
    {
        $cantErrores = count($errors);

        $this->stdout("\nEl import tuvo $cantErrores errores:\n", Console::FG_RED, Console::UNDERLINE);

        foreach ($errors as $error) {
            $this->stdout("GMID", Console::FG_YELLOW, Console::UNDERLINE);
            $gmid = empty($error['gmid']) ? "-" : $error['gmid'];
            $this->stdout(": $gmid");
            $this->stdout(" || ");
            $this->stdout("Descripcion", Console::FG_YELLOW, Console::UNDERLINE);
            $description = empty($error['description']) ? "-" : $error['description'];
            $this->stdout(": $description");
            $this->stdout(" || ");
            $this->stdout("Mes", Console::FG_YELLOW, Console::UNDERLINE);
            $month = empty($error['month']) ? "-" : $error['month'];
            $this->stdout(": $month");
            $this->stdout(" || ");
            $this->stdout("Cliente", Console::FG_YELLOW, Console::UNDERLINE);
            $client = empty($error['client']) ? "-" : $error['client'];
            $this->stdout(": $client");
            $this->stdout(" || ");
            $this->stdout("Causa", Console::FG_YELLOW, Console::UNDERLINE);
            $cause = empty($error['cause']) ? "-" : $error['cause'];
            $this->stdout(": $cause");
            $this->stdout("\n");
        }

        $this->stdout("\n");
    }
}
