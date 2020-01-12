<?php

use app\models\ChainClonesSteps;
use app\models\Task;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $tasks array */

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
            $count = $tasks[ChainClonesSteps::STATUS_REWORK];
            $url = Url::to(['task/users-tasks', 'id_user' => Yii::$app->user->id, 'status' => ChainClonesSteps::STATUS_REWORK]);
            $a = Html::a($count, $url, ['style' => 'color:red']);
            echo $a;
            ?>
        </td>
        <td>
            <?php
            $count  = $tasks[ChainClonesSteps::STATUS_WORK];
            $url = Url::to(['task/users-tasks', 'id_user' => Yii::$app->user->id, 'status' => ChainClonesSteps::STATUS_WORK]);
            $a = Html::a($count, $url, ['style' => 'color:#f0ad4e;']);
            echo  $a;
            ?>
        </td>
        <td>
            <?php
            $count  = $tasks[ChainClonesSteps::STATUS_DONE];
            $url = Url::to(['task/users-tasks', 'id_user' => Yii::$app->user->id, 'status' => ChainClonesSteps::STATUS_DONE]);
            $a = Html::a($count, $url, ['style' => 'color:green']);
            echo  $a;
            ?>
        </td>
        <td>
            <?php
            $url = Url::to(['task/users-tasks', 'id_user' => Yii::$app->user->id, 'status' => 'all']);
            $a = Html::a($tasks['count'], $url, ['style' => 'color:#000']);
            echo  $a;
            ?>
        </td>
    </tr>
    </tbody>
</table>
