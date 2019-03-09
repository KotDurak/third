<?php
    use yii\grid\GridView;
    use yii\widgets\Pjax;
    use yii\helpers\Url;
    use yii\widgets\Menu;
    use app\models\Task;
    use app\models\Project;
    use yii\helpers\ArrayHelper;
    use app\models\Chain;


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
                    return $data['name'];
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
                    $steps = $clone->getCloneSteps()->all();
                    return $clone->id_task;
                }
            ],
        ]
    ]);


Pjax::end();
?>