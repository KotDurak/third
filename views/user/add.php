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

<?php
$form = ActiveForm::begin(['id' => 'form-add-user',
'options' => [
    'enctype'=>'multipart/form-data'
]]);
?>

<?php echo $form->field($user, 'name')->label('Имя'); ?>
<?php echo $form->field($user, 'surname')->label('Фамилия'); ?>
<?php echo $form->field($user, 'email')->label('Email');  ?>
<?php if(!isset($_GET['id'])): ?>
    <?php echo $form->field($user, 'password')->label('Пароль'); ?>
<?php endif; ?>
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
   /* echo Html::dropDownList('groups', [],
    $groups, ['multiple' => 'true', 'class' => 'form-control']); */
    echo Select2::widget([
        'name'   => 'groups',
        'value'  => '',
        'data'   => $groups,
        'options' => ['multiple' => true, 'placeholder' => 'Должности'],
        'value' => $selectedGroups
    ]);
 ?>
<?php echo $form->field($user, 'type_rate')->label('Тип ставки'); ?>

<?php echo $form->field($user, 'rate'); ?>

<?php echo $form->field($user, 'about')->textarea(); ?>
<div class=" view-btn text-right">
    <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
