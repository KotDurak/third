<?php
use app\models\ChainClonesSteps;
use yii\helpers\Url;
use yii\helpers\Html;

if(empty($clone_steps['from'])){
    $clone_steps['from'] = date('Y-m-d H:i:s',0);
}
if(empty($clone_steps['to'])){
    $clone_steps['to'] = date('Y-m-d H:i:s', PHP_INT_MAX);
}

?>

<table class="table">
    <thead>
    <tr>
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
            $count  = $clone_steps[ChainClonesSteps::STATUS_REWORK];
            $url = Url::to(['task/users-tasks-date', 'id_user' => Yii::$app->user->id, 'status' => ChainClonesSteps::STATUS_REWORK,
                'from' => $clone_steps['from'], 'to' => $clone_steps['to']]);
            $a = Html::a($count, $url, ['style' => 'color:red']);
            echo $a;
            ?>
        </td>
        <td>
            <?php
            $count  = $clone_steps[ChainClonesSteps::STATUS_WORK];
            $url = Url::to(['task/users-tasks-date', 'id_user' => Yii::$app->user->id, 'status' => ChainClonesSteps::STATUS_WORK,
                'from' => $clone_steps['from'], 'to' => $clone_steps['to']]);
            $a = Html::a($count, $url, ['style' => 'color:#f0ad4e;']);
            echo  $a;
            ?>
        </td>
        <td>
            <?php
            $count  = $clone_steps[ChainClonesSteps::STATUS_DONE];
            $url = Url::to(['task/users-tasks-date', 'id_user' => Yii::$app->user->id, 'status' => ChainClonesSteps::STATUS_DONE,
                'from' => $clone_steps['from'], 'to' => $clone_steps['to']]);
            $a = Html::a($count, $url, ['style' => 'color:green']);
            echo  $a;
            ?>
        </td>
        <td>
            <?php
            $url = Url::to(['task/users-tasks-date', 'id_user' => Yii::$app->user->id, 'status' => 'all',
                'from' => $clone_steps['from'], 'to' => $clone_steps['to']]);
            $a = Html::a($clone_steps['count'], $url, ['style' => 'color:#000']);
            echo  $a;
            ?>
        </td>
    </tr>
    </tbody>
</table>