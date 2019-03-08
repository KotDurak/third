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
use yii\widgets\ActiveForm;
use app\models\StepAttributes;

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
        $groups = $groups = ArrayHelper::map(Groups::find()->asArray()->all(), 'id', 'name');

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
                        $transaction->commit();
                        return Json::encode(['message' => 'save attributes']);
                    }
                } catch (Exception $exception){

                }

            }
        }
        return $this->renderAjax('add-attr', [
           'modelStep' => $modelStep,
            'modelAttributes'   =>  $modelAttributes
        ]);
    }

}
