<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
$this->registerCssFile('/css/user.css');

$this->registerJsFile('@web/js/group.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->params['breadcrumbs'][] = 'Должности';
$add_url =  Url::to('/group/add');
?>
<?php if(Yii::$app->user->identity->is_root): ?>
<div class="row">
    <div  class="btn-group right-top-menu">
        <?php echo Html::a('<i  class="clglyphicon glyphicon-plus"></i> Создать должность', $add_url, [
            'class'  => 'btn btn-success circle-conttrols' ,
            'type'  => 'button',
            'id'    => 'add-group'
        ]); ?>
    </div>
</div>
<?php endif; ?>
<?php
Pjax::begin(array('id' => 'groups-list', 'enablePushState' => false));
echo GridView::widget([
    'options'      => [
        'class' => 'users-list'
    ],
    'dataProvider' => $dataProvider,
    'filterModel'   => $userSearch,
    'columns'   => [
        [
            'attribute' => 'name'
        ],
        Yii::$app->user->identity->is_root ?
        [
            'class' => 'yii\grid\ActionColumn',
            'header'    => 'Действия',
            'template'  => '{update} {delete}',
            'buttons'   => [
                'update'    => function($url, $model, $key){
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                       'class'  => 'ajax-update',
                       'pjax-container' => 'users-list'
                    ]);
                }
            ]
        ] : [
                'attribute' => 'id',
                'content'   => function($data){
                        return 'Нет доступа';
                }
        ]
    ]
]);

Pjax::end();
?>

<div class="modal inmodal add" id="add" role="dialog" data-keyboard="false"">
    <div class="modal-dialog modal-lg"></div>
</div>
