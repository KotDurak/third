<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\file\FileInput;
use yii\helpers\Url;
?>
    <style>
        .add-item{
            padding: 5px 10px;
            font-size: 14px;
        }
    </style>
    <div class="modal-content animated bounceInTop" >
        <?php
        $form = ActiveForm::begin(['id' => 'form-upload',
            'options'=>['enctype'=>'multipart/form-data']
        ]);

        ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-left">Загрузить файлы</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-2">

                        <?= $form->field($model, 'id')->hiddenInput()->label(false); ?>
                    </div>


                    <div class="col-md-12">
                        <?php
                        echo FileInput::widget([
                            'model' => $file,
                            'attribute' => 'file[]',
                            'name'       => 'file[]',
                            'options'   => [ 'multiple' => true],
                            'pluginOptions' => [
                             //   'uploadUrl' => Url::to(['/chain/upload', 'id' => $model->id]),
                                'maxFileCount' => 10,
                                'overwriteInitial' => false,
                            ]
                        ]);
                        ?>
                    </div>
                </div>
            </div>

            <div class=" view-btn text-right">
                <button  type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                <?= Html::submitButton($modelChain->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => 'btn btn-success', 'id' => 'submit']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

<?php
$js = <<<JS
    $(document).ready(function() {
      $('#submit').on('click', function(e){
         e.preventDefault(); 
         var form_data = new FormData($('#form-upload')[0]);
         var url = $('#form-upload').attr('action');
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
                  $.pjax.reload({
                          container: "#chain-pjax" 
                   });
               }
         });
         return false;
      });
    });
JS;
$this->registerJS($js);
?>