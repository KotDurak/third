<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Task;

$this->params['breadcrumbs'][] = ['label' => 'Список задач', 'url' => ['/task/list?id_project='.$task->id_project]];
$this->params['breadcrumbs'][] = 'Карточка задачи';

$this->registerCssFile('/css/task.css');

$clone_chain = $task->getChainClones()->one();
$steps = $clone_chain->getSteps()->orderBy(['sort' => SORT_ASC])->all();


?>

<div class="row top-task-info">
    <div class="col-md-3">
        <h5>Проект</h5>
        <span><?php echo $task->project['name'];?></span>
    </div>
    <div class="col-md-3">
        <h5>Имя задачи</h5>
        <span><?php echo $task->name;?></span>
    </div>
    <div class="col-md-3">
        <h5>Дата создания задачи</h5>
        <span><?php echo date('d.m.Y', strtotime($task->created));?></span>
    </div>
    <div class="col-md-3">
        <h5>Срок сдачи задачи</h5>
        <span><?php echo date('d.m.Y H:i', strtotime($task->deadline));?></span>
    </div>
</div>
<div class="row">
    <div class="col-md-9">
        <?php foreach ($steps as $step): ?>
            <?php
              $cloone_step = $step->getStepClones()->where(['id_clone' => $clone_chain['id']])->one();
              if($step->type == 'table'){
                $rows = Task::getRows($task['id']);
              }
            ?>
            <?php if($step->type == 'table'): ?>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Фраза</th>
                        <th>Базовая</th>
                        <th>Частота ""</th>
                        <th>Частота "!"</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Cell</td>
                        <td>Cell</td>
                        <td>Cell</td>
                        <td>Cell</td>
                    </tr>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div class="col-md-3">
        side
    </div>
</div>
