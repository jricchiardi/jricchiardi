<?php

namespace common\models\sis;

use common\models\User;
use Yii;

trait HasFilterUserLevel
{
    static $filteredUser;

    private function getFilterUserLevel(){
        return Yii::$app->request->get('lvl') ?? 'Dsm';
    }

    private function getFilteredUser(){
        if(empty(self::$filteredUser)){
            self::$filteredUser = User::findIdentity($this->getFilterUser());
        }
        return self::$filteredUser;
    }

    private function getFilterUser(){
        return (int)Yii::$app->request->get('selectedUser') ?? null;
    }
}