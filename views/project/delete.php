<?php
 use yii\helpers\Html;
 use yii\widgets\ActiveForm;
?>

<div class="modal-content animated bounceInTop" >
    <?php
    $form = ActiveForm::begin(['id' => 'delete-project']);
    ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-left">Удаление проекта</h4>
    </div>
    <div class="modal-body">
        <p>Удалить <?= $model->name; ?> ?</p>
      <?php  echo $form->field($model, 'id')->hiddenInput(['value'=> $model->id])->label(false); ?>
        <div class=" view-btn text-right">
            <button  type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
            <?= Html::submitButton('Удалить', ['class' => 'btn btn-danger', 'id' => 'delete-proj']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$js = <<<JS
    $(document).ready(function() {
      $('#delete-proj').on('click', function(e){
         e.preventDefault(); 
         var form_data = new FormData($('#delete-project')[0]);
         var url = $('#delete-project').attr('action');
         $.ajax({
               url: url,
               dataType: 'JSON',  
               cache: false,
               contentType: false,
               processData: false,
               data: form_data,                     
               type: 'post',                        
               success: function(response){                      
                 $('#delete').modal('hide');
                 $('.list-content').load($('.list-content').attr('url'));
               }
         });
         return false;
      });
    });
JS;
$this->registerJS($js);
?>