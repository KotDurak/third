<?php


namespace app\controllers;

use app\models\LoginForm;
use app\models\SignupForm;
use app\models\User;
use yii\web\Controller;

use Yii;

class AuthController extends Controller
{
    public function actionSignup()
    {

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = $model->signup();
            Yii::$app->session->setFlash('success', 'Проверьте ващ почтовый ящик');
            $model->sentEmailConfirm($user);
            return $this->goHome();
        }
        return $this->render('signup', [
            'model' => $model
        ]);
    }

    public function actionSignupConfirm($token)
    {
        $signupForm = new SignupForm();
        try {
            $signupForm->confirmation($token);
            Yii::$app->session->setFlash('success', 'Вы успешно завершили регистрацию');
        } catch (\Exception $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->goHome();
    }

    public function actionLogin()
    {
        if(!Yii::$app->user->isGuest){
            return $this->goHome();
        }
        $model = new LoginForm();
        if($model->load(Yii::$app->request->post()) && $model->login()){
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model
        ]);
    }
}