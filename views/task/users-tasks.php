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



?>
<div class="row">
    <div class="col-md-12 text-right">
        <select onchange="location = this.value" name="" id="count-rows">
            <?= \app\helpers\PaginationHelper::getPageCounts($dataProvider->getPagination()->getPageSize()) ?>
        </select>
    </div>
</div>
<?php

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'   => $taskSearch,
        'columns'   => [
            [
                'class' => 'yii\grid\SerialColumn'
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
                        return '<span style="color: #cccccc">' . $data['name'] . '</span>';

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
                'attribute' => 'id',
                'label'     => 'Стадния задаиня',
                'content'    => function($data){
                    $clone = Task::findOne($data['id'])->getChainClones()->one();
                    $step = $data->getLastDoneStep();
                    if($step !== false){
                        return $step;
                    }
                    return 'Не установлен';
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
        ]
    ]);

?>
