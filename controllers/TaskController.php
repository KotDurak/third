<?php

namespace app\controllers;

use app\models\Chain;
use app\models\TaskEdit;
use yii\data\ActiveDataProvider;
use app\models\Task;
use app\models\TaskSearch;
use yii\db\Query;
use app\models\SelectUserStep;
use yii\widgets\ActiveForm;

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
        $modelEdit = new TaskEdit();
        return $this->render('update', [
            'task'  => $task,
            'modelEdit' => $modelEdit
        ]);
    }


    /**
     * Ajax загрузка цепочки этапов;
     *
     * @param null $q
     * @param null $id
     * @return array
     */
    public function actionChainList($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        $query = new Query();
        if(!is_null($q)){
            $query->select('id, name as text')
                ->from('chain')
                ->where(['like', 'name', $q])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } else if($id > 0){
            $out['results'] = ['id' => $id, 'text' => Chain::find($id)->name];
        } else{
            $query->select('id, name as text')
                ->from('chain');
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        return $out;
    }

    public function actionAddFields($id)
    {
        $form = new ActiveForm();
        $chain = Chain::findOne($id);
        $modelSteps = [];
        foreach ($chain->getSteps()->orderBy(['sort' => SORT_ASC])->all() as $step){
            $modelSteps[] = new SelectUserStep([
                'id_step' => $step->id,
                'label'   => $step->name,
                'id_group'   => $step->id_group
            ]);
        }
        return $this->renderAjax('add-fields', [
            'form'  => $form,
            'chain' => $chain,
            'modelSteps'   => $modelSteps
        ]);

    }

}
