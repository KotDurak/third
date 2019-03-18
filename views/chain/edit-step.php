<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\file\FileInput;
?>
    <style>
        .add-item{
            padding: 5px 10px;
            font-size: 14px;
        }
    </style>
    <div class="modal-content animated bounceInTop" >
        <?php
            $form = ActiveForm::begin(['id' => 'form-edit-step']);
            if($modelStep->isNewRecord){
                $header = 'Добавить';
            } else{
                $header = 'Редактировать';
            }
        ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-left"><?= $header; ?> шаг</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-2">

                        <?= $form->field($modelStep, 'sort')->textInput(['type' => 'number'])->label('Порядок'); ?>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($modelStep, "name")->label('Имя этапа'); ?>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($modelStep, "type")->label('Тип')->dropDownList([
                            'attributes'    => 'Атрибуты',
                            'table'         => 'Таблица'
                        ]); ?>
                    </div>
                    <div class="col-md-6">

                        <?= $form->field($modelStep, "id_group")
                            ->label('Должность исполнителей')->dropDownList($groups, [
                                'options'   => [

                                ]
                            ]); ?>
                    </div>

                    <div class="col-md-12">
                        <?php
                            echo FileInput::widget([
                                'model' => $modelFiles,
                                'attribute' => 'file[]',
                                'name'      => 'file[]',
                                'options'   => [ 'multiple' => true],
                                'pluginOptions' => [
                                    'maxFileCount' => 10,
                                  //  'overwriteInitial' => false,
                                ]
                            ]);
                        ?>
                    </div>
                </div>
            </div>
            <?php
            DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper',
                'widgetBody' => '.container-items',
                'widgetItem' => '.item',
                'min' => 0,
                'limit' => 999,
                'insertButton' => '.add-item',
                'deleteButton' => '.remove-item',
                'model' => $modelAttributes[0],
                'formId'   => 'form-edit-step',
                'formFields'    => [
                    'name',
                    'index',
                    'def_value'
                ]
            ]);
            ?>
            <div class="panel panel-default">
                <div class="panel-heading text-right">
                    <button type="button" class="add-item btn btn-success btn-xs"><i class="fa fa-plus"></i>Добавить аттрибут</button>
                </div>
                <div class="panel-body">

                    <div class="container-items">
                        <?php foreach ($modelAttributes as $i => $modelAttribute): ?>
                            <div class="item panel panel-default">
                                <div class="panel-heading">
                                    <div class="pull-right">
                                        <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="panel-body">
                                    <?php
                                    if (!$modelAttribute->isNewRecord) {

                                        echo Html::activeHiddenInput($modelAttribute, "[{$i}]id");
                                    }
                                    ?>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?= $form->field($modelAttribute, "[{$i}]name")->label('Имя этапа'); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($modelAttribute, "[{$i}]index")
                                                ->textInput()->label('Уникальный индекс');?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($modelAttribute, "[{$i}]def_value")
                                                ->textInput()->label('Значение по умолчанию');?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    </div>
                    <?php DynamicFormWidget::end(); ?>
                    <div class=" view-btn text-right">
                        <button  type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        <?= Html::submitButton($modelChain->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => 'btn btn-success', 'id' => 'submit']) ?>
                    </div>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

<?php
$js = <<<JS
    $(document).ready(function() {
      $('#submit').on('click', function(e){
         e.preventDefault(); 
         var form_data = new FormData($('#form-edit-step')[0]);
         var url = $('#form-edit-step').attr('action');
         $.ajax({
               url: url,
               dataType: 'JSON',  
               cache: false,
               contentType: false,
               processData: false,
               data: form_data,                     
               type: 'post',                        
               success: function(response){                      
                 $('#edit-step').modal('hide');
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