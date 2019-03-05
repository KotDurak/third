<?php

namespace app\controllers;

use app\models\Chain;
use app\models\Steps;
use yii\data\Pagination;
use app\models\Groups;
use app\models\ModelMultiple;
use yii\helpers\ArrayHelper;

class ChainController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query = Chain::find();
        $pages = new Pagination([
            'totalCount' => $query->count(),
            'pageSize'   => 10
        ]);
        $chains = $query->offset($pages->offset)->limit($pages->limit)->all();


        return $this->render('index', compact('chains', 'pages'));
    }

   public function actionAdd()
   {
       $chain = new Chain();
       $steps = [new Steps()];
       $groups =  $groups = ArrayHelper::map(Groups::find()->asArray()->all(), 'id', 'name');
       return $this->renderAjax('add', [
           'modelChain' => $chain,
           'modelSteps'  => (empty($steps)) ? [new Steps()] : $steps,
           'groups'     => $groups
       ]);
   }

}
