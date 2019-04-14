<?php
use app\models\Task;
use app\models\ChainClonesSteps;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Project;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;


$this->registerJsFile('@web/js/site/admin-summary.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]);

$tasks = Task::getTasksStatuses();
$projects = Project::find()->asArray()->all();
$projects = ArrayHelper::map($projects, 'id', 'name');
$projects[0] = 'Не выбрано';
ksort($projects);


?>

<div class="row">
    <div class="col-md-12 worker-block">
        <h4>По задачам</h4><div class="clearfix"></div>
        <table class="table">
            <thead>
            <tr>
                <th>В архиве</th>
                <th>На доработке</th>
                <th>В работе</th>
                <th>Принятые</th>
                <th>Всего</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <?php
                    $count  = $tasks[Task::STATUS_ARCHIVE];
                    $url = Url::to(['task/list-status', 'status' => Task::STATUS_ARCHIVE]);
                    $a = Html::a($count, $url, ['style' => 'color:black']);
                    echo $a;
                    ?>
                </td>
                <td>
                    <?php
                    $count  = $tasks[Task::STATUS_REWORK];
                    $url = Url::to(['task/list-status', 'status' => Task::STATUS_REWORK]);
                    $a = Html::a($count, $url, ['style' => 'color:red']);
                    echo $a;
                    ?>
                </td>
                <td>
                    <?php
                    $count  = $tasks[Task::STATUS_WORK];
                    $url = Url::to(['task/list-status', 'status' => Task::STATUS_WORK]);
                    $a = Html::a($count, $url, ['style' => 'color:#f0ad4e;']);
                    echo $a;
                    ?>
                </td>
                <td>
                    <?php
                    $count  = $tasks[Task::STATUS_DONE];
                    $url = Url::to(['task/list-status', 'status' => Task::STATUS_DONE]);
                    $a = Html::a($count, $url, ['style' => 'color:green']);
                    echo $a;
                    ?>
                </td>
                <td>
                    <?php
                    $count  = $tasks['count'];
                    $url = Url::to(['task/list-status', 'status' => 'all']);
                    $a = Html::a($count, $url, ['style' => 'color:black']);
                    echo $a;
                    ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-12 worker-block">
        <div class="col-md-4">
            <h4>По проектам</h4>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php echo Html::dropDownList('project', null, $projects, [
                    'id'    => 'select-project',
                    'class' => 'form-control'
                ]); ?>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <strong>Всего проектов</strong><br>
            <span><?php echo (count($projects) - 1); ?></span>
        </div>
        <div class="col-md-12" id="task-by-project">
        </div>
    </div>
</div>
