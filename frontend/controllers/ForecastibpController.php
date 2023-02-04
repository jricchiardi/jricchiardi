<?php

namespace frontend\controllers;

use common\components\controllers\CustomController;
use common\models\FcastIBP;
use frontend\traits\UseDownloadExcel;

class ForecastibpController extends CustomController
{
    use UseDownloadExcel;

    public function actionIndex()
    {
        return $this->render('index', [
            'ForecastIBP' => FcastIBP::find()->all(),
        ]);
    }

    public function actionDownload()
    {
        $title = "ForecastIBP";
        $items = FcastIBP::find()->all();
        $attributes = (new FcastIBP())->attributeLabels();
        $this->downloadExcel($items, $attributes, $title);
    }

}
