<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\file\FileInput;
use yii\helpers\Url;

$comments = $model->getComments()->all();

?>
    <style>
        .add-item{
            padding: 5px 10px;
            font-size: 14px;
        }
    </style>
    <div class="modal-content animated bounceInTop" >
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-left">Комментарии</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 comments-list">
                       <?php foreach($comments as $comment): ?>
                           <?php
                                if($comment->user->is_admin()){
                                    $class = 'alert-danger';
                                } else {
                                    $class = 'alert-info';
                                }
                                $time = date('d.m.Y H:i', strtotime($comment->timestamp));
                            ?>
                       <div class="alert <?php echo $class; ?>">
                           <p><?php echo $comment->comment; ?></p>
                           <small>(<?php echo $comment->getCommentator() . ' ' . $time; ?> )</small>
                       </div>
                    <?php  endforeach;?>
                </div>
            </div>
            <?php if(Yii::$app->user->identity->is_admin() || $model->id_user == Yii::$app->user->id): ?>
                <div class="row">
                    <?php $form = ActiveForm::begin(['id' => 'worker-comment']); ?>
                    <?php echo $form->field($model_comment, 'comment')->textarea([
                            'placeholder' => 'Введите комментарий если невозможна работа с этапом'])->label(false); ?>
                    <?= $form->field($model_comment, 'id_user')->hiddenInput(['value'=> Yii::$app->user->id])->label(false); ?>
                    <?= $form->field($model_comment, 'timestamp')->hiddenInput(['value' => date('Y-m-d H:i:s')])->label(false); ?>
                    <?= $form->field($model_comment, 'id_step_clone')->hiddenInput(['value' => $model->id])->label(false); ?>
                    <?php ActiveForm::end(); ?>
                </div>
            <?php endif; ?>
            <div class=" view-btn text-right">
                <?php if(Yii::$app->user->identity->is_admin() || $model->id_user == Yii::$app->user->id): ?>
                    <?= Html::submitButton('Комментировать', ['class' => 'btn btn-info', 'id' => 'submit-comment']) ?>
                <?php endif; ?>
                <button  type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
            </div>
        </div>

    </div>

<?php
$js = <<<JS
    $(document).ready(function() {
        $('.comments-list').scrollTop($('.comments-list').height());
        
      $('#submit-comment').on('click', function(e){
         e.preventDefault(); 
         var form_data = new FormData($('#worker-comment')[0]);
         var url = $('#worker-comment').attr('action');
         $.ajax({
               url: url,
               dataType: 'JSON',  
               cache: false,
               contentType: false,
               processData: false,
               data: form_data,                     
               type: 'post',                        
               success: function(response){                      
                  $('#comment').modal('hide');
                 window.location.reload();
               }
         });
         return false;
      });
    });
JS;
$this->registerJS($js);
?>