<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

Pjax::begin(array('id' => 'notes', 'enablePushState' => false));
echo GridView::widget([
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
                return Html::a($data['name'], Url::toRoute(['task/list', 'id_project' => $data['id']]), [
                    'target'    => '_blank'
                ]);
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
                $html = '';
                $html .= '<a  class="proj-edit" href="'.Url::toRoute(['project/edit', 'id' => $data['id']]).'" title="Редактировать"
                            aria-label="Редактировать" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span>
                            </a>';
                $html.= '<a class="proj-delete" href="'.Url::toRoute(['project/delete', 'id' => $data['id']]).'" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a>';
                return $html;
            }
        ],
    ]
]);
Pjax::end();
?>
