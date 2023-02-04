<?php

namespace frontend\controllers;

use Yii;
use common\models\Sale;
use common\models\SaleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SaleController implements the CRUD actions for Sale model.
 */
class SaleController extends \common\components\controllers\CustomController {
   

    /**
     * Lists all Sale models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SaleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sale model.
     * @param integer $CampaignId
     * @param integer $ClientId
     * @param string $GmidId
     * @param integer $Month
     * @return mixed
     */
    public function actionView($CampaignId, $ClientId, $GmidId, $Month)
    {
        return $this->render('view', [
            'model' => $this->findModel($CampaignId, $ClientId, $GmidId, $Month),
        ]);
    }

    /**
     * Creates a new Sale model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sale();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'CampaignId' => $model->CampaignId, 'ClientId' => $model->ClientId, 'GmidId' => $model->GmidId, 'Month' => $model->Month]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Sale model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $CampaignId
     * @param integer $ClientId
     * @param string $GmidId
     * @param integer $Month
     * @return mixed
     */
    public function actionUpdate($CampaignId, $ClientId, $GmidId, $Month)
    {
        $model = $this->findModel($CampaignId, $ClientId, $GmidId, $Month);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'CampaignId' => $model->CampaignId, 'ClientId' => $model->ClientId, 'GmidId' => $model->GmidId, 'Month' => $model->Month]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Sale model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $CampaignId
     * @param integer $ClientId
     * @param string $GmidId
     * @param integer $Month
     * @return mixed
     */
    public function actionDelete($CampaignId, $ClientId, $GmidId, $Month)
    {
        $this->findModel($CampaignId, $ClientId, $GmidId, $Month)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Sale model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $CampaignId
     * @param integer $ClientId
     * @param string $GmidId
     * @param integer $Month
     * @return Sale the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($CampaignId, $ClientId, $GmidId, $Month)
    {
        if (($model = Sale::findOne(['CampaignId' => $CampaignId, 'ClientId' => $ClientId, 'GmidId' => $GmidId, 'Month' => $Month])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
