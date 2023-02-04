<?php

namespace frontend\controllers;

use common\components\controllers\CustomController;
use common\models\InvoiceNotCounted;
use frontend\traits\UseDownloadExcel;

class InvoiceNotCountedController extends CustomController
{
    use UseDownloadExcel;

    public function actionIndex()
    {
        return $this->render('index', [
            'invoices' => InvoiceNotCounted::find()->all(),
        ]);
    }

    public function actionDownload()
    {
        $title = "FCNOCONT";
        $items = InvoiceNotCounted::find()->all();
        $attributes = (new InvoiceNotCounted())->attributeLabels();
        $this->downloadExcel($items, $attributes, $title);
    }

}
