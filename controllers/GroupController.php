<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 14.04.2019
 * Time: 19:05
 */

namespace app\controllers;

use app\models\Groups;
use app\models\GroupsSearch;
use app\models\SignupForm;
use app\models\User;
use app\models\UserSearch;
use yii\helpers\ArrayHelper;
use Yii;



class GroupController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $groupSearch = new GroupsSearch;
        $dataProvider = $groupSearch->search(\Yii::$app->request->get());
        $dataProvider->setPagination([
            'pageSize' => 20
        ]);
        return $this->render('index', [
            'dataProvider'  => $dataProvider,
            'userSearch'    => $groupSearch
        ]);
    }

    public function actionAdd()
    {
        $model = new Groups();

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            $model->save();
            return true;
        }

        return $this->renderAjax('add', [
            'model' => $model
        ]);
    }

    public function actionUpdate($id)
    {
        $model = Groups::findOne($id);

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            $model->save();
            return true;
        }

        return $this->renderAjax('add', [
           'model'  => $model
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Groups::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}