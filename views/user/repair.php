<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
    <div class="modal-content animated bounceInTop" >
        <?php
        $form = ActiveForm::begin(['id' => 'form-repair']);
        ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-left">Новый пароль</h4>
        </div>
        <div class="modal-body">
            <?= $form->field($model, 'password')->textInput([
                'value' => '',
                'placeholder'   => 'Новый пароль для сотрудника ' . $model->surname . ' ' . $model->name
            ])->label(''); ?>
            <div class=" view-btn text-right">
                <button  type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                <?= Html::submitButton('Смена пароля', ['class' => 'btn btn-info', 'id' => 'submit']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php
$js = <<<JS
    $(document).ready(function() {
      $('#submit').on('click', function(e){
         e.preventDefault(); 
         var form_data = new FormData($('#form-repair')[0]);
         var url = $('#form-repair').attr('action');
         $.ajax({
               url: url,
               dataType: 'JSON',  
               cache: false,
               contentType: false,
               processData: false,
               data: form_data,                     
               type: 'post',                        
               success: function(response){                      
                 $('#repair').modal('hide');
               }
         });
         return false;
      });
    });
JS;
$this->registerJS($js);
?>