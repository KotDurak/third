<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\file\FileInput;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\web\JsExpression;
use app\models\Chain;
use yii\jui\DatePicker;
use kartik\datetime\DateTimePicker;

$cb = <<<CB
    function(event, data, previewId, index){
         var form = data.form, files = data.files, extra = data.extra,
        response = data.response, reader = data.reader;
        var count =  response.count;
        $('.task-count .count').html(count);
        if(count > 0){
            $('#proj-import').removeAttr('disabled');
        }
     }
CB;



?>
    <div class="modal-content animated bounceInTop" >
        <?php
        $form = ActiveForm::begin(['id' => 'import-form', 'options' => [
            'enctype'=>'multipart/form-data'
        ]]);
        ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-left text-center">Импорт сементики и перелинковка</h4>
        </div>
        <div class="modal-body">
            <?= $form->field($import, 'file')->widget(FileInput::className(), [
                    'options' => [

                    ],
                    'pluginOptions'=>[
                        'showPreview' => false,
                        'uploadUrl' => Url::to(['/project/file-upload?id=' . $project->id])
                    ],
                    'pluginEvents'  => [
                       'fileuploaded' => $cb
                    ]
            ])->label('Импорт (импортируются колонки C,E,F,G)'); ?>

            <div class="col-md-12 task-count text-right">
                Количество задач <span class="count">0</span>
            </div>

            <?php
                echo $form->field($import, 'id_chain')->widget(Select2::className(), [
                    'initValueText' => 'Цепочка этапов',
                    'options' => ['placeholder' => 'Поиск цеопчки ...', 'id' => 'chain-select'],
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

            <div class="col-md-6" id="chain-options">

            </div>
            <div class="clearfix"></div>
            <div class="col-md-6 deadline">
                <?php

                echo $form->field($import, 'deadline')->widget(DatePicker::className(), [
                    'language'  => 'ru',
                    'dateFormat' => 'dd.MM.yyyy',
                    'clientOptions'    => [
                        'changeYear'    => true,
                        'changeMonth'    => true
                    ],
                    'options'  => [
                        'class' => 'form-control'
                    ]

                ])->label('Дедлайн');
                ?>
            </div>
            <div class="clearfix"></div>
            <div class=" view-btn text-right">
                <button  type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                <?= Html::submitButton('Импорт', ['class' => 'btn btn-default', 'id' => 'proj-import', 'disabled' => true]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php
$js = <<<JS
    $(document).ready(function() {
      $('#submit').on('click', function(e){
         e.preventDefault(); 
         var form_data = new FormData($('#form-add-project')[0]);
         var url = $('#form-add-project').attr('action');
        
         return false;
      });
    });
JS;
$this->registerJS($js);
?>