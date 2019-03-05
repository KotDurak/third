<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 05.03.2019
 * Time: 21:12
 */

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

class ModelMultiple extends \yii\base\Model
{
    /**
     * @param $modelClass
     * @param array $multipleModels
     * @return array
     */
    public static function createMultiple($modelClass, $multipleModels = [])
    {
        $model = new $modelClass;
        $formName = $model->formName();
        $post     = Yii::$app->request->post($formName);
        $models   = [];

        if (! empty($multipleModels)) {
            $keys = array_keys(ArrayHelper::map($multipleModels, 'id', 'id'));
            $multipleModels = array_combine($keys, $multipleModels);
        }

        if($post && is_array($post)){
            foreach ($post as $i => $item) {
                if (isset($item['id']) && !empty($item['id']) && isset($multipleModels[$item['id']])) {
                    $models[] = $multipleModels[$item['id']];
                } else {
                    $models[] = new $modelClass;
                }
            }
        }
        unset($model, $formName, $post);

        return $models;
    }
}