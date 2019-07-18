<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;

class SiteController extends Controller
{
	public function beforeAction($action){

        if (in_array($action->id,['login']))
            {
            $this->enableCsrfValidation = false;
            }

	    if (parent::beforeAction($action)) {
       		if (Yii::$app->user->isGuest && $action->id != 'login') {
				return Yii::$app->response->redirect(['site/login'])->send();
	        } else return true;
	    } else {
			return false;
		}
	}

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post', 'get'],
                ],
            ],
        ];
    }

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

    public function actionIndex()
    {
        //return $this->redirect('/index.php/akcii/index/');
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
			//$userRole = Yii::$app->authManager->getRole('root');
			//Yii::$app->authManager->assign($userRole, Yii::$app->user->getId());

            Yii::$app->db->createCommand()->update(
                User::tableName(),
                [
                    'lastlogin'     => new \yii\db\Expression('NOW()'),
                    'ip'            => $_SERVER['HTTP_X_REAL_IP']?:Yii::$app->request->getUserIP(),
                    'login_count'   => new \yii\db\Expression('`login_count` + 1')
                    ],
                ['id'=>Yii::$app->user->id]
                )->execute();

            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect('/index.php/site/login/');
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact',[
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionPlanogram()
        {
        return $this->render('planogram');
        }
}
