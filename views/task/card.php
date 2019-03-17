<?php

use app\models\Task;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => 'Список задач', 'url' => ['/task/list?id_project=' . $task->id_project]];
$this->params['breadcrumbs'][] = 'Карточка задачи';

$this->registerCssFile('/css/task.css');

$clone_chain = $task->getChainClones()->one();
$steps = $clone_chain->getSteps()->orderBy(['sort' => SORT_ASC])->all();

$i = 1;
?>

<div class="row top-task-info">
    <div class="col-md-3">
        <h5>Проект</h5>
        <span><?php echo $task->project['name']; ?></span>
    </div>
    <div class="col-md-3">
        <h5>Имя задачи</h5>
        <span><?php echo $task->name; ?></span>
    </div>
    <div class="col-md-3">
        <h5>Дата создания задачи</h5>
        <span><?php echo date('d.m.Y', strtotime($task->created)); ?></span>
    </div>
    <div class="col-md-3">
        <h5>Срок сдачи задачи</h5>
        <span><?php echo date('d.m.Y H:i', strtotime($task->deadline)); ?></span>
    </div>
</div>
<div class="row">
    <div class="col-md-9">
        <?php foreach ($steps as $step): ?>
            <div class="row">
                <div class="col-md-12">
                    <h3>Этап <?php echo $i++ . ' ' . $step->name; ?> </h3>
                </div>
                <div class="col-md-6">
                    <?php
                    $clone_step = $step->getStepClones()->where(['id_clone' => $clone_chain['id']])->one();
                    if ($step->type == 'table') {
                        $rows = Task::getRows($task['id']);
                    }
                    ?>
                    <?php if ($step->type == 'table'): ?>
                        <div class="table-container">
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
                                <?php foreach ($rows as $row): ?>
                                    <tr>
                                        <td><?php echo $row['phrase']; ?></td>
                                        <td><?php echo $row['base']; ?></td>
                                        <td><?php echo $row['frequence_e']; ?></td>
                                        <td><?php echo $row['frequence_f']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                       <?php
                            $attributes = $clone_step->getAttributesValues()->all();
                        ?>
                        <?php if(!empty($attributes)): ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Атрибут</th>
                                    <th>Значение</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($attributes as $value): ?>
                                    <tr>
                                        <td><?php echo $value->attribute0->name; ?></td>
                                        <td><?php echo $value->value; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <?php if ($step == end($steps)): ?>
                        Кнопки админа
                    <?php else: ?>
                        <div class="btn-group steps-btn">
                            <a href="<?php echo Url::toRoute(['/task/rework', 'id_clone' => $clone_step->id, 'id_task' => $_GET['id']]) ?>"
                               type="button" class="btn btn-danger  rework">На доработку</a>
                            <a type="button" class="btn btn-warning work">В работе</a>
                            <a href="<?php echo Url::toRoute(['/task/done', 'id_clone' => $clone_step->id, 'id_task' => $_GET['id']]) ?>"
                               type="button" class="btn btn-info done">Сделано</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="col-md-3">
        side
    </div>
</div>
