<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
?>

<div class="row">
    <h1>Регистрация</h1>
    <?php
    $form = ActiveForm::begin([
        'id'    => 'login-form',

    ]);
    ?>

    <?= $form->field($model, 'name')->textInput()->label('Имя'); ?>

    <?= $form->field($model, 'surname')->textInput()->label('Фамилия') ?>

    <?= $form->field($model, 'email')->textInput()->label('E-mail'); ?>


    <?= $form->field($model, 'password')->passwordInput()->label('Пароль'); ?>

    <?= $form->field($model, 'confirmPassword')->passwordInput()->label('Подтвердите пароль'); ?>

    <?=  $form->field($model, 'capture')->widget(Captcha::className(), [
        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
    ]) ?>


    <div class="form-group">
        <div class="">
            <?= Html::submitButton('Регистрация', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
