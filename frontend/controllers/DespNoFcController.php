<?php

namespace frontend\controllers;

use common\components\controllers\CustomController;
use common\models\DespNoFc;
use frontend\traits\UseDownloadExcel;

class DespNoFcController extends CustomController
{
    use UseDownloadExcel;

    public function actionIndex()
    {
        return $this->render('index', [
            'invoices' => DespNoFc::find()->all(),
        ]);
    }

    public function actionDownload()
    {
        $title = "Desp No Fc";
        $items = DespNoFc::find()->all();
        $attributes = (new DespNoFc())->attributeLabels();
        $this->downloadExcel($items, $attributes, $title);
    }


}
