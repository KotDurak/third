<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    use yii\jui\DatePicker;
    use kartik\datetime\DateTimePicker;
    use kartik\select2\Select2;
    use yii\web\JsExpression;
    use app\models\SelectUserStep;
    use yii\helpers\Html;

$this->registerJsFile('@web/js/task/update.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]);

    $projects = ArrayHelper::map($task->getProject()->asArray()->all(), 'id', 'name');

    $current_chain = ($task->getChains()->all());
    $current_chain = array_shift($current_chain);
    $modelEdit->id_chain = $current_chain->id;
    $initChainText = $current_chain->name;
    $modelSteps = [];
     foreach ($current_chain->getSteps()->orderBy(['sort' => SORT_ASC])->all() as $step){
         $modelSteps[] = new SelectUserStep([
             'id_step' => $step->id,
             'label'   => $step->name,
             'id_group'   => $step->id_group
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
    <div class="col-md-9">
        Шаги
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
<?php ActiveForm::end(); ?>