<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Steps */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="steps-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_chain')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList([ 'table' => 'Table', 'attributes' => 'Attributes', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
