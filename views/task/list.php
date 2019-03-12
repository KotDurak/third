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

$this->params['breadcrumbs'][] = 'Список задач';

$this->registerJsFile('@web/js/task.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]);

    $counts = [10, 20, 30, 50, 100, 200, -1];
 /*   $items  = [];
    foreach ($counts as $count){
        $items[] = [
            'label' =>  ($count != -1) ? $count : 'все',
            'url'   => Url::toRoute(['task/list', 'id_project' => $_GET['id_project'], 'page_size' => $count])
        ];
    } */


?>
<div class="row">
    <div class="col-md-12 text-right">
        <select data-project="<?= $_GET['id_project']; ?>" name="" id="count-rows">
            <?php foreach ($counts as $count): ?>
                <option value="<?= $count ?>">
                    <?php echo $count != -1 ? $count : 'все' ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<?php
Pjax::begin(array('id' => 'task-list', 'enablePushState' => false));
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
                'attribute' => 'status',
                'label'     => 'Состояние',
                'filter'    => [
                    0 => 'Не настроен',
                    1 => 'В работе',
                    2 => 'На доработке',
                    3 => 'Принятные',
                    4 => 'В архиве'
                ],
                'content'   => function($data){
                    return Task::getStrStatus($data['status']);
                }
            ],
            [
                'attribute' => 'name',
                'label'     => 'Название',
                'content'   => function($data){
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
                'attribute' => 'id',
                'label'     => 'Стадния задаиня',
                'content'    => function($data){
                    $clone = Task::findOne($data['id'])->getChainClones()->one();
                    if(empty($clone)){
                        return 'Не установлен';
                    }
                      $step = $clone->getCloneSteps()->where(['status' => '1'])->one()->step;
                    if(!empty($step)){
                        return $step->name;
                    } else {
                        return 'Не установлен';
                    }

                }
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
                'template'  => '{update} {delete}'
            ]
        ]
    ]);


Pjax::end();
?>