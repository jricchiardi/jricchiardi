<?php

namespace frontend\controllers;

use Yii;
use common\models\TradeProduct;
use common\models\TradeProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends \common\components\controllers\CustomController {

    public function actionSave() {
        $connection = Yii::$app->db;
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();

            \common\models\ClientProduct::updateAll(["IsForecastable" => false], ['IsForecastable' => true]);

            if (isset($request["gmids"])) {
                \common\models\Gmid::updateAll(["IsActive" => false], ['IsForecastable' => true]);
                \common\models\Gmid::updateAll(["IsActive" => true], ['in', 'GmidId', $request["gmids"]]);
                \common\models\ClientProduct::updateAll(["IsForecastable" => true], ['in', 'GmidId', $request["gmids"]]);
                $connection->createCommand("UPDATE client_product set IsForecastable = 0 
                                            FROM client_product cp 
                                            INNER JOIN client c 
                                            ON cp.ClientId = c.ClientId
                                            INNER JOIN gmid g 
                                            ON g.GmidId = cp.GmidId AND g.CountryId <> c.CountryId")
                        ->execute();
            }

            if (isset($request["trades"])) {
                \common\models\TradeProduct::updateAll(["IsActive" => false], ['IsForecastable' => true]);
                \common\models\TradeProduct::updateAll(["IsActive" => true], ['in', 'TradeProductId', $request["trades"]]);


                \common\models\ClientProduct::updateAll(["IsForecastable" => true], ['in', 'TradeProductId', $request["trades"]]);

                /* only update the trades per country */

               $connection->createCommand("UPDATE client_product set IsForecastable = 0 
                                            FROM client_product cp 
                                            inner join
                                            (
                                                    select cp.ClientProductId,g.TradeProductId 
                                                    from client_product cp
                                                    inner join trade_product tp 
                                                    on cp.TradeProductId = tp.TradeProductId and tp.IsForecastable = 1
                                                    inner join gmid g 
                                                    on tp.TradeProductId = g.TradeProductId
                                                    inner join client c
                                                    on c.ClientId = cp.ClientId AND c.CountryId <> g.CountryId
                                                    group by cp.ClientProductId,g.TradeProductId
                                            ) diff
                                            on diff.ClientProductId = cp.ClientProductId
                                            ")
                        ->execute();
            }
            return;
        }
    }

    /**
     * Lists all TradeProduct models.
     * @return mixed
     */
    public function actionIndex() {
        $connection = Yii::$app->db;
        $products = $connection->createCommand("SELECT   [GmidId]
                                                      ,[TradeProductId]
                                                      ,[Description]
                                                      ,[TradeProduct]
                                                      ,[PerformanceCenter]
                                                      ,[ValueCenter]
                                                      ,[Price]
                                                      ,[Profit]
                                                      ,[CountryId]
                                                      ,IsActive = CASE WHEN IsActive = 1 THEN 'True' ELSE 'False' END
                                               FROM dbo.GmidUnionTrade product
                                               ");

        return $this->render('index', [
                    'products' => $products->queryAll(),
        ]);
    }

    /**
     * Displays a single TradeProduct model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TradeProduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TradeProduct();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->TradeProductId]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TradeProduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->TradeProductId]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TradeProduct model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TradeProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TradeProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TradeProduct::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
