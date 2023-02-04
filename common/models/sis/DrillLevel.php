<?php

namespace common\models\sis;

use common\models\Client;
use common\models\User;
use Yii;

class DrillLevel
{
    use HasFilterUserLevel;

    const BASE_URL = '/forecast/sis';
    const BASE_API_URL = '/forecast/sis/detail';

    public function getBreadCrumb(){
        $urls = [];

        if('Dsm' == $this->getFilterUserLevel()){

            $urls['INICIO'] = '#';

        }

        if('Tam' == $this->getFilterUserLevel()){
            $params = Yii::$app->request->get();
            unset($params['lvl']);
            unset($params['selectedUser']);
            $urls['INICIO'] = self::BASE_URL . '?' . http_build_query($params);

            $urls[$this->getFilteredUser()->Fullname] = '#';
        }

        if('Client' == $this->getFilterUserLevel()){
            $currentUser = $this->getFilteredUser();

            $params = Yii::$app->request->get();
            unset($params['lvl']);
            unset($params['selectedUser']);
            $urls['INICIO'] = self::BASE_URL . '?' . http_build_query($params);

            $params = Yii::$app->request->get();
            $params['lvl'] = 'Tam';
            $params['selectedUser'] = $currentUser->ParentId;
            $urls[User::findIdentity($currentUser->ParentId)->Fullname] = self::BASE_URL . '?' . http_build_query($params);

            $urls[$currentUser->Fullname] = '#';
        }

        if('Product' == $this->getFilterUserLevel()){
            $tamUser = User::findIdentity(Yii::$app->request->get('TamId'));

            $params = Yii::$app->request->get();
            unset($params['lvl']);
            unset($params['selectedUser']);
            unset($params['TamId']);
            $urls['INICIO'] = self::BASE_URL . '?' . http_build_query($params);

            $params = Yii::$app->request->get();
            unset($params['TamId']);
            $params['lvl'] = 'Tam';
            $params['selectedUser'] = $tamUser->ParentId;
            $urls[User::findIdentity($tamUser->ParentId)->Fullname] = self::BASE_URL . '?' . http_build_query($params);


            $params = Yii::$app->request->get();
            unset($params['TamId']);
            $params['lvl'] = 'Client';
            $params['selectedUser'] = $tamUser->UserId;
            $urls[$tamUser->Fullname] = self::BASE_URL . '?' . http_build_query($params);

            $clientUser = Client::findOne(['ClientId' => $this->getFilterUser()]);
            $urls[$clientUser->Description] = '#';
        }

        return $urls;
    }
    public function getDrillDownUrl($baseUrl = self::BASE_URL){
        $params = Yii::$app->request->get();
        $params['lvl'] = 'Tam';
        if($this->getFilterUserLevel()==='Tam'){
            $params['lvl'] = 'Client';
        }elseif($this->getFilterUserLevel()==='Client'){
            $params['lvl'] = 'Product';
            $params['TamId'] = $this->getFilteredUser()->UserId;
        }
        unset($params['selectedUser']);

        return $baseUrl . '?' . http_build_query($params).'&selectedUser=';
    }

    public function getDrillUpUrl(){
        $params = Yii::$app->request->get();
        if($this->getFilterUserLevel()=='Client'){
            $params['lvl'] = 'Tam';
            $params['selectedUser'] = $this->getFilteredUser()->UserId;
        }elseif($this->getFilterUserLevel()=='Product'){
            $params['lvl'] = 'Client';
            $params['selectedUser'] = Yii::$app->request->get('TamId');
        }else{
            unset($params['lvl']);
            unset($params['selectedUser']);
        }

        return self::BASE_URL . '?' . http_build_query($params);
    }

    public function getDrillDownApiUrl()
    {
        return $this->getDrillDownUrl(self::BASE_API_URL);
    }
}