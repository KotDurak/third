<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
    <div class="modal-content animated bounceInTop" >
        <?php
        $form = ActiveForm::begin(['id' => 'import-form']);
        ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-left text-center">Импорт сементики и перелинковка</h4>
        </div>
        <div class="modal-body">
          qwdqwdq

            <div class=" view-btn text-right">
                <button  type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Изменить', ['class' => 'btn btn-info', 'id' => 'proj=import']) ?>
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