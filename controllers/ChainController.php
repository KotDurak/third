<?php

namespace app\controllers;

use app\models\Chain;
use app\models\Steps;
use Matrix\Exception;
use yii\helpers\Json;
use Yii;
use yii\base\Model;
use yii\data\Pagination;
use app\models\Groups;
use app\models\ModelMultiple;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;
use app\models\StepAttributes;
use app\models\Files;
use app\models\StepFiles;
use app\models\FileLoad;

class ChainController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query = Chain::find();
        $pages = new Pagination([
            'totalCount' => $query->count(),
            'pageSize' => 10
        ]);
        $chains = $query->offset($pages->offset)->limit($pages->limit)->all();


        return $this->render('index', compact('chains', 'pages'));
    }

    public function actionAdd()
    {
        $chain = new Chain();
        $steps = [new Steps()];
        $groups  = ArrayHelper::map(Groups::find()->asArray()->all(), 'id', 'name');

        if (Yii::$app->request->isAjax && $chain->load(Yii::$app->request->post())) {
            $steps = ModelMultiple::createMultiple(Steps::className());
            ModelMultiple::loadMultiple($steps, Yii::$app->request->post());

            $valid = $chain->validate();
            $valid = ModelMultiple::validateMultiple($steps) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $chain->save(false)) {
                        foreach ($steps as $step) {
                            $step->id_chain = $chain->id;
                            if (!$flag = $step->save(false)) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return Json::encode(['message' => 'success']);
                    }
                } catch (Exception $e) {

                }
            }
        }

        return $this->renderAjax('add', [
            'modelChain' => $chain,
            'modelSteps' => (empty($steps)) ? [new Steps()] : $steps,
            'groups' => $groups
        ]);
    }

    public function actionEditStep($id)
    {
        $modelStep = Steps::findOne($id);
        $modelAttributes = $modelStep->getStepAttributes()->all();


        if(empty($modelAttributes)){
            $modelAttributes = [new StepAttributes];

        }
        $groups  = ArrayHelper::map(Groups::find()->asArray()->all(), 'id', 'name');

        if(Yii::$app->request->isAjax && $modelStep->load(Yii::$app->request->post())){
            $oldIDs = ArrayHelper::map($modelAttributes, 'id', 'id');
            $modelAttributes = ModelMultiple::createMultiple(StepAttributes::className(),$modelAttributes);
            ModelMultiple::loadMultiple($modelAttributes ,Yii::$app->request->post());
            $deleteIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelAttributes, 'id', 'id')));

            $valid = $modelStep->validate();
            $valid = ModelMultiple::validateMultiple($modelAttributes) && $valid;
            $transaction = Yii::$app->db->beginTransaction();
            $modelStep->save(false);
            try{
                if(!empty($deleteIDs)){
                    StepAttributes::deleteAll(['id' => $deleteIDs]);
                }
                foreach ($modelAttributes as $modelAttribute){
                    $modelAttribute->id_step = $modelStep->id;
                    if(!$modelAttribute->save(false)){
                        $transaction->rollBack();
                        break;
                    }
                }
                $transaction->commit();
                return Json::encode(['message' => 'save attributes']);
            } catch (Exception $e){
                return Json::encode(['message' => $e->getMessage()]);
            }
        }

        return $this->renderAjax('edit-step', [
            'modelStep' => $modelStep,
            'modelAttributes'   => $modelAttributes,
            'groups'    => $groups,
        ]);

    }

    public function actionAddAttr($id)
    {
        $modelStep= Steps::findOne($id);
        $modelAttributes = [new StepAttributes];
        if(Yii::$app->request->isAjax && !empty(Yii::$app->request->post())){
            $modelAttributes = ModelMultiple::createMultiple(StepAttributes::className());
            ModelMultiple::loadMultiple($modelAttributes, Yii::$app->request->post());
            $valid = ModelMultiple::validateMultiple($modelAttributes);
            if($valid){
                $transaction = Yii::$app->db->beginTransaction();
                try{
                    foreach ($modelAttributes as $modelAttribute){
                        $modelAttribute->id_step = $modelStep->id;
                        if(!$modelAttribute->save(false)){
                            $transaction->rollBack();
                            break;
                        }
                    }
                    $transaction->commit();
                    return Json::encode(['message' => 'save attributes']);
                } catch (Exception $exception){

                }

            }
        }
        return $this->renderAjax('add-attr', [
           'modelStep' => $modelStep,
            'modelAttributes'   =>  $modelAttributes
        ]);
    }

    public function actionAddStep($id_chain)
    {
        $modelChain = Chain::findOne($id_chain);
        $modelStep = new Steps();

        $modelAttributes = $modelStep->getStepAttributes()->all();
        if(empty($modelAttributes)){
            $modelAttributes = [new StepAttributes];
        }
        $groups  = ArrayHelper::map(Groups::find()->asArray()->all(), 'id', 'name');

        if(Yii::$app->request->isAjax && $modelStep->load(Yii::$app->request->post())){
            $modelStep->id_chain = $modelChain->id;
            $modelStep->save(false);
            $modelAttributes = ModelMultiple::createMultiple(StepAttributes::className());
            ModelMultiple::loadMultiple($modelAttributes ,Yii::$app->request->post());
            $valid = $modelStep->validate();
            $valid = ModelMultiple::validateMultiple($modelAttributes) && $valid;
            $transaction = Yii::$app->db->beginTransaction();
            if($valid){
                try{
                    foreach ($modelAttributes as $modelAttribute){
                        $modelAttribute->id_step = $modelStep->id;
                        if(!$modelAttribute->save(false)){
                            $transaction->rollBack();
                            break;
                        }
                    }
                    $transaction->commit();
                    return Json::encode(['message' => 'save attributes']);
                } catch (Exception $exception){

                }
            }
        }

        return $this->renderAjax('edit-step', [
            'modelStep' => $modelStep,
            'modelAttributes'   => $modelAttributes,
            'groups'    => $groups
        ]);
    }

    /***
     * Удаляем цепочку по ajax
     *
     * @param $id
     * @return string
     */
    public function actionDelete($id)
    {
        $chain = Chain::findOne($id);
        $chain->delete();
        return Json::encode(['message' => 'chain delete']);
    }

    /**
     * Удаляет по ajax
     *
     * @param $id
     * @return string
     */
    public function actionDeleteStep($id)
    {
        $step = Steps::findOne($id);
        $step->delete();
        return Json::encode(['message' => 'succes']);
    }

    public function actionUpload($id)
    {
        $model = Steps::findOne($id);
        $file = new FileLoad();
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            $file->file = UploadedFile::getInstances($file, 'file');
            foreach ($file->file as $item){
                $modelFile = new Files();
                $modelFile['real-name'] = $item->baseName;
                $modelFile->name = uniqid();
                $modelFile->tmp =   $modelFile->name . '.' . $item->extension;
                $item->saveAs(Yii::getAlias('@web') . 'uploads/files/' . $modelFile->tmp);
                $modelFile->save();
                $step_file = new StepFiles();
                $step_file->id_step = $id;
                $step_file->id_file = $modelFile->id;
                $step_file->save();
            }
            return true;

        }
        return $this->renderAjax('upload', [
           'model'  => $model,
            'file'  => $file
        ]);
    }

    public function actionCheckUniq()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $index = Yii::$app->request->post('index');
        $attrs = StepAttributes::find()->where(['index' => $index])->asArray()->all();
        $response = [
            'acc' => empty($attrs)
        ];
        return $response;
    }

}
