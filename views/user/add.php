<?php

use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\jui\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\UserGroups;
use app\models\Groups;

$selectedGroups = [];

if(isset($_GET['id'])){
    $selectedGroups =  ArrayHelper::getColumn(UserGroups::find()->where(['id_user' => $_GET['id']])->asArray()->all(), 'id_group');
}

?>
    <div class="modal-content animated bounceInTop" >
        <?php
        $form = ActiveForm::begin(['id' => 'form-add-user',
            'options' => [
                'enctype'=>'multipart/form-data'
            ]]);
        ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-left">Добавить сотрудника</h4>
        </div>
        <div class="modal-body">
            <?php echo $form->field($user, 'name')->label('Имя'); ?>
            <?php echo $form->field($user, 'surname')->label('Фамилия'); ?>
            <?php echo $form->field($user, 'email')->label('Email');  ?>
            <?php echo $form->field($user, 'password')->label('Пароль'); ?>
            <?php
                echo $form->field($user, 'is_root')->checkbox([
                    'label' => 'Администраор (полный доступ)'
                ]);
            ?>
            <?php
                echo $form->field($user, 'is_moderator')->checkbox([
                   'label'  => 'Модератор (Доступ к задачам сотрудников)'
                ]);
            ?>
            <?= $form->field($user, 'status')->dropDownList([
                '5' => 'Не активирован',
                '1' => 'Активирован'
            ]); ?>
            <?php
               echo Html::dropDownList('groups', [],
                $groups, ['multiple' => 'true', 'class' => 'form-control']);
             ?>
            <?php echo $form->field($user, 'type_rate')->label('Тип ставки'); ?>

            <?php echo $form->field($user, 'rate'); ?>

            <?php echo $form->field($user, 'about')->textarea(); ?>
            <div class=" view-btn text-right">
                <button  type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                <?= Html::submitButton($user->isNewRecord ? 'Добавить' : 'Изменить', ['class' => 'btn btn-info', 'id' => 'submit']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php
$js = <<<JS
    $(document).ready(function() {
      $('#submit').on('click', function(e){
         e.preventDefault(); 
         var form_data = new FormData($('#form-add-user')[0]);
         var url = $('#form-add-user').attr('action');
         var pjax = 'users-list';
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
                    $.pjax.reload({container: '#' + $.trim(pjax)});     
               }
         });
         return false;
      });
    });
JS;
$this->registerJS($js);
?>