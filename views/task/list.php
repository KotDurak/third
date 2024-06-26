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

/* @var $dataProvider \yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = 'Список задач';

$this->registerJsFile('@web/js/task.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]);

    $counts = [10, 20, 30, 50, 100, 200, 500];

    $menus = '';
    if(Yii::$app->user->identity->is_root){
        $menu_add = Html::a('Добавить <i class="glyphicon glyphicon-plus"></i>', Url::to(['task/add', 'id_project' => $_GET['id_project']]), [
            'class' => 'btn btn-success circle-conttrols'
        ]);
        $menus .= $menu_add;

        $menu_import = Html::a('Импорт задач <i class="glyphicon glyphicon-import"></i>', Url::to(['project/import', 'id' => $_GET['id_project']]), [
            'class' => 'btn btn-default circle-conttrols', 'id' => 'menu-import'
        ]);
        $menus .= $menu_import;
    }
$this->registerCssFile('@web/css/task.css');
?>
<div class="row">
    <div class="col-md-12 text-right">
        <?php echo  $menus; ?>
        <select data-project="<?= $_GET['id_project']; ?>" onchange="location = this.value" name="" id="count-rows">
            <?= \app\helpers\PaginationHelper::getPageCounts($dataProvider->getPagination()->getPageSize() ,$counts) ?>
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

    $bottom_menus = '';
    $menu_change = Html::a(' <i class="glyphicon glyphicon-pencil"></i> Изменить', Url::to(['task/change']), [
           'class'  => 'btn btn-default circle-conttrols', 'id' => 'task-change'
    ]);

    $menu_copy = Html::a('<i class="glyphicon glyphicon-duplicate"></i> Копировать', Url::to(['task/copy']), [
        'class'  => 'btn btn-default circle-conttrols', 'id' => 'task-copy'
    ]);

    $menu_archive = Html::a('В архив', Url::to(['task/archive-mass']), [
        'class'  => 'btn btn-default circle-conttrols', 'id' => 'task-mass-change'
    ]);
    $menu_accept = Html::a('Принять', Url::to(['task/accept-mass']), [
        'class'  => 'btn btn-default circle-conttrols', 'id' => 'task-mass-accept'
    ]);

    $menu_delete = Html::a('Удалить', Url::to(['task/delete-mass']), [
        'class'     => 'btn btn-danger circle-conttrols', 'id'   => 'task-mass-delete'
    ]);

    $bottom_menus .= $menu_change;
    $bottom_menus .= $menu_copy;
    $bottom_menus .= $menu_archive;
    $bottom_menus .= $menu_accept;
    $bottom_menus .= $menu_delete;

?>
<?php if(Yii::$app->user->identity->is_root): ?>
<div class="row">
    <?php echo $bottom_menus; ?>
</div>
<?php endif; ?>

<div class="modal inmodal import" id="import" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg"></div>
</div>

<div class="modal inmodal change" id="change" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg"></div>
</div>

