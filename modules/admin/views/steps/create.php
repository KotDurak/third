<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Steps */

$this->title = 'Create Steps';
$this->params['breadcrumbs'][] = ['label' => 'Steps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="steps-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
