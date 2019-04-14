<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\UserGroups;
use app\models\Groups;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

  $selectedGroups = [];
  if(isset($_GET['id'])){
      $selectedGroups =  ArrayHelper::getColumn(UserGroups::find()->where(['id_user' => $_GET['id']])->asArray()->all(), 'id_group');
  }


?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'surname')->textInput() ?>

    <?php if(!isset($_GET['id'])): ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?php endif; ?>

    <?= $form->field($model, 'email')->input('email') ?>

    <?= $form->field($model, 'is_root')->dropDownList(['0' => 'Не имеет рут прав','1' => 'Рут права']); ?>

    <?php echo $form->field($model, 'birthday')->widget(DatePicker::className(), [
        'language'  => 'ru',
        'dateFormat' => 'dd.MM.yyyy',
        'clientOptions'    => [
            'changeYear'    => true,
            'changeMonth'    => true
        ],
    ]); ?>


    <?= $form->field($model, 'status')->dropDownList([
        '5' => 'Не активирован',
        '1' => 'Активирован'
    ]); ?>

    <?php
        echo Select2::widget([
           'name'   => 'groups',
           'value'  => '',
           'data'   => $groups,
            'options' => ['multiple' => true, 'placeholder' => 'Должности'],
            'value' => $selectedGroups
        ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
