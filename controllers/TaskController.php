<?php

namespace app\controllers;

use app\models\Chain;
use app\models\ChainClonesSteps;
use app\models\FileLoad;
use app\models\Files;
use app\models\FilesTaskSteps;
use app\models\Groups;
use app\models\StepFiles;
use app\models\Steps;
use app\models\TaskEdit;
use app\models\User;
use Codeception\Step\Comment;
use Yii;
use yii\data\ActiveDataProvider;
use app\models\Task;
use app\models\TaskSearch;
use yii\db\Query;
use app\models\SelectUserStep;
use yii\widgets\ActiveForm;
use app\models\ChainClones;
use app\models\StepClonesComment;
use yii\web\UploadedFile;
use app\models\ExternalSource;
use yii\helpers\Json;


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
            'pageSize' => $page_size
        ]);
        $dataProvider->query->andFilterWhere(['id_project' => $id_project]);
        // $dataProvider->query->where('id_project=' . $id_project);
        /* $dataProvider = new ActiveDataProvider([
             'query' => Task::find()->where(['id_project' => $id_project]),
             'pagination'    => [
                 'pageSize'  => $page_size
             ]
         ]);*/
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'taskSearch' => $taskSearch
        ]);
    }

    public function actionUpdate($id)
    {
        $task = Task::findOne($id);

        $modelEdit = new TaskEdit();
        if (!empty(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $task->load(Yii::$app->request->post());
            $task->created = date('Y-m-d H:i:s', strtotime($task->created));
            $task->deadline = date('Y-m-d H:i:s', strtotime($post['Task']['deadline']));
            ChainClones::deleteByTaskId($id);
            $modelEdit->load($post);
            $clone = new ChainClones();
            $clone->id_task = $id;
            $clone->id_chain = $modelEdit->id_chain;
            $clone->save();
            foreach ($post['SelectUserStep'] as $i => $step) {
                $post['SelectUserStep'][$i]['status'] = $post['ChainClonesSteps'][$i]['status'];
            }
            $is_rework = false;
            foreach ($post['SelectUserStep'] as $item) {
                $clone_step = new ChainClonesSteps();
                $clone_step->id_clone = $clone->id;
                $clone_step->id_step = $item['id_step'];
                $clone_step->id_user = $item['id_user'];
                $clone_step->status = $item['status'];
                if ($item['status'] == 2) {
                    $is_rework = true;
                }
                $clone_step->save();
            }
            if ($is_rework) {
                $task->status = 2;
            }
            $task->save();
            $this->redirect('/task/list?id_project=' . $task->id_project);
        }
        return $this->render('update', [
            'task' => $task,
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
        if (!is_null($q)) {
            $query->select('id, name as text')
                ->from('chain')
                ->where(['like', 'name', $q])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } else if ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Chain::find($id)->name];
        } else {
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
        foreach ($chain->getSteps()->orderBy(['sort' => SORT_ASC])->all() as $step) {
            $modelSteps[] = new SelectUserStep([
                'id_step' => $step->id,
                'label' => $step->name,
                'id_group' => $step->id_group
            ]);
        }
        return $this->renderAjax('add-fields', [
            'form' => $form,
            'chain' => $chain,
            'modelSteps' => $modelSteps
        ]);

    }

    /**
     * Вывосдим список для редакрования статуса задачи;
     *
     * @param $id_chain
     */
    public function actionStepList($id_chain)
    {
        $form = new ActiveForm();
        $chain = Chain::findOne($id_chain);
        $modelsClonesSteps = [];
        foreach ($chain->getSteps()->orderBy(['sort' => SORT_ASC])->all() as $step) {
            $modelsClonesSteps[] = new ChainClonesSteps([
                'id_step' => $step->id,
                'status' => 0,
            ]);
        }

        return $this->renderAjax('step-list', [
            'modelsClonesSteps' => $modelsClonesSteps,
            'form' => $form
        ]);
    }

    public function actionUsersList($id_group, $q = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $groups = Groups::findOne($id_group);
        if (!is_null($q)) {
            $users = $groups->getUsers()->where(['like', 'surname', $q])
                ->orWhere(['like', 'name', $q])
                ->asArray()->all();
        } else {
            $users = $groups->getUsers()->asArray()->all();
        }

        $add_users = User::find()->where(['is_outer' => 1])->one();
        $users[] = $add_users;
        $results = [];
        foreach ($users as $user) {
            if ($user->is_outer == 1) {
                $results[] = [
                    'id' => $user['id'],
                    'text' => '<strong style="color:red">Внешний сотрудник!</strong>'
                ];
            } else {
                $results[] = [
                    'id' => $user['id'],
                    'text' => $user['surname'] . ' ' . $user['name'] . ' (' . $user['email'] . ')'
                ];
            }

        }
        $out['results'] = $results;
        return $out;
    }

    public function actionCard($id)
    {
        $task = Task::findOne($id);

        return $this->render('card', [
            'task' => $task
        ]);
    }


    public function actionRework($id_clone, $id_task)
    {
        $step = ChainClonesSteps::findOne($id_clone);
        $task = Task::findOne($id_task);
        $task->setRework();
        $step->changeStatus(ChainClonesSteps::STATUS_REWORK);
        $this->redirect(['task/card', 'id' => $id_task]);
    }

    public function actionDone($id_clone, $id_task)
    {
        $step = ChainClonesSteps::findOne($id_clone);
        $step->changeStatus(ChainClonesSteps::STATUS_DONE);
        $this->redirect(['task/card', 'id' => $id_task]);
    }

    public function actionWorking($id_clone, $id_task)
    {
        $step = ChainClonesSteps::findOne($id_clone);
        $step->changeStatus(ChainClonesSteps::STATUS_WORK);
        $task = Task::findOne($id_task);
        $task->setWorkStatus();
        $this->redirect(['task/card', 'id' => $id_task]);
    }

    public function actionComment($id_clone)
    {
        $model = new StepClonesComment();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $model->save();
            $step = ChainClonesSteps::findOne($id_clone);
            $step->changeStatus(ChainClonesSteps::STATUS_REWORK);
            $task = $step->clone->task;
            $task->setRework();
            return true;
        }
        return $this->renderAjax('comment', [
            'model' => $model,
            'id_clone' => $id_clone
        ]);
    }

    public function actionUpload($id_task, $id_step)
    {
        $model = Steps::findOne($id_step);
        $file = new FileLoad();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $file->file = UploadedFile::getInstances($file, 'file');
            foreach ($file->file as $item) {
                $modelFile = new Files();
                $modelFile['real-name'] = $item->baseName;
                $modelFile->name = uniqid();
                $modelFile->tmp = $modelFile->name . '.' . $item->extension;
                $item->saveAs(Yii::getAlias('@web') . 'uploads/files/' . $modelFile->tmp);
                $modelFile->save();
                $files_steps = new FilesTaskSteps();
                $files_steps->id_step = $id_step;
                $files_steps->id_task = $id_task;
                $files_steps->id_file = $modelFile->id;
                $files_steps->save();
            }
            return true;
        }

        return $this->renderAjax('upload', [
            'file' => $file,
            'model' => $model
        ]);
    }

    public function actionArchive($id)
    {
        $model = Task::findOne($id);
        $model->archive();
        return $this->redirect(['task/card', 'id' => $id]);
    }

    public function actionComplete($id)
    {
        $model = Task::findOne($id);
        $zip_name = $model->complete();
        if ($zip_name) {
            return $this->redirect(['task/card', 'id' => $id]);
            //return Yii::$app->response->SendFile($zip_name);
        }
        return $this->redirect(['task/card', 'id' => $id]);
    }

    public function actionStepComments($id_clone)
    {
        $model = ChainClonesSteps::findOne($id_clone);
        $model_comment = new StepClonesComment();
        if (Yii::$app->request->isAjax && $model_comment->load(Yii::$app->request->post())) {
            $model_comment->save();
            return true;
        }
        return $this->renderAjax('step-comments', [
            'model' => $model,
            'model_comment' => $model_comment
        ]);
    }

    public function actionDeleteFile($id, $id_task)
    {
        $file = Files::findOne($id);
        $file->delete();
        return $this->redirect(['task/card', 'id' => $id_task]);
    }

    public function actionDeleteExternal($id, $id_task)
    {
        $model = ExternalSource::findOne($id);
        $model->delete();
        return $this->redirect(['task/card', 'id' => $id_task]);
    }

    public function actionAddExternal($id_task, $id_step)
    {
       $model = new ExternalSource();

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            $model->id_step = $id_step;
            $model->id_task = $id_task;
            $model->save();
            return Json::encode(['message' => 'Внешний источник добавлен', 'id' => $model->id]);
        }

        return $this->renderAjax('add-external', [
            'model' => $model,
            'id_task'   => $id_task,
            'id_step'   => $id_step
        ]);
    }

    public function actionDelete($id)
    {
        $task = Task::findOne($id);
        $task->delete();
        return Json::encode(['task delete']);
    }

}
