<?php
use app\models\Task;
use yii\helpers\Html;
use yii\helpers\Url;

?>
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
            $url = Url::to(['task/list-status-user', 'status' => Task::STATUS_ARCHIVE, 'id_user' => $id_user]);
            $a = Html::a($count, $url, ['style' => 'color:black']);
            echo $a;
            ?>
        </td>
        <td>
            <?php
            $count  = $tasks[Task::STATUS_REWORK];
            $url = Url::to(['task/list-status-user', 'status' => Task::STATUS_REWORK, 'id_user' => $id_user]);
            $a = Html::a($count, $url, ['style' => 'color:red']);
            echo $a;
            ?>
        </td>
        <td>
            <?php
            $count  = $tasks[Task::STATUS_WORK];
            $url = Url::to(['task/list-status-user', 'status' => Task::STATUS_WORK, 'id_user' => $id_user]);
            $a = Html::a($count, $url, ['style' => 'color:#f0ad4e;']);
            echo $a;
            ?>
        </td>
        <td>
            <?php
            $count  = $tasks[Task::STATUS_DONE];
            $url = Url::to(['task/list-status-user', 'status' => Task::STATUS_DONE, 'id_user' => $id_user]);
            $a = Html::a($count, $url, ['style' => 'color:green']);
            echo $a;
            ?>
        </td>
        <td>
            <?php
            $count  = $tasks['count'];
            $url = Url::to(['task/list-status-user', 'status' => 'all', 'id_user' => $id_user]);
            $a = Html::a($count, $url, ['style' => 'color:black']);
            echo $a;
            ?>
        </td>
    </tr>
    </tbody>
</table>
