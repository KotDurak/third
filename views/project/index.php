<?php
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\Project;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\bootstrap\Modal;


$this->registerJsFile('@web/js/project_add.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]);

?>
<div class="col-md-12 text-right">
    <?= Html::a('<i class="glyphicon glyphicon-plus"></i>Создать проект', [ Yii::$app->urlManager->createUrl('project/add')], ['class'=>'btn btn-success', 'id' => 'create-project']) ?>
</div>
<div class="list-content" url="<?php echo  Yii::$app->urlManager->createUrl('project/list'); ?>">
<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'   => $projectSearch,
    'columns'   => [
        [
            'attribute' => 'name',
            'label'     => 'Название проекта'
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
            'value'     => function($data){
                return $data['id'];
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
        [
            'class' => 'yii\grid\ActionColumn',
            // вы можете настроить дополнительные свойства здесь.
        ]
    ]
]);

?>
</div>
<div class="modal inmodal add" id="add" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg"></div>
</div>

</div>
<div class="modal inmodal delte" id="delete" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg"></div>
</div>