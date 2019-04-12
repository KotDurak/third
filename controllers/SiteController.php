<?php

namespace app\controllers;

use app\models\ChainClonesSteps;
use app\models\Project;
use app\models\SignupForm;
use app\models\SignupService;
use app\models\User;
use function GuzzleHttp\Psr7\str;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
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
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if(!Yii::$app->user->isGuest && Yii::$app->user->identity->is_admin()){

        } else{

        }
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Получает спиосок задач сотрудника по проектам;
     *
     * @param $id_prohect
     */
    public function actionProjectTasks($id_project)
    {
        $clone_steps = ChainClonesSteps::getStepsByProject($id_project);
        return $this->renderAjax('tasks-project', [
            'clone_steps'   => $clone_steps
        ]);
    }

    public function actionUsersTasksDate()
    {
        $post = Yii::$app->request->post();
        $from = (!empty($post['from'])) ? date('Y-m-d H:i:s', strtotime($post['from'])) : null;
        $to = (!empty($post['to'])) ? date('Y-m-d H:i:s', strtotime($post['to'])) : null;
        $clone_steps = ChainClonesSteps::getStepsByDates($from, $to);
        die();
    }
}
