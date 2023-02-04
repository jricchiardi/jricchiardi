<?php

namespace frontend\controllers;

use common\components\controllers\CustomController;
use common\models\OpenOrders;
use frontend\traits\UseDownloadExcel;

class OpenOrdersController extends CustomController
{
    use UseDownloadExcel;

    public function actionIndex()
    {
        return $this->render('index', [
            'openOrders' => OpenOrders::find()->all(),
        ]);
    }

    public function actionDownload()
    {
        $title = "Open Orders";
        $items = OpenOrders::find()->all();
        $attributes = (new OpenOrders())->attributeLabels();
        $this->downloadExcel($items, $attributes, $title);
    }


}

