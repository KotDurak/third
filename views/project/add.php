<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
?>
<div class="modal-content animated bounceInTop" >
    <?php
    $form = ActiveForm::begin(['id' => 'form-add-project']);
    ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-left">Создать проект</h4>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'name')->textInput()->label('Назввание проекта'); ?>
        <?= $form->field($model, 'url')->textInput()->label('Сайт проекта');?>

        <div class=" view-btn text-right">
            <button  type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
            <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Изменить', ['class' => 'btn btn-info', 'id' => 'submit']) ?>
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
         $.ajax({
               url: url,
               dataType: 'JSON',  
               cache: false,
               contentType: false,
               processData: false,
               data: form_data,                     
               type: 'post',                        
               success: function(response){                      
                 $('#add').modal('hide');
                 $('.list-content').load($('.list-content').attr('url'));
               }
         });
         return false;
      });
    });
JS;
$this->registerJS($js);
?>