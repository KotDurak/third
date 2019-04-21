<?php
use app\models\Task;
use app\models\ChainClonesSteps;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Project;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;
use app\models\User;
use app\models\Groups;

$this->registerJsFile('@web/js/site/admin-summary.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]);

$tasks = Task::getTasksStatuses();
$projects = Project::find()->asArray()->all();
$projects = ArrayHelper::map($projects, 'id', 'name');
$projects[0] = 'Не выбрано';
ksort($projects);

$users = User::find()->select(['concat(surname, " " ,name) as username', 'id'])->orderBy(['username' => SORT_ASC, 'surname' => SORT_ASC , 'name' => SORT_ASC])->asArray()->all();
$users  = ArrayHelper::map($users, 'id', 'username');
$keys = array_keys($users);
array_unshift($keys, 0);
array_unshift($users, 'Не выбран');
$users = array_combine($keys, $users);
$groups = ArrayHelper::map(Groups::find()->asArray()->all(), 'id', 'name');
$keys = array_keys($groups);
array_unshift($keys, 0);
array_unshift($groups, 'Не выбрано');
$groups = array_combine($keys, $groups);
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
    <div class="col-md-12 worker-block">
        <div class="col-md-4">
            <h4>По сотрудникам</h4>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Должности</label>
                <?php echo Html::dropDownList('groups', '0', $groups, [
                    'id'    => 'select-group',
                    'class' => 'form-control',
                ]);?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group" id="users-contaier">
                <label for="">Сотрудник</label>
                <?php echo Html::dropDownList('users', 's', $users, [
                    'id'    => 'select-user',
                    'class' => 'form-control select-user'
                ]); ?>
            </div>
        </div>
        <div class="col-md-12" id="task-by-user"></div>
    </div>
    <div class="col-md-12 worker-block">
        <div class="time-filters clearfix">
            <h4>Дедлайн в промежутке</h4>
            <?php
            echo '<div class="col-md-6">' . DatePicker::widget([
                    'language'  => 'ru',
                    'dateFormat' => 'dd.MM.yyyy',
                    'clientOptions'    => [
                        'changeYear'    => true,
                        'changeMonth'    => true
                    ],
                    'options'  => [
                        'id'    => 'date-from',
                        'class' => 'form-control',
                        'placeholder'   => 'От'
                    ],
                ]) . '</div>';
            echo '<div class="col-md-6">' .DatePicker::widget([
                    'language'  => 'ru',
                    'dateFormat' => 'dd.MM.yyyy',
                    'clientOptions'    => [
                        'changeYear'    => true,
                        'changeMonth'    => true
                    ],
                    'options'  => [
                        'id'    => 'date-to',
                        'class' => 'form-control',
                        'placeholder'   => 'До'
                    ],
                ]) . '</div>';
            ?>
        </div>
        <div id="date-contaier">
        </div>
    </div>
</div>
