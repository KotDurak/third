<?php
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\Html;

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