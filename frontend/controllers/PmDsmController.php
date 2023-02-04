<?php

namespace frontend\controllers;

use common\components\controllers\CustomController;
use Yii;

/**
 * Site controller
 */
class PmDsmController extends CustomController
{
    public function actionIndex()
    {
        $pmId = Yii::$app->user->identity->UserId;

        $dsm = Yii::$app->db->createCommand("
SELECT dsm.UserId                                             AS DsmId,
       dsm.Fullname,
       ctry.Description AS Country,
       CASE WHEN A.DsmId IS NULL THEN 'False' ELSE 'True' END AS IsRelated
FROM [user] dsm
         INNER JOIN auth_assignment aa on dsm.UserId = aa.user_id
         LEFT JOIN (SELECT DsmId FROM pm_dsm pd WHERE PmId = $pmId) AS A ON A.DsmId = dsm.UserId
         INNER JOIN [user] sellers ON sellers.ParentId = dsm.UserId
         INNER JOIN client_seller cs ON cs.SellerId = sellers.UserId
         INNER JOIN client_marketing cm ON cm.ClientMarketingId = cs.ClientId
         INNER JOIN country ctry ON ctry.CountryId = cm.CountryId
WHERE item_name = 'DSM'
  AND dsm.IsActive = 1
GROUP BY dsm.UserId, dsm.Fullname, ctry.Description, CASE WHEN A.DsmId IS NULL THEN 'False' ELSE 'True' END

        ")->queryAll();

        return $this->render('index', [
            'dsm' => $dsm,
        ]);
    }

    public function actionSetDsmIds()
    {
        if (Yii::$app->request->isPost) {

            $request = Yii::$app->request->post();

            if (isset($request['dsmIds'])) {
                $userId = Yii::$app->user->identity->UserId;

                Yii::$app->db->createCommand("DELETE FROM pm_dsm WHERE PmId = $userId")->execute();

                $dsmPms = array_filter($request['dsmIds'], function ($dsmId) {
                    return boolval($dsmId['isRelated']) === true;
                });

                if (!empty($dsmPms)) {
                    $dsmPms = array_map(function ($dsmPm) use ($userId) {
                        return [
                            'DsmId' => $dsmPm['dsmId'],
                            'PmId' => $userId,
                        ];
                    }, $dsmPms);

                    Yii::$app->db->createCommand()->batchInsert('pm_dsm', [
                        '[DsmId]',
                        '[PmId]',
                    ], $dsmPms)->execute();
                }
            }
        }
    }
}
