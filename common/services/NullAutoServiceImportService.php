<?php

namespace common\services;

use common\models\Import;

class NullAutoServiceImportService extends AutoSapImportService
{
    public function doImport($filePath = null)
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getJobName()
    {
        return "Null job";
    }

    /**
     * @inheritDoc
     */
    protected function getTypeImportId()
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    protected function createElemento(array $data, $campaignId, $importId, int $rowNumber)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    protected function doImportToDb(Import $import, array $elementosValidos)
    {
        return;
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
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function exitWhenGmidNotFound()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function exitWhenFailedElement()
    {
        return true;
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
        return true;
    }
}
