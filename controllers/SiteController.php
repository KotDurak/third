<?php

namespace app\controllers;

use app\models\ChainClonesSteps;
use app\models\Project;
use app\models\SignupForm;
use app\models\SignupService;
use app\models\Task;
use app\models\User;
use function GuzzleHttp\Psr7\str;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
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

        if(Yii::$app->user->identity->is_ban){
            $this->layout = false;
            Yii::$app->user->logout();
            return  $this->render('ban');
        }
        if(!Yii::$app->user->isGuest){
            $user = User::findOne(Yii::$app->user->id);
            $user->last_visit = date('Y-m-d H:i:s');
            $user->save();
        }

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
        $from = (!empty($post['from'])) ? date('Y-m-d 00:00:00', strtotime($post['from'])) : null;
        $to = (!empty($post['to'])) ? date('Y-m-d 23:59:59', strtotime($post['to'])) : null;
        $clone_steps = ChainClonesSteps::getStepsByDates($from, $to);
        return $this->renderAjax('tasks-date', [
            'clone_steps'   => $clone_steps
        ]);
    }

    public function actionAdminTaskProjects($id_project)
    {
        $tasks = Task::getTaskStatusesByProject($id_project);
        return $this->renderAjax('tasks-table', [
            'tasks' => $tasks
        ]);
    }

    public function actionAdminTaskUser($id_user)
    {
        $tasks = ChainClonesSteps::getStepsByWorker($id_user);
        return $this->renderAjax('task-user-table', [
            'tasks' => $tasks,
            'id_user'   => $id_user
        ]);
    }

    public function actionUserGroups($id_user)
    {
        $user = User::findOne($id_user);
        $groups = $user->getGroups()->asArray()->all();
        $groups = ArrayHelper::map($groups, 'id', 'name');
        return $this->renderAjax('positions', [
           'groups' => $groups
        ]);
    }

    public function actionAdminTasksDate()
    {
        $post = Yii::$app->request->post();
        $from = (!empty($post['from'])) ? date('Y-m-d 00:00:00', strtotime($post['from'])) : null;
        $to = (!empty($post['to'])) ? date('Y-m-d 23:59:59', strtotime($post['to'])) : null;
        $tasks = Task::getTasksByDate($from, $to);
        return $this->renderAjax('task-user-date', [
           'tasks'  => $tasks,
           'from'   => $from,
           'to'     => $to
        ]);
    }

    public function actionUsersSelect()
    {
        $GLOBALS['id_group'] = Yii::$app->request->post('id_group');
        if($GLOBALS['id_group'] != 0) {
            $users = User::find()->select(['CONCAT(`surname`, " " , `user`.`name`) as n', 'user.id'])->joinWith([
                'groups' => function ($query) {
                    $query->andWhere(['groups.id' => $GLOBALS['id_group']]);
                }
            ])->asArray()->all();
        } else{
            $users = User::find()->select(['CONCAT(`surname`, " " , `user`.`name`) as n', 'id', 'user.id'])->asArray()->all();

        }

        $users = ArrayHelper::map($users, 'id', 'n');
     //   print_pre($users); die();
        $keys = array_keys($users);
        array_unshift($keys,0);
        array_unshift($users, 'Не выбрано');
        $users = array_combine($keys, $users);

        return $this->renderAjax('users-select', [
            'users' => $users
        ]);
    }
}
