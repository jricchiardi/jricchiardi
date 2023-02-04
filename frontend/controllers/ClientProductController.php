<?php

namespace frontend\controllers;

use Yii;
use common\models\ClientProduct;
use common\models\ClientProductSearch;
use yii\web\NotFoundHttpException;

/**
 * ClientProductController implements the CRUD actions for ClientProduct model.
 */
class ClientProductController extends \common\components\controllers\CustomController {

    public $news;

    public function actionListClient() {
        \Yii::$app->response->format = 'json';

        $clients = \common\models\Client::find()
                ->select(['c.ClientId', 'c.Description', 'cou.Description AS Country', 'ct.Description AS Type'])
                ->from('client' . ' c')
                ->innerJoin('client_seller' . ' cs', 'c.ClientId=cs.ClientId')
                ->leftJoin('country' . ' cou', 'cou.CountryId = c.CountryId')
                ->leftJoin('client_type' . ' ct', 'ct.ClientTypeId=c.ClientTypeId')
                ->where(['cs.SellerId' => Yii::$app->user->identity->UserId,
                    'c.IsActive' => TRUE,
                    'c.GroupId' => NULL,
                ])
                ->asArray()
                ->all();

        return $clients;
    }

    public function actionListProduct($ClientId = NULL) {

        \Yii::$app->response->format = 'json';
        $connection = Yii::$app->db;
        $client = \common\models\Client::find()->joinWith('sellers')->where(['UserId' => \Yii::$app->user->identity->UserId])->one();

        $products = $connection->createCommand("
                SELECT g.GmidId,	
                           TradeProductId = NULL,		
                           Description = g.Description,
                           tp.Description AS TradeProduct,
                           pc.Description AS PerformanceCenter ,
                           vc.Description AS ValueCenter,
                           tp.Price,
                           tp.Profit,
                           g.CountryId,
                           g.IsActive
                FROM gmid g
                INNER JOIN trade_product tp
                ON g.TradeProductId = tp.TradeProductId
                INNER JOIN performance_center pc 
                ON pc.PerformanceCenterId = tp.PerformanceCenterId
                INNER JOIN value_center vc 
                ON vc.ValueCenterId = pc.ValueCenterId
                WHERE g.IsForecastable = 1 AND g.IsActive = 1 AND g.CountryId = {$client->CountryId}
                UNION
                SELECT GmidId = NULL,
                           tp.TradeProductId,
                           Description = tp.Description,
                           tp.Description AS TradeProduct,
                           pc.Description AS PerformanceCenter ,
                           vc.Description AS ValueCenter,
                           tp.Price,
                           tp.Profit,
                           {$client->CountryId} AS CountryId,
                           tp.IsActive
                FROM trade_product tp
                INNER JOIN gmid g 
                ON g.TradeProductId = tp.TradeProductId
                INNER JOIN performance_center pc 
                ON pc.PerformanceCenterId = tp.PerformanceCenterId
                INNER JOIN value_center vc 
                ON vc.ValueCenterId = pc.ValueCenterId
                WHERE tp.IsForecastable = 1 AND tp.IsActive = 1 AND CountryId = {$client->CountryId}
                GROUP BY tp.TradeProductId,tp.Description,pc.Description,vc.Description,tp.Price,tp.Profit, tp.IsActive
");

        return $products->queryAll();
    }

    /**
     * Lists all ClientProduct models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ClientProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSetupProductClient() {

        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();
            $connection = Yii::$app->db;

            $clients = implode(",", $request["clients"]);

            ClientProduct::updateAll(["IsForecastable" => false], ['in', 'ClientId', $request["clients"]]);

            if (isset($request["gmids"])) {
                $gmids = implode(",", $request["gmids"]);
                $connection->createCommand("UPDATE client_product SET IsForecastable=1 WHERE  ClientId IN({$clients}) AND GmidId IN ({$gmids})")->execute();
            }
            if (isset($request["trades"])) {
                $trades = implode(",", $request["trades"]);
                $connection->createCommand("UPDATE client_product SET IsForecastable=1 WHERE  ClientId IN({$clients}) AND TradeProductId IN ({$trades})")->execute();
            }
            //   Yii::$app->getSession()->setFlash('success', 'Los productos fueron guardados correctamente');
            //   $this->redirect(['clientproduct/wizard']);
            return;
        }
    }

    /**
     * Lists all ClientProduct models.
     * @return mixed
     */
    public function actionWizard() {
        $searchModel = new ClientProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('wizard', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ClientProduct model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ClientProduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new ClientProduct();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ClientProductId]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ClientProduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ClientProductId]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ClientProduct model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ClientProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ClientProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ClientProduct::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
