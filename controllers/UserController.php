<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 14.04.2019
 * Time: 13:17
 */

namespace app\controllers;

use app\models\Groups;
use app\models\SignupForm;
use app\models\User;
use app\models\UserSearch;
use yii\helpers\ArrayHelper;
use Yii;


class UserController  extends \yii\web\Controller
{
    public function actionIndex()
    {
        $userSearch = new UserSearch();
        $dataProvider = $userSearch->search(\Yii::$app->request->get());
        $dataProvider->setPagination([
            'pageSize' => 20
        ]);
        return $this->render('index', [
            'dataProvider'  => $dataProvider,
            'userSearch'    => $userSearch
        ]);
    }

    public function actionBan($id, $type)
    {
        $user = User::findOne($id);
        $user->is_ban = $type == 'block' ? 1 : 0;
        $user->save();
    }

    public function actionAdd()
    {
        $user = new User();
        $groups = ArrayHelper::map(Groups::find()->asArray()->all(), 'id', 'name');

        if($user->load(Yii::$app->request->post()) && $user->save()){
            $groups = Yii::$app->request->post('groups');
            $user->saveGroups($groups);
            $passord = $user->password;
            SignupForm::sentEmailToUser($user, $passord);
            $user->setPassword($user->password);
            return $this->redirect('index');
        }

        return $this->render('add', [
            'user'  => $user,
            'groups'    => $groups
        ]);
    }
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);
        $groups = ArrayHelper::map(Groups::find()->asArray()->all(), 'id', 'name');

        if ($user->load(Yii::$app->request->post()) && $user->save()) {
            $groups = Yii::$app->request->post('groups');
            $user->saveGroups($groups);
            return $this->redirect(['index']);
        }

        return $this->render('add', [
            'user' => $user,
            'groups'    => $groups
        ]);
    }


    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionView($id)
    {
        return $this->render('view');
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}