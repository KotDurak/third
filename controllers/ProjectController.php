<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 28.02.2019
 * Time: 20:08
 */

namespace app\controllers;

use app\models\Chain;
use app\models\Groups;
use app\models\ModelMultiple;
use app\models\Project;
use app\models\ProjectSearch;
use app\models\Steps;
use app\models\User;
use Yii;
use yii\db\Query;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\Import;
use moonland\phpexcel\Excel;
use app\models\Task;
use app\models\TaskTable;
use app\models\TaskTableRows;
use app\models\SelectUserStep;
use yii\helpers\ArrayHelper;
use app\models\ChainClones;
use app\models\ChainClonesSteps;
use app\models\AttributesValues;

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

    /**
     * Импорт задач в проект
     *
     * @param $id
     * @return string
     */
    public function actionImport($id)
    {
        $project = Project::findOne($id);
        $url = Yii::$app->request->url;
        $import = new Import();
        if(!empty(Yii::$app->request->post()) && $import->load(Yii::$app->request->post())){
            $deadline = date('Y-m-d H:i:s', strtotime($import->deadline));
            $tasks = $project->getTasks()->where(['status' => '0'])->all();
            $modelSteps = ModelMultiple::createMultiple(SelectUserStep::className());
            $post = Yii::$app->request->post();
            ModelMultiple::loadMultiple($modelSteps, Yii::$app->request->post());
            $id_chain = $post['Import']['id_chain'];
            $tmp_step = Steps::find()->where(['id_chain' => $id_chain])->orderBy(['id' => SORT_ASC])->one();

            $i = 1;
            foreach ($tasks as $task){
                $chainClone = new ChainClones();
                $chainClone->id_chain = $import->id_chain;
                $chainClone->id_task = $task->id;
                $chainClone->save();
                foreach ($modelSteps as $step){
                    $stepClone = new ChainClonesSteps();
                    $stepClone->id_step = $step->id_step;
                    $stepClone->id_clone = $chainClone->id;
                    $stepClone->status = 0;
                    if($tmp_step->id == $step->id_step){
                        $stepClone->status = ChainClonesSteps::STATUS_WORK;
                    }
                    $stepClone->id_user = $step->id_user;
                    $stepClone->save(false);
                    $stepsAttr = Steps::findOne($step->id_step)->getStepAttributes()->all();
                    if(!empty($stepsAttr)){
                        foreach ($stepsAttr as $step_attr){
                            $attr_clone = new AttributesValues();
                            $attr_clone->id_attribute = $step_attr->id;
                            $attr_clone->id_step_clone = $stepClone->id;
                            if(!empty($step_attr['def_value']) && !is_null($step_attr['def_value'])){
                                $attr_clone->value = $step_attr['def_value'];
                            }
                            $attr_clone->save();
                        }
                    }
                }

                if($i % 10 == 0){
                    $deadline = date('Y-m-d H:i:s', strtotime($deadline.'+5 days'));
                }
                $task->deadline = $deadline;
                $task->status = 1;
                $task->save(false);
                $i++;
            }
           return $this->redirect(['task/list', 'id_project' => $id]);
        }
        return $this->renderAjax('import', compact(
            'project',
            'import'
        ));
    }

    /**
     * Загрузка задач из Excell файла;
     *
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
                $task->status = 0;
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


    /**
     *
     * Добавляем дополнительные поле в зависимости от выбора цепочки;
     *
     * @param $id
     * @return string
     */
    public function actionAddFields($id)
    {
        $form = new ActiveForm();
        $chain = Chain::findOne($id);
        $modelSteps = [];
        foreach ($chain->getSteps()->orderBy(['sort' => SORT_ASC])->all() as $step){
            $modelSteps[] = new SelectUserStep([
                'id_step' => $step->id,
                'label'   => $step->name,
                'id_group'   => $step->id_group,
            ]);

        }

        return $this->renderAjax('add-fields', [
            'form'  => $form,
            'chain' => $chain,
            'modelSteps'   => $modelSteps
        ]);
    }

    public function actionUsersList($id_group, $q = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $groups = Groups::findOne($id_group);
        if(!is_null($q)){
            $users = $groups->getUsers()->where(['like', 'surname', $q])
                ->andWhere(['<>', 'is_outer', '1'])
                ->orWhere(['like', 'name', $q])
                ->asArray()->all();
        } else{
            $users = $groups->getUsers()->asArray()->joinWith('groups')->all();

        }
        $add_users = User::find()->where(['is_outer' => 1])->one();
        foreach ($users as  $key => $user){
            if($user['id'] == $add_users->id){
                unset($users[$key]);
            }
        }
        $users[] = $add_users;
        $results = [];
        foreach ($users as $user){
            if(is_null($q)){
                $groups = ArrayHelper::getColumn($user['groups'], 'name');
                $groups = '(' . implode(',', $groups) . ')';
            } else{
                $groups = '';
            }

            if($user->is_outer == 1){
                $results[] = [
                    'id'    => $user['id'],
                    'text'  => '<strong style="color:red">Внешний сотрудник!</strong>'
                ];
            } else{
                $results[]  = [
                    'id'    => $user['id'],
                    'text'  => $user['surname']. ' ' . $user['name'] . ' (' . $user['email'] . ')' . $groups
                ];
            }

        }
       $out['results'] = $results;
        return $out;
    }
}