<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>
    <div class="modal-content animated bounceInTop" >
        <?php
        $form = ActiveForm::begin(['id' => 'form-add-comment']);
        ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-left">Комментарий к этапу</h4>
        </div>
        <div class="modal-body">
            <?= $form->field($model, 'comment')->textarea(); ?>
            <?= $form->field($model, 'id_user')->hiddenInput(['value'=> Yii::$app->user->id])->label(false); ?>
            <?= $form->field($model, 'timestamp')->hiddenInput(['value' => date('Y-m-d H:i:s')])->label(false); ?>
            <?= $form->field($model, 'id_step_clone')->hiddenInput(['value' => $id_clone])->label(false); ?>
            <div class=" view-btn text-right">
                <button  type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                <?= Html::submitButton($model->isNewRecord ? 'Отправить' : 'Изменить', ['class' => 'btn btn-info', 'id' => 'submit']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php
$js = <<<JS
    $(document).ready(function() {
      $('#submit').on('click', function(e){
         e.preventDefault(); 
         var form_data = new FormData($('#form-add-comment')[0]);
         var url = $('#form-add-comment').attr('action');
         $.ajax({
               url: url,
               dataType: 'JSON',  
               cache: false,
               contentType: false,
               processData: false,
               data: form_data,                     
               type: 'post',                        
               success: function(response){                      
                 $('#add-comment').modal('hide');
                   location.reload();
               }
         });
         return false;
      });
    });
JS;
$this->registerJS($js);
?>