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
use app\models\Import;
use moonland\phpexcel\Excel;
use app\models\Task;
use app\models\TaskTable;
use app\models\TaskTableRows;

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

    public function actionImport($id)
    {
        $project = Project::findOne($id);
        $import = new Import();
        return $this->renderAjax('import', compact('project', 'import'));
    }

    /**
     * @param $id
     * @return string
     */
    public function actionFileUpload($id)
    {
        $project = Project::findOne($id);

        $data = [];
        if(isset($_FILES['Import']['tmp_name'])){
            $fileName = $_FILES['Import']['tmp_name']['file'];
            $tasks = Project::getRowTasks($fileName);
            foreach ($tasks as $tsk){
               $task = new Task();
                $task->name = $tsk['name'];
                $task->id_project = $id;
                $task->save(false);
                $my_table = new TaskTable();
                $my_table->id_task = $task->id;
                $my_table->save(false);
                foreach ($tsk['table'] as $tbl){
                    $rows_table = new TaskTableRows();
                    $rows_table->id_table = $my_table->id;
                    $rows_table->phrase = $tbl['C'];
                    $rows_table->base = $tbl['E'];
                    $rows_table->frequence_e = $tbl['F'];
                    $rows_table->frequence_f = $tbl['G'];
                    $rows_table->save();
                }
            }
            $data =  Json::encode([
                'count'  => count($tasks)
            ]);
        } else{
            $data = Json::encode(['err' => 'no tasks']);
        }

        return $this->renderAjax('upload', [
            'data'  => $data
        ]);
    }
}