<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 18.03.2019
 * Time: 20:40
 */

namespace app\controllers;

use yii\helpers\Json;
use yii\web\Controller;
use Yii;
use app\models\AttributesValues;


class AttributesValuesController extends Controller
{
    public function actionChange($id)
    {
        $model = AttributesValues::findOne($id);
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            $model->save(false);
            return Json::encode(['message' => 'model saved', 'id' => $model->id]);
        }
        return $this->renderAjax('change', [
            'model' => $model
        ]);
    }
}