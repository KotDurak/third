<?php
    use app\models\ChainClonesSteps;
    use yii\helpers\Url;
    use yii\helpers\Html;
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
            $url = Url::to(['task/users-tasks', 'id_user' => Yii::$app->user->id, 'status' => ChainClonesSteps::STATUS_REWORK,
                'id_project' => $clone_steps['id_project']]);
            $a = Html::a($count, $url, ['style' => 'color:red']);
            echo $a;
            ?>
        </td>
        <td>
            <?php
            $count  = $clone_steps[ChainClonesSteps::STATUS_WORK];
            $url = Url::to(['task/users-tasks', 'id_user' => Yii::$app->user->id, 'status' => ChainClonesSteps::STATUS_WORK,
                'id_project' => $clone_steps['id_project']]);
            $a = Html::a($count, $url, ['style' => 'color:#f0ad4e;']);
            echo  $a;
            ?>
        </td>
        <td>
            <?php
            $count  = $clone_steps[ChainClonesSteps::STATUS_DONE];
            $url = Url::to(['task/users-tasks', 'id_user' => Yii::$app->user->id, 'status' => ChainClonesSteps::STATUS_DONE,
                'id_project' => $clone_steps['id_project']]);
            $a = Html::a($count, $url, ['style' => 'color:green']);
            echo  $a;
            ?>
        </td>
        <td>
            <?php
            $url = Url::to(['task/users-tasks', 'id_user' => Yii::$app->user->id, 'status' => 'all',
                'id_project' => $clone_steps['id_project']]);
            $a = Html::a($clone_steps['count'], $url, ['style' => 'color:#000']);
            echo  $a;
            ?>
        </td>
    </tr>
    </tbody>
</table>