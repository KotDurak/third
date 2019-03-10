<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    use yii\jui\DatePicker;
    use kartik\datetime\DateTimePicker;

    $projects = ArrayHelper::map($task->getProject()->asArray()->all(), 'id', 'name');

?>

<?php $form =  ActiveForm::begin([
    'id'    => 'update-task',
    'options' => [
        'enctype'=>'multipart/form-data'
    ]
]); ?>
<div class="row task-form-shadow">
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
                //  'dateFormat' => 'dd.MM.yyyy',
                'convertFormat' => true,
                'value'=> date("d.m.Y h:i"),
                'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions'    => [
                    'format' => 'dd.MM.yyyy hh:i',
                    'showMeridian' => true,
                    'autoclose'=>true,
                    'weekStart'=>1, //неделя начинается с понедельника
                    'startDate' => '01.05.2015 00:00', //самая ранняя возможная дата
                    'todayBtn'=>true, //снизу кнопка "сегодня"
                    'buttonImage'   =>  Yii::getAlias('@images') . '/calendar.png'
                ],
            ])->label('Срок сдачи задачи');
        ?>
    </div>
</div>
<div class="row task-for-content">
    <div class="col-md-9 task-form-shadow">
        Шаги
    </div>
    <div class="col-md-3 task-form-shadow">
        Настройка цепочек
    </div>
</div>
<?php ActiveForm::end(); ?>