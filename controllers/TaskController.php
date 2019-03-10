<?php

namespace app\controllers;

use yii\data\ActiveDataProvider;
use app\models\Task;
use app\models\TaskSearch;

class TaskController extends \yii\web\Controller
{
    public function actionIndex()
    {

        return $this->render('index');
    }

    public function actionList($id_project, $page_size = 10)
    {
        $taskSearch = new TaskSearch();
        $dataProvider = $taskSearch->search(\Yii::$app->request->get());
        $dataProvider->setPagination([
            'pageSize'  => $page_size
        ]);
       /* $dataProvider = new ActiveDataProvider([
            'query' => Task::find()->where(['id_project' => $id_project]),
            'pagination'    => [
                'pageSize'  => $page_size
            ]
        ]);*/
        return $this->render('list', [
            'dataProvider'  => $dataProvider,
            'taskSearch'    => $taskSearch
        ]);
    }

    public function actionUpdate($id)
    {
        $task = Task::findOne($id);
        return $this->render('update', [
            'task'  => $task,
        ]);
    }

}
