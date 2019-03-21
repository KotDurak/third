<?php
use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    use yii\jui\DatePicker;
    use kartik\datetime\DateTimePicker;
    use kartik\select2\Select2;
    use yii\web\JsExpression;
    use app\models\SelectUserStep;
    use yii\helpers\Html;
    use app\models\User;
$this->params['breadcrumbs'][] = ['label' => 'Список задач', 'url' => ['/task/list?id_project='.$task->id_project]];
$this->params['breadcrumbs'][] = 'Редактировать задачу';

$this->registerJsFile('@web/js/task/update.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]);

    $projects = ArrayHelper::map($task->getProject()->asArray()->all(), 'id', 'name');

    $current_chain = ($task->getChains()->all());
    $current_chain = array_shift($current_chain);
    $modelEdit->id_chain = $current_chain->id;
    $initChainText = $current_chain->name;
    $modelSteps = [];
    $clone = $task->getChainClones()->all()[0];
    $modelsClonesSteps = [];

   /**
    * Текущие шаги для задачи;
   */
   foreach ($current_chain->getSteps()->orderBy(['sort' => SORT_ASC])->all() as $step){
        $clone_step = $step->getStepClones()->where(['id_clone' => $clone->id])->one();
        if(empty($clone_step)){
           $clone_step = new \app\models\ChainClonesSteps();
        }
        $modelsClonesSteps[] = $clone_step;
        $modelSteps[] = new SelectUserStep([
             'id_step' => $step->id,
             'label'   => $step->name,
             'id_group'   => $step->id_group,
             'id_user'  => $clone_step->id_user
         ]);
     }

    


?>

<?php $form =  ActiveForm::begin([
    'id'    => 'update-task',
    'options' => [
        'enctype'=>'multipart/form-data'
    ]
]); ?>
<div class="row">
    <div class="col-md-3">
        <?= $form->field($task, 'id_project')->dropDownList($projects, [
            'disabled'  => true
        ])->label('Проект'); ?>
    </div>
    <div class="col-md-3">
        <?=  $form->field($task, 'name')->textInput()->label('Название задачи'); ?>
    </div>
    <div class="col-md-3">
        <?php
            echo $form->field($task, 'created')->widget(DatePicker::className(), [
                'language'  => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'clientOptions'    => [
                    'changeYear'    => true,
                    'changeMonth'    => true
                ],
                'options'  => [
                    'class' => 'form-control'
                ]

            ])->label('Дата создания');
        ?>
    </div>
    <div class="col-md-3">
        <?php
            echo $form->field($task, 'deadline')->widget(DateTimePicker::className(), [
                'language'  => 'ru',
                'options' => ['placeholder' => 'Дедлайн ...'],
                'convertFormat' => true,
                'value'=> date("d.m.Y h:i"),
                'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions'    => [
                    'format' => 'dd.MM.yyyy hh:i',
                    'showMeridian' => true,
                    'autoclose'=>true,
                    'weekStart'=>1,
                    'startDate' => '01.05.2015 00:00',
                    'todayBtn'=>true,
                    'buttonImage'   =>  Yii::getAlias('@images') . '/calendar.png'
                ],
            ])->label('Срок сдачи задачи');
        ?>
    </div>
</div>
<div class="row task-for-content">
    <div class="col-md-9" id="step-cpntainer">
        <?php foreach ($modelsClonesSteps as $i => $modelsClonesStep): ?>
            <div class="col-md-12 step-item">
                <h4 class="text-left"><?php echo  $modelsClonesStep->step->name;?></h4>
                <br>
                <?php
                echo   $form->field($modelsClonesStep, "[{$i}]status")->radioList(array(0 => 0,2 => 2, 1 => 1,3 => 3), [
                    'class' => 'btn-group radio-colors',
                    'data-toggle' => 'buttons',
                    'unselect' => 0,
                    'item'  => function($index,$label,$name,$checked,$value){
                        switch ($value){
                            case 1:{
                                $class = 'btn-warning';
                                break;
                            }
                            case  2:{
                                $class= 'btn-danger';
                                break;
                            }
                            case 3:{
                                $class = 'btn-success';
                                break;
                            }
                            case 0:{
                                $class = 'btn-default';
                                break;
                            }
                            default:
                                $class  = '';
                                break;
                        }
                        if($checked){
                            $class .= ' active';
                        }
                        return Html::radio($name,
                            $checked,
                            [
                                'label' => \app\models\ChainClonesSteps::getLabel($label),
                                'value' => $value,
                                'labelOptions' => ['class' => 'btn  circle-conttrols ' . $class]
                            ]);
                    }
                ])->label(false);
              ?>

            </div>
        <?php endforeach; ?>
    </div>
    <div class="col-md-3">
        <?php

            echo $form->field($modelEdit, 'id_chain')->widget(Select2::className(), [
                'initValueText' => $initChainText,
                'options' => [
                    'placeholder' => 'Поиск цеопчки ...',
                    'id' => 'chain-select',
                ],
                'pluginOptions' => [
                    'allowClear'    => true,
                    'minimumInputLength' => 0,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Ожидаение результатов..'; }"),
                    ],
                    'ajax'  => [
                        'url'   => \yii\helpers\Url::to(['chain-list']),
                        'dataType'  => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(city) { return city.text; }'),
                    'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                ],
                'pluginEvents' => [
                    "change" => "function(e) {
                               
                            }"]
            ])->label('Цепочка этапов');
            ?>
        <div class="col-md-12" id="chain-options">
            <?php
                foreach ($modelSteps as $i => $modelStep){
                    echo $form->field($modelStep, "[{$i}]id_user")->widget(Select2::className(),[
                        'options' => ['placeholder' => 'Выберите сотрудника ...', 'class' => 'users-select'],
                        'initValueText' => User::getUserName($modelStep->id_user),
                        'pluginOptions' => [
                            'allowClear'    => true,
                            'minimumInputLength' => 0,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Ожидаение результатов..'; }"),
                            ],
                            'ajax'  => [
                                'url'   => \yii\helpers\Url::to(['users-list', 'id_group' => $modelStep->id_group]),
                                'dataType'  => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(result) {  return result.text; }'),
                            'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                        ],
                    ])->label($modelStep->label);

                    echo Html::activeHiddenInput($modelStep, "[{$i}]id_step");
                }
            ?>
        </div>
    </div>
</div>
<div class="row text-right">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-default', 'id' => 'task-save']); ?>
</div>
<?php ActiveForm::end(); ?>