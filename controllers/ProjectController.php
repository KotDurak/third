<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 28.02.2019
 * Time: 20:08
 */

namespace app\controllers;

use app\models\Project;
use app\models\ProjectSearch;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;


class ProjectController extends Controller
{
    public function actionIndex()
    {
        $projectSearch = new ProjectSearch();
        $dataProvider = $projectSearch->search(\Yii::$app->request->get());
        $dataProvider->setSort(['defaultOrder' => ['id' => SORT_DESC]]);


        return $this->render('index', compact(
            'projectSearch',
            'dataProvider'
        ));
    }

    public function actionList()
    {
        $projectSearch = new ProjectSearch();
        $dataProvider = $projectSearch->search(\Yii::$app->request->get());
        $dataProvider->setSort(['defaultOrder' => ['id' => SORT_DESC]]);

        return $this->renderAjax('list', compact('projectSearch', 'dataProvider'));
    }

    public function actionAdd()
    {
        $model = new Project();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
           if($model->validate()){
               $model->save(false);
               return Json::encode(array('status' => 'success', 'type' => 'success', 'message' => 'Contact created successfully.'));
           }
        }
        return $this->renderAjax('add', compact('model'));
    }

    public function actionEdit($id)
    {
        $model = Project::findOne($id);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            if($model->validate()){
                $model->save(false);
                return Json::encode(array('status' => 'success', 'type' => 'success', 'message' => 'Contact created successfully.'));
            }
        }
        return $this->renderAjax('add', compact('model'));
    }

    public function actionDelete($id)
    {
       $model = Project::findOne($id);
       if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            $model->delete();
            return Json::encode(array(['status' => 'succes', 'message' => 'delete']));
       }
       return $this->renderAjax('delete', compact('model'));
    }
}