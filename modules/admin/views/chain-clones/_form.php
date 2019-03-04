<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ChainClones */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="chain-clones-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_chain')->textInput() ?>

    <?= $form->field($model, 'id_task')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
