<?php

namespace frontend\controllers;

use Yii;
use common\models\Client;
use common\models\ClientSearch;
use yii\web\NotFoundHttpException;

/**
 * ClientController implements the CRUD actions for Client model.
 */
class ClientController extends \common\components\controllers\CustomController {

    public function actionClientBySeller($SellerId = NULL, $ParentId = NULL) {
        \Yii::$app->response->format = 'json';
        $request = Yii::$app->request->get();

        if (is_null($ParentId)) {
            $clients = \common\models\Client::find()
                    ->select(['c.ClientId', 'c.Description', 'cou.Description AS Country', 'ct.Description AS Type', 'c.GroupId'])
                    ->from('client' . ' c')
                    ->innerJoin('client_seller' . ' cs', 'c.ClientId=cs.ClientId')
                    ->innerJoin('country' . ' cou', 'cou.CountryId = c.CountryId')
                    ->leftJoin('client_type' . ' ct', 'ct.ClientTypeId=c.ClientTypeId')
                    ->where(['cs.SellerId' => $request["filter"]["filters"][0]["value"],
                        'c.IsActive' => true,
                        'GroupId' => NULL
                    ])
                    ->andWhere(['not', ['c.Description' => 'OTROS']])
                    ->asArray()
                    ->all();
        } else {
            $connection = \Yii::$app->db;
            $clients = $connection->createCommand("SELECT c.ClientId ,
                                        c.Description,
                                        C.GroupId ,
                                        IsActive  = 'false'	   
                                        FROM client c
                                        LEFT JOIN client_seller csp
                                        ON csp.ClientId = c.ClientId
                                        WHERE csp.SellerId = {$SellerId} AND 
                                              c.GroupId IS NULL AND 
                                              c.ClientId <> {$ParentId} AND
                                              c.Description <> 'OTROS' AND 
                                              c.IsGroup = 0 
                                        UNION 
                                        SELECT c.ClientId ,
                                                   c.Description,
                                                   C.GroupId ,
                                                   IsActive  = 'true'	   
                                        FROM client c
                                        LEFT JOIN client_seller csp
                                        ON csp.ClientId = c.ClientId
                                        WHERE csp.SellerId = {$SellerId} AND 
                                                  c.GroupId  = {$ParentId}  AND
                                                  c.ClientId <> {$ParentId} AND 
                                                  c.Description <> 'OTROS' AND  
                                                  c.IsGroup = 0 ")->queryAll();
        }

        return $clients;
    }

    public function actionList() {
        \Yii::$app->response->format = 'json';
        $SellerId = \Yii::$app->user->identity->UserId;
        $clients = \common\models\Client::find()
                ->select(['c.ClientId', 'c.Description', 'cou.Description AS Country', 'ct.Description AS Type', 'c.GroupId', 'father.Description AS NameGroup'])
                ->from('client' . ' c')
                ->innerJoin('client_seller' . ' cs', 'c.ClientId=cs.ClientId')
                ->innerJoin('country' . ' cou', 'cou.CountryId = c.CountryId')
                ->leftJoin('client_type' . ' ct', 'ct.ClientTypeId=c.ClientTypeId')
                ->leftJoin('client' . ' father', 'father.ClientId = c.GroupId')
                ->where(['cs.SellerId' => $SellerId,
                    'c.IsActive' => true,
                    'c.IsGroup' => false,
                ])
                ->asArray()
                ->all();
        return $clients;
    }

    public function actionGroup() {

        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();

            $SellerId = (int) $request["SellerId"];
            $ClientId = (int) $request["ClientId"];

            Client::updateAll(['GroupId' => NULL, 'IsGroup' => 0], ['GroupId' => $ClientId]);
            $clientGroup = Client::findOne(['ClientId' => $ClientId]);
            if (isset($request["clients"])) {
                $clientsChildrens = Client::find()->filterWhere(['IN', 'ClientId', $request["clients"]])->all();
                $clientGroup->updateAttributes(['IsGroup' => 1]);

                foreach ($clientsChildrens as $client) {
                    $client->updateAttributes(['GroupId' => $clientGroup->ClientId, 'IsGroup' => 0]);
                }
            } else {
                $clientGroup->updateAttributes(['IsGroup' => 0]);
            }
            return;
        }
        return $this->render('groups', [
                    'sellers' => \common\models\User::find()->joinWith(['authAssignments'])->where(['item_name' => 'SELLER'])->asArray()->all(),
        ]);
    }

    /**
     * Lists all Client models.
     * @return mixed
     */
    public function actionIndex() {
        return $this->render('index', [
        ]);
    }

    /**
     * Lists all Client models.
     * @return mixed
     */
    public function actionProducts($id) {
        $connection = Yii::$app->db;

        if (Yii::$app->request->IsPost) {
            $request = Yii::$app->request->post();

            \common\models\ClientProduct::updateAll(["IsForecastable" => false], ['ClientId' => $id]);
            if (isset($request["clientsProducts"])) {
                \common\models\ClientProduct::updateAll(["IsForecastable" => true], ['in', 'ClientProductId', $request["clientsProducts"]]);
            }
            return;
        }
     
        $products = $connection->createCommand("     

select distinct cp.ClientProductId, cp.GmidId,cp.TradeProductId,g.Description,pc.Description AS PerformanceCenter,vc.Description AS ValueCenter, CASE WHEN cp.IsForecastable=1 THEN 'true' 
                                                                                                                                        ELSE 'false' END  AS IsForecastable				
from client_product cp
inner join trade_product tp on tp.TradeProductId = cp.TradeProductId
inner join client c on c.ClientId = cp.ClientId
inner join gmid g on g.GmidId = cp.GmidId AND g.CountryId = c.CountryId
inner join performance_center pc on pc.PerformanceCenterId  = tp.PerformanceCenterId
inner join value_center vc on vc.ValueCenterId = pc.ValueCenterId
where cp.ClientId = {$id} and g.IsActive = 1 

UNION

select distinct cp.ClientProductId,cp.GmidId,cp.TradeProductId,tp.Description,pc.Description AS PerformanceCenter,vc.Description AS ValueCenter, CASE WHEN cp.IsForecastable=1 THEN 'true' 
                                                                                                                                         ELSE 'false' END  AS IsForecastable					
from client_product cp
inner join trade_product tp on tp.TradeProductId = cp.TradeProductId
inner join client c 
on c.ClientId = cp.ClientId 
inner join gmid g 
on tp.TradeProductId = g.TradeProductId and g.CountryId = c.CountryId
inner join performance_center pc on pc.PerformanceCenterId  = tp.PerformanceCenterId
inner join value_center vc on vc.ValueCenterId = pc.ValueCenterId
where pc.ValueCenterId = 10111  and c.ClientId = {$id} and tp.IsActive = 1 and cp.GmidId is null
"
);


        return $this->render('products', [
                    'model' => $this->findModel($id),
                    'products' => $products->queryAll(),
        ]);
    }

    /**
     * Lists all Client models.
     * @return mixed
     */
    public function actionClients() {
        return $this->render('clients', [
        ]);
    }

    /**
     * Displays a single Client model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Client model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Client();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ClientId]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Client model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ClientId]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Client model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Client model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Client the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Client::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
