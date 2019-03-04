<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Chain */

$this->title = 'Create Chain';
$this->params['breadcrumbs'][] = ['label' => 'Chains', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chain-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
