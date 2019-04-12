<?php
    use app\models\ChainClonesSteps;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use app\models\Project;
    use yii\helpers\ArrayHelper;
    use yii\jui\DatePicker;
    use kartik\datetime\DateTimePicker;

$this->registerJsFile('@web/js/site/workers.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]);

    $clone_steps = ChainClonesSteps::getStepsByWorker();
    $projects = Project::find()->asArray()->all();
    $projects = ArrayHelper::map($projects, 'id', 'name');
    $projects[0] = 'Не выбрано';
    ksort($projects);

?>
<div class="row">
    <div class="col-md-12 worker-block">
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
                        echo $a;
                    ?>
                </td>
                <td>
                    <?php
                        $count  = $clone_steps[ChainClonesSteps::STATUS_WORK];
                        $url = Url::to(['task/users-tasks', 'id_user' => Yii::$app->user->id, 'status' => ChainClonesSteps::STATUS_WORK]);
                        $a = Html::a($count, $url, ['style' => 'color:#f0ad4e;']);
                        echo  $a;
                     ?>
                </td>
                <td>
                    <?php
                        $count  = $clone_steps[ChainClonesSteps::STATUS_DONE];
                        $url = Url::to(['task/users-tasks', 'id_user' => Yii::$app->user->id, 'status' => ChainClonesSteps::STATUS_DONE]);
                        $a = Html::a($count, $url, ['style' => 'color:green']);
                        echo  $a;
                    ?>
                </td>
                <td>
                    <?php
                        $url = Url::to(['task/users-tasks', 'id_user' => Yii::$app->user->id, 'status' => 'all']);
                        $a = Html::a($clone_steps['count'], $url, ['style' => 'color:#000']);
                        echo  $a;
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
        <div class="col-md-12" id="project-container">

        </div>
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
                echo '<div class="col-md-6">' . DatePicker::widget([
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
