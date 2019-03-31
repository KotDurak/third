<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>
    <div class="modal-content animated bounceInTop" >
        <?php
            $form = ActiveForm::begin(['id' => 'form-add-external']);
        ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-left">Добавить внешний источник</h4>
        </div>
        <div class="modal-body">
            <div class="col-md-12">
                <?php echo $form->field($model, 'src')->textInput()->label('Ссылка'); ?>
            </div>
            <div class=" view-btn text-right">
                <button  type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                <?= Html::submitButton('Добавить внешний источник', ['class' => 'btn btn-info', 'id' => 'submit']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php
$js = <<<JS
    $(document).ready(function() {
      $('#submit').on('click', function(e){
         e.preventDefault(); 
         var form_data = new FormData($('#form-add-external')[0]);
         var url = $('#form-add-external').attr('action');
         $.ajax({
               url: url,
               dataType: 'JSON',  
               cache: false,
               contentType: false,
               processData: false,
               data: form_data,                     
               type: 'post',                        
               success: function(response){                      
                 $('#external').modal('hide');
                   location.reload();
               }
         });
         return false;
      });
    });
JS;
$this->registerJS($js);
?>