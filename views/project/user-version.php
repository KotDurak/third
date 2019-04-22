<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\models\ChainClonesSteps;

$this->registerCss('
    #project-table{
        overflow:auto;
    }
');

Pjax::begin(array('id' => 'notes', 'enablePushState' => false));
echo '<div class="container-fluid" id="project-table">' . GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'   => $projectSearch,
        'columns'   => [
            [
                'attribute' => 'name',
                'label'     => 'Название проекта',
                'content'   => function($data, $key, $index, $column){
                    if(!Yii::$app->user->identity->is_admin()){
                        return $data['name'];
                    }
                    return Html::a($data['name'], Url::toRoute(['task/list', 'id_project' => $data['id']]), [
                        'target'    => '_blank'
                    ]);
                }
            ],
            [
                'attribute' => 'tasks.id',
                'header'     => '<span style="color:red">Задачи на доработке</span>',
                'format'    => 'html',
                'value'     => function($model){
                    $GLOBALS['info'] = ChainClonesSteps::getStepsByProject($model->id);
                    $count  = $GLOBALS['info'][ChainClonesSteps::STATUS_REWORK];
                    $url = Url::to(['task/users-tasks', 'id_user' => Yii::$app->user->id, 'status' => ChainClonesSteps::STATUS_REWORK,
                        'id_project' => $GLOBALS['info']['id_project']]);
                    $a = Html::a($count, $url, ['style' => 'color:red']);
                    return ($count != 0) ? $a : $count;
                }
            ],
            [
                'attribute' => 'tasks.id',
                'header'     => '<span style="color: #f0ad4e;">Задачи в работе</span>',
                'format'    => 'html',
                'value'     => function($model){
                    $count  = $GLOBALS['info'][ChainClonesSteps::STATUS_WORK];
                    $url = Url::to(['task/users-tasks', 'id_user' => Yii::$app->user->id, 'status' => ChainClonesSteps::STATUS_WORK,
                        'id_project' => $GLOBALS['info']['id_project']]);
                    $a = Html::a($count, $url, ['style' => 'color:#f0ad4e;']);
                    return ($count != 0) ? $a : $count;
                }
            ],
            [
                'attribute' => 'tasks.id',
                'header'     => '<span style="color:green">Принятые задачи</span>',
                'format'    => 'html',
                'value'     => function($model){
                    $count  = $GLOBALS['info'][ChainClonesSteps::STATUS_DONE];
                    $url = Url::to(['task/users-tasks', 'id_user' => Yii::$app->user->id, 'status' => ChainClonesSteps::STATUS_DONE,
                        'id_project' => $GLOBALS['info']['id_project']]);
                    $a = Html::a($count, $url, ['style' => 'color:green;']);
                    return ($count != 0) ? $a : $count;
                }
            ],
            [
                'attribute' => 'tasks.id',
                'label'     => 'Общее количество задач',
                'format'    => 'html',
                'value'     => function($model){
                    $count = $GLOBALS['info']['count'];
                    $url = Url::to(['task/users-tasks', 'id_user' => Yii::$app->user->id, 'status' => 'all',
                        'id_project' => $model->id]);
                    $a = Html::a($count, $url, ['style' => 'color:#000']);
                    return ($count != 0) ? $a : $count;
                }
            ],
            [
                'attribute' => 'url',
                'label' => 'Сайт проекта',
                'content'   => function($data, $key, $index, $column){
                    return Html::a($data['url'], Url::to($data['url']), ['target' => '_blank']);
                }
            ],
        ]
    ]) . '</div>';
Pjax::end();
?>