<?php
    use app\models\ChainClonesSteps;
    use yii\helpers\Url;
    use yii\helpers\Html;

    $clone_steps = ChainClonesSteps::getStepsByWorker();
?>
<div class="row">
    <div class="col-md-12">
        <table class="table">
            <thead>
            <tr>
                <th></th>
                <th>На доработке</th>
                <th>В работе</th>
                <th>Принятые</th>
                <th>Всего</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="font-weight: bold;">По задачам</td>
                <td>
                    <?php
                        $count  = $clone_steps[ChainClonesSteps::STATUS_REWORK];
                        $url = Url::to(['task/users-tasks', 'id_user' => Yii::$app->user->id, 'status' => ChainClonesSteps::STATUS_REWORK]);
                        $a = Html::a($count, $url, ['style' => 'color:red']);
                    ?>
                    <?php echo $a;?>
                </td>
                <td><?php echo $clone_steps[ChainClonesSteps::STATUS_WORK]; ?></td>
                <td><?php echo $clone_steps[ChainClonesSteps::STATUS_DONE]; ?></td>
                <td><?php echo $clone_steps['count']; ?></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
