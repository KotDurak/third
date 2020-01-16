<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\Menu;
use app\models\Task;
use app\models\Project;
use yii\helpers\ArrayHelper;
use app\models\Chain;
use yii\jui\DatePicker;
use yii\helpers\Html;

/* @var $statuses array */
/* @var $this \yii\web\View */

$this->registerCssFile('@web/css/task.css');
?>

<?php if(!Yii::$app->user->identity->is_admin()): ?>
    <?= $this->render('/site/workers'); ?>
<?php endif; ?>
<div class="row">
    <div class="col-md-12 text-right">
        <select onchange="location = this.value" name="" id="count-rows">
            <?= \app\helpers\PaginationHelper::getPageCounts($dataProvider->getPagination()->getPageSize()) ?>
        </select>
    </div>
</div>
<?php
echo GridView::widget([
    'options'      => [
        'class' => 'task-list'
    ],
    'dataProvider' => $dataProvider,
    'filterModel'   => $taskSearch,
    'columns'   => [
        [
            'class' => 'yii\grid\SerialColumn'
        ],
        [
            'class' => 'yii\grid\CheckboxColumn',
        ],
        [
            'attribute'  => 'chain',
            'label'     => 'Цепочка',
            'value'     => 'chain.name'
        ],
        [
            'attribute' => 'status',
            'label'     => 'Состояние',
            'filter'    => [
                Task::STATUS_NOT => 'Не настроен',
                Task::STATUS_WORK => 'В работе',
                Task::STATUS_REWORK => 'На доработке',
                Task::STATUS_DONE => 'Принятные',
                Task::STATUS_ARCHIVE => 'В архиве'
            ],
            'content'   => function($data){
                return Task::getStrStatus($data['status']);
            }
        ],
        [
            'attribute' => 'id',
            'filter'   => false,
            'label'     => 'Статус этапа сотрудника',
            'content'    => function($data) use ($statuses){
                return $statuses[$data['id']];
            }
        ],
        [
            'attribute' => 'name',
            'label'     => 'Название',
            'format'    => 'html',
            'content'   => function($data){
                if($data->status == Task::STATUS_ARCHIVE){
                    return Html::a($data['name'], Url::toRoute(['task/card', 'id' => $data['id'],
                    ]), [ 'style' => 'color:#cccccc']);

                }
                return Html::a($data['name'], Url::toRoute(['task/card', 'id' => $data['id']]));
            }
        ],
        [
            'attribute' => 'id_project',
            'label'     => 'Проект',
            'content'   => function($data){
                return Project::findOne($data['id_project'])->name;
            }
        ],
        [
            'attribute' => 'stage',
            'filter' => \app\helpers\TasksHelper::getTaskStages(),
        ],
        [
            'attribute' => 'created',
            'label'     => 'Дата создания',
            'content'   => function($data){
                return date('d.m.Y H:i', strtotime($data['created']));
            },
            'filter'    => DatePicker::widget([
                'model'=>$taskSearch,
                'attribute'=>'created',
                'language' => 'ru',
                'dateFormat' => 'yyyy-MM-dd',
            ]),
            'format'    => 'html'
        ],
        [
            'attribute' => 'deadline',
            'label'     => 'Дедлайн',
            'content'   => function($data){
                return date('d.m.Y', strtotime($data['deadline']));
            },
            'filter'    => DatePicker::widget([
                'model' => $taskSearch,
                'attribute' => 'deadline',
                'language'  => 'ru',
                'dateFormat'    => 'yyyy-MM-dd'
            ]),
            'format'    => 'html'
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header'    => 'Действия',
            'template'  => Yii::$app->user->identity->is_admin() ? (Yii::$app->user->identity->is_root ? '{update} {delete}' : '{update}') : null,
            'buttons'   => [
                'delete'    => function($url, $model, $key){
                    $url = Url::to(['task/delete', 'id' => $model->id]);
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                        false,
                        [
                            'class' => 'ajaxDelete',
                            'delete-url'    => $url,
                            'pjax-container' => 'task-list',
                            'title'          => Yii::t('app', 'Delete')
                        ]
                    );
                }
            ]
        ]
    ]
]);

?>
