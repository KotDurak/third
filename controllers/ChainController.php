<?php

namespace app\controllers;

use app\models\Chain;
use yii\data\Pagination;

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

       /// $chains = Chain::find()->asArray()->all();
        return $this->render('index', compact('chains', 'pages'));
    }

}
