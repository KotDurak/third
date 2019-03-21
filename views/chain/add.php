<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

?>
<style>
    .add-item{
        padding: 5px 10px;
        font-size: 14px;
    }
</style>
<div class="modal-content animated bounceInTop" >
    <?php
        $form = ActiveForm::begin(['id' => 'form-add-chain']);
    ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-left">Добавить цепочку</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($modelChain, 'name')->textInput()->label('Название цепочки'); ?>
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
                'model' => $modelSteps[0],
                'formId'   => 'form-add-chain',
                'formFields'    => [
                    'sort',
                    'name',
                    'id_group',
                    'type'
                ]
            ]);
        ?>
        <div class="panel panel-default">
            <div class="panel-heading text-right">
                <button type="button" class="add-item btn btn-success btn-xs"><i class="fa fa-plus"></i>Добавить этап</button>
            </div>
            <div class="panel-body">

                <div class="container-items">
                    <?php foreach ($modelSteps as $i => $modelStep): ?>
                    <div class="item panel panel-default">
                        <div class="panel-heading">
                            <div class="pull-right">
                                <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <?php
                            if (!$modelStep->isNewRecord) {
                                echo Html::activeHiddenInput($modelStep, "[{$i}]id");
                            }
                            ?>
                            <div class="row">
                                <div class="col-md-2">
                                    <?= $form->field($modelStep, "[{$i}]sort")->textInput(['type' => 'number'])->label('Порядок'); ?>
                                </div>
                                <div class="col-md-2">
                                    <?= $form->field($modelStep, "[{$i}]name")->label('Имя этапа'); ?>
                                </div>
                                <div class="col-md-2">
                                    <?= $form->field($modelStep, "[{$i}]type")->label('Тип')->dropDownList([
                                        'attributes'    => 'Атрибуты',
                                        'table'         => 'Таблица'
                                    ]); ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($modelStep, "[{$i}]id_group")
                                        ->label('Должность исполнителей')->dropDownList($groups); ?>
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
         var form_data = new FormData($('#form-add-chain')[0]);
         var url = $('#form-add-chain').attr('action');
         $.ajax({
               url: url,
               dataType: 'JSON',  
               cache: false,
               contentType: false,
               processData: false,
               data: form_data,                     
               type: 'post',                        
               success: function(response){                      
                 $('#add-chain').modal('hide');
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