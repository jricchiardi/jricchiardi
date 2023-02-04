<?php

declare(strict_types=1);

namespace common\models\sis;

use common\models\AuthItem;
use common\models\User;
use Yii;
use yii\helpers\Url;

class RoleVerify
{
    use HasFilterUserLevel;

    const BASE_URL = 'sis';

    public function __construct($filters = null)
    {
        $this->filters = $filters ?? new SisFilters();
        $this->drillLevel = new DrillLevel();

//        var_dump(Yii::$app->getResponse()->redirect('http://newdomain.com')->send());die;
    }

    public function validate()
    {
        $roles = $this->getAvailableRoles();

        if(!in_array($this->getFilterUserLevel(), $roles)){
            $this->eject();
        }

    }

    private function getAvailableRoles(){
        if (
            Yii::$app->user->can(AuthItem::ROLE_DIRECTOR_COMERCIAL) ||
            Yii::$app->user->can(AuthItem::ROLE_SIS_ADMIN) ||
            Yii::$app->user->can(AuthItem::ROLE_ADMIN) ||
            Yii::$app->user->can(AuthItem::ROLE_PM) ||
            Yii::$app->user->can(AuthItem::ROLE_DSM) ||
            Yii::$app->user->can(AuthItem::ROLE_SIS_VIEWER)
        ){
            return ['Dsm', 'Tam', 'Client', 'Product'];
        }

        if (Yii::$app->user->can(AuthItem::ROLE_SELLER)){
            return ['Tam', 'Client', 'Product'];
        }

        return [];
    }

    private function eject(){

        $params = Yii::$app->request->get();
        $params['lvl'] = 'Tam';
        $params['selectedUser'] = User::findIdentity(Yii::$app->user->getId())->ParentId;
        if($this->getFilterUserLevel()==='Tam'){
            $params['lvl'] = 'Client';
        }elseif($this->getFilterUserLevel()==='Client'){
            $params['lvl'] = 'Product';
            $params['TamId'] = $this->getFilteredUser()->UserId;
        }

        $url = Url::to(self::BASE_URL . '?' . http_build_query($params));

        Yii::$app->getResponse()->redirect($url)->send();
    }
}