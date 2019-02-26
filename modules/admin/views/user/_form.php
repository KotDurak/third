<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'surname')->textInput() ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'email')->input('email') ?>

    <?= $form->field($model, 'is_root')->dropDownList(['0' => 'Не имеет рут прав','1' => 'Рут права']); ?>

    <?php
        echo DatePicker::widget([
           'model'  => $model,
           'attribute'  => 'birthday',
            'language'  => 'ru',
            'dateFormat' => 'dd.MM.yyyy',
           'clientOptions'    => [
               'changeYear'    => true,
               'changeMonth'    => true
           ]
        ]);
    ?>

    <?= $form->field($model, 'status')->dropDownList([
        '5' => 'Не активирован',
        '1' => 'Активирован'
    ]); ?>



    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
