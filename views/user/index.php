<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\User;

$this->registerCssFile('/css/user.css');

$this->registerJsFile('@web/js/user.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->params['breadcrumbs'][] = 'Сотрудники';
$add_url = Url::to('/user/add');
?>
<div class="row">
    <div  class="btn-group right-top-menu">
        <?php echo Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить сотрудника', $add_url, [
            'class'  => 'btn btn-success circle-conttrols' ,
            'type'  => 'button',
            'id'    => 'add-user'
        ]); ?>
    </div>
</div>

<?php
    Pjax::begin(array('id' => 'users-list', 'enablePushState' => false));
echo GridView::widget([
    'options'      => [
        'class' => 'users-list'
    ],
    'dataProvider' => $dataProvider,
    'filterModel'   => $userSearch,
    'columns'   => [
        [
            'class' => 'yii\grid\SerialColumn'
        ],
        [
            'attribute' => 'surname'
        ],
        [
            'attribute' => 'name'
        ],
        [
            'attribute' => 'email'
        ],
        [
            'attribute' => 'id',
            'label'     => 'Права',
            'content'   => function($data){
                if($data['is_root']){
                    return 'Администратор';
                }
                if($data['is_moderator']){
                    return 'Модератор';
                }
                return 'Сотрудник';
            }

        ],
        [
           'attribute'  => 'id',
           'label'      => 'Должности',
           'filterInputOptions' => ['disabled' => true],
           'content'      => function($data){
               $groups = User::findOne($data['id'])->getGroups()->asArray()->all();
               $html = '<ul class="position-list">';
               foreach ($groups as $group){
                   $html .= '<li>'.$group['name'].'</li>';
               }
               $html .= '</ul>';
               return $html;
           }
        ],
        [
            'attribute' => 'last_visit',
            'label'     => 'Просамтривал задания',
            'content'    => function($date){
                if(!empty($date['last_visit'])){
                    return date('d.m.Y H:i', strtotime($date['last_visit']));
                }
                return null;
            }
        ],
        [
            'attribute' => 'id',
            'label'     => 'Текущие задачи',
            'filterInputOptions' => ['disabled' => true],
            'content'    => function($data){
                $tasks = $data->showTasks();
                return count($tasks);
            }
        ],
        [
            'attribute' => 'status',
            'label'     => 'Активация',
            'content'   => function($data){
                return $data['status'] == 1 ?
                    '<span style="color:green">Активирован</span>' : '<span style="color:blue">Не активирован</span>';
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header'    => 'Блокировка',
            'template'  => '{ban}',
            'buttons'   => [
                'ban'   => function($url, $model, $key){
                    $type = $model->is_ban ? 'deblock' : 'block';
                    $url .= '&type=' . $type;
                    $text = $model->is_ban ? 'Разблокировать' : 'Блокировать';
                    return Html::a($text, $url, [
                            'class' => 'btn btn-danger circle-conttrols ajax-ban',
                            'pjax-container' => 'users-list',
                    ]);
                }
            ]
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header'    => 'Действия',
            'template'  => Yii::$app->user->identity->is_root ? '{update} {delete}' : '{update}'
        ]
    ]
]);

Pjax::end();
?>

<div class="modal inmodal add" id="add" role="dialog" data-keyboard="false" style="overflow:hidden;">
    <div class="modal-dialog modal-lg"></div>
</div>
