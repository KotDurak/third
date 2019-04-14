<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 14.04.2019
 * Time: 13:17
 */

namespace app\controllers;

use app\models\Groups;
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

        if(Yii::$app->request->isAjax && $user->load(Yii::$app->request->post())){
            $groups = Yii::$app->request->post('groups');
            print_pre($groups); die();
        }

        return $this->renderAjax('add', [
            'user'  => $user,
            'groups'    => $groups
        ]);
    }
}