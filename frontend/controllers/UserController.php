<?php

namespace frontend\controllers;

use common\components\controllers\CustomController;
use common\models\AuthItem;
use frontend\models\ChangePasswordForm;
use Yii;
use common\models\User;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use frontend\models\SignupForm;
use frontend\models\ResetPasswordForm;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends CustomController
{
    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $UserId = Yii::$app->user->identity->UserId;
        $users = User::find()->select(['user.UserId', 'user.DowUserId', 'user.Fullname', 'user.Username', 'user.Email', '[auth_item].[name] AS Type', 'p.Fullname AS Father'])
            ->innerJoinWith('itemNames', 'user.parent')
            ->leftJoin('user' . ' p', 'p.UserId = [user].ParentId');
        if (!Yii::$app->user->can(AuthItem::ROLE_ADMIN) && !Yii::$app->user->can(AuthItem::ROLE_DIRECTOR_COMERCIAL) && !Yii::$app->user->can(AuthItem::ROLE_SIS_ADMIN)) {
            $users->andWhere(['p.UserId' => $UserId]);
        }

        $users = $users->asArray()->all();

        return $this->render('index', [
            'users' => $users,
        ]);
    }

    public function actionSignup()
    {
        $roles = [
            'admin' => Yii::t('app', 'Administrator'),
            'Director Comercial' => Yii::t('app', 'Comertial Director'),
            'PM' => "Product Manager",
			AuthItem::ROLE_SIS_ADMIN => Yii::t('app', 'Administrator SIS'),
            AuthItem::ROLE_SIS_VIEWER => Yii::t('app', 'Viewer SIS'),
        ];

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->signup('admin')) {
                return $this->redirect(['index']);
            }
        }
        return $this->render('signup', [
            'model' => $model,
            'roles' => $roles,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->UserId]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->UserId]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionResetPassword($token = NULL)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {

            Yii::$app->getSession()->setFlash('success', Yii::t("app", 'The new password was saved correctly.'));

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Change User password.
     *
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionChangePassword()
    {
        $id = Yii::$app->user->id;

        try {
            $model = new ChangePasswordForm($id);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->changePassword()) {
//            \Yii::$app->session->setFlash('success', 'Password Changed!');
            Yii::$app->getSession()->setFlash('success', Yii::t("app", 'The new password was saved correctly.'));
            return $this->goHome();
        }

        return $this->render('changePassword', [
            'model' => $model,
        ]);
    }
}
