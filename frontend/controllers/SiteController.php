<?php

namespace frontend\controllers;

use common\models\AuthItem;
use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;

use League\OAuth2\Client\Provider\Microsoft;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;


$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '\..\..\\');
$dotenv->load();
$dotenv->required(['CLIENT_ID', 'TENANT_ID', 'GRAPH_USER_SCOPES']);

/**
 * Site controller
 */
class SiteController extends Controller
{


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'about'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['about'],
                        'allow' => true,
                        'roles' => ['stc'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionLogin_ad()
    {

        // $_ENV['CLIENT_ID'];
        // $_ENV['TENANT_ID'];
        // $_ENV['GRAPH_USER_SCOPES'];

        $provider = new Microsoft([
            'clientId'     => '{your-client-id}',
            'clientSecret' => '{your-client-secret}',
            'redirectUri'  => 'https://example.com/callback-url',
        ]);

        echo '<pre>';
        var_dump($_ENV['CLIENT_ID']);
        die();
        // $graph = new GraphHelper;
        // $graph->initializeGraphForUserAuth();
        // var_dump($graph->getUser());
        // die();
        // $user = $graph->getUser();


    }

    public function actionIndex()
    {

        /* IF NOT LOGGED REDIRECT TO SCREEN LOGIN */
        if (is_null(\Yii::$app->user->identity))
            $this->redirect(['/site/login']);
        /* IF SHOULD RESET AT PASSWORD REDIRECT TO RESET-PASSWORD */
        elseif (\Yii::$app->user->identity->resetPassword && !\Yii::$app->session->get('forceAccess'))
            $this->redirect(['/site/reset-password']);
        /**/
        else {
            /* IS LOGGED */
            if (!\Yii::$app->user->isGuest) {
                Yii::$app->auditcomponents->createAudit([
                    'UserId' => \Yii::$app->user->identity->UserId,
                    'TypeAuditId' => \common\models\TypeAudit::TYPE_LOGIN,
                ]);

                if (Yii::$app->user->identity->authAssignment->item_name === AuthItem::ROLE_PM) {
                    return $this->redirect(['/forecast-marketing']);
                }

                if (Yii::$app->user->identity->authAssignment->item_name === AuthItem::ROLE_SIS_ADMIN) {
                    return $this->redirect(['/check-auto-sap-import']);
                }


                if (Yii::$app->user->identity->authAssignment->item_name === AuthItem::ROLE_SIS_VIEWER) {
                    return $this->redirect(['/sis']);
                }

                $dashBoardFilter = new \common\components\models\FilterDashboard();

                $dashBoardFilter->load(Yii::$app->request->post());

                $results = Yii::$app->dashboardcomponent->generateDashBoard($dashBoardFilter);

                return $this->render('index', [
                    'results' => $results,
                    'dashBoardFilter' => $dashBoardFilter
                ]);
            }
        }

        return $this->redirect(['/site/login']);
    }

    public function actionLogin()
    {
        $this->layout = 'login';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }



    public function actionRequestPasswordReset()
    {
        $this->layout = 'login';
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', Yii::t("app", 'Check your email and follow the instructions to reset your E-Mail'));
                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', Yii::t("app", 'We were unable to reset your email contact with your Administrator'));
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token = NULL)
    {
        $this->layout = 'password';
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

    public function actionPerformances()
    {
        return $this->render('performance/index');
    }
    public function actionTrade()
    {
        return $this->render('trade/index');
    }

    public function actionPresentation()
    {
        return $this->render('presentation/index');
    }

    public function actionImportClient()
    {
        return $this->render('import/importClient');
    }

    public function actionImportProduct()
    {
        return $this->render('import/importProduct');
    }

    public function actionClient()
    {
        return $this->render('client/index');
    }

    public function actionPrice()
    {
        return $this->render('price/index');
    }


    public function actionCampaign()
    {
        return $this->render('campaign/index');
    }

    public function actionUser()
    {
        return $this->render('user/index');
    }
}
