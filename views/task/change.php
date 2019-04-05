<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\file\FileInput;
use yii\helpers\Url;
use app\models\Chain;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\JsExpression;

$steps = Chain::findOne($data['id_chain'])->getSteps()->orderBy(['sort' => SORT_ASC])->asArray()->all();
array_unshift($steps, [
        'id' => 0,
        'name'  => '...'
]);

$steps = ArrayHelper::map($steps, 'id', 'name');



?>
    <style>
        .add-item{
            padding: 5px 10px;
            font-size: 14px;
        }
    </style>
    <div class="modal-content animated bounceInTop" >
        <?php
        $form = ActiveForm::begin(['id' => 'form-change',
            'options'=>['enctype'=>'multipart/form-data']
        ]);

        ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-left">Изменить выделенные задачи</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <?php echo $form->field($model, 'id_step')->dropDownList($steps, [
                            'id'         => 'step',
                            'onchange'  => '
                                $.post("/task/steps-list?id_step=" + $(this).val(), function(data){
                                    $("#user").html(data);
                                });
                            '
                    ])->label('Стадия задания'); ?>
                </div>
                <div class="col-md-6">
                    <?php
                      echo $form->field($model, 'id_user')->dropDownList([], [
                         'id'   => 'user'
                      ])->label('Кому назначено');
                    ?>
                </div>
                <div class="col-md-12">
                    <?php foreach ($tasks as $task): ?>
                       <?php
                             echo Html::activeHiddenInput($task, "[{$i}]id");
                        ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class=" view-btn text-right">
                <button  type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'id' => 'submit']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

<?php
$js = <<<JS
    $(document).ready(function() {
           
      $('#submit').on('click', function(e){
         e.preventDefault(); 
         var form_data = new FormData($('#form-change')[0]);
         var url = $('#form-change').attr('action');
         $.ajax({
               url: url,
               dataType: 'JSON',  
               cache: false,
               contentType: false,
               processData: false,
               data: form_data,                     
               type: 'post',                        
               success: function(response){                      
                  $('#upload').modal('hide');
                 window.location.reload();
               }
         });
         return false;
      });
    });
JS;
$this->registerJS($js);
?>