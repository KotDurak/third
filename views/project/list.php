<?php

use app\models\Task;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

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
            'class' => 'yii\grid\SerialColumn'
        ],
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
            'label'     => 'Задачи в архиве',
            'format'    => 'html',
            'value'     => function($model){
                $GLOBALS['tasks'] =  Task::getTaskStatusesByProject($model->id);
                $count  =  $GLOBALS['tasks'][Task::STATUS_ARCHIVE];
                $url = Url::to(['task/list-status', 'status' => Task::STATUS_ARCHIVE, 'id_project' => $model->id]);
                $a = Html::a($count, $url, ['style' => 'color:black']);
                return $count > 0 ? $a : $count;
            }
        ],
        [
            'attribute' => 'tasks.id',
            'header'     => '<span style="color:red">Задачи на доработке</span>',
            'format'     => 'html',
            'value'     => function($model){
                $count  =  $GLOBALS['tasks'][Task::STATUS_REWORK];
                $url = Url::to(['task/list-status', 'status' => Task::STATUS_REWORK, 'id_project' => $model->id]);
                $a = Html::a($count, $url, ['style' => 'color:red']);
                return $count > 0 ? $a : $count;
            }
        ],
        [
            'attribute' => 'tasks.id',
            'header'     => '<span style="color: #f0ad4e;">Задачи  в работе</span>',
            'format'    => 'html',
            'value'     => function($model){
                $count  =  $GLOBALS['tasks'][Task::STATUS_WORK];
                $url = Url::to(['task/list-status', 'status' => Task::STATUS_WORK, 'id_project' => $model->id]);
                $a = Html::a($count, $url, ['style' => 'color:f0ad4e;']);
                return $count > 0 ? $a : $count;
            }
        ],
        [
            'attribute' => 'tasks.id',
            'header'     => '<span style="color: green">Принятые задачи</span>',
            'format'      => 'html',
            'value'     => function($model){
                $count  =  $GLOBALS['tasks'][Task::STATUS_DONE];
                $url = Url::to(['task/list-status', 'status' => Task::STATUS_DONE, 'id_project' => $model->id]);
                $a = Html::a($count, $url, ['style' => 'color:green;']);
                return $count > 0 ? $a : $count;
            }
        ],
        [
            'attribute' => 'tasks.id',
            'label'     => 'Общее количество задач',
            'format'    => 'html',
            'value'     => function($model){
               $count = $GLOBALS['tasks']['count'];
                $url = Url::to(['task/list-status', 'status' => 'all', 'id_project' => $model->id]);
                $a = Html::a($count, $url, ['style' => 'color:black']);
                return $count > 0 ? $a : $count;
            }

        ],
        [
            'attribute' => 'url',
            'label' => 'Сайт проекта',
            'content'   => function($data, $key, $index, $column){
                return Html::a($data['url'], Url::to($data['url']), ['target' => '_blank']);
            }
        ],
        [
            'label'     => 'Импорт',
            'format'    => 'html',
            'value'     => function($data){
                if(!Yii::$app->user->identity->is_root){
                    return 'нет доступа';
                }
                $html = '<a  class="proj-import" href="'.Url::toRoute(['project/import', 'id' => $data['id']]).'" title="Импорт "
                            aria-label="Импорт" data-pjax="0"><span class="glyphicon glyphicon-indent-left"></span>
                            </a>';
                return $html;
            }
        ],
        [
            'label'     => 'Действия',
            'format'     => 'html',
            'value'     => function($data){
                if(!Yii::$app->user->identity->is_root){
                    return 'нет доступа';
                }
                $html = '';
                $html .= '<a  class="proj-edit" href="'.Url::toRoute(['project/edit', 'id' => $data['id']]).'" title="Редактировать"
                            aria-label="Редактировать" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span>
                            </a>';
                $html.= '<a class="proj-delete" href="'.Url::toRoute(['project/delete', 'id' => $data['id']]).'" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a>';
                return $html;
            }
        ],
    ]
]) . '</div>';
Pjax::end();
?>
