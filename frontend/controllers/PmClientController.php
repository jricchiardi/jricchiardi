<?php

namespace frontend\controllers;

use common\components\controllers\CustomController;
use Yii;

/**
 * Site controller
 */
class PmClientController extends CustomController
{
    public function actionIndex()
    {
        $userId = Yii::$app->user->identity->UserId;

        $clients = Yii::$app->db->createCommand("
SELECT c.ClientMarketingId, ctry.Description AS Country, c.Description, CASE WHEN A.ClientId IS NULL THEN 'False' ELSE 'True' END AS IsRelated
FROM client_marketing c
         LEFT JOIN (
    SELECT ClientId
    FROM pm_client
    WHERE UserId = $userId
) AS A ON A.ClientId = c.ClientMarketingId
INNER JOIN country ctry ON ctry.CountryId = c.CountryId
ORDER BY Country, Description
        ")->queryAll();

        return $this->render('index', [
            'clients' => $clients,
        ]);
    }

    public function actionSetClientIds()
    {
        if (Yii::$app->request->isPost) {

            $request = Yii::$app->request->post();

            if (isset($request['clientIds'])) {

                $userId = Yii::$app->user->identity->UserId;

                Yii::$app->db->createCommand("DELETE FROM pm_client WHERE UserId = $userId")->execute();

                $clientPms = array_filter($request['clientIds'], function ($clientId) {
                    return boolval($clientId['isRelated']) === true;
                });

                if (!empty($clientPms)) {
                    $clientPms = array_map(function ($clientPm) use ($userId) {
                        return [
                            'ClientId' => $clientPm['clientMarketingId'],
                            'UserId' => $userId,
                        ];
                    }, $clientPms);

                    Yii::$app->db->createCommand()->batchInsert('pm_client', [
                        '[ClientId]',
                        '[UserId]',
                    ], $clientPms)->execute();
                }
            }
        }

        return;
    }
}
