<?php

namespace common\components\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Custom Controller
 */
class CustomController extends Controller {

    public function beforeAction($action) {
		$this->enableCsrfValidation = false;
        if (\Yii::$app->user->isGuest && is_null(\Yii::$app->user->getIdentity())) {
            header('Location: ' . \Yii::$app->getHomeUrl());
        } elseif (\Yii::$app->user->identity->resetPassword && !\Yii::$app->session->get('forceAccess')) {
            return $this->redirect(['/site/reset-password']);
        } else {
            return parent::beforeAction($action);
        }
    }

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['get', 'post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

}
