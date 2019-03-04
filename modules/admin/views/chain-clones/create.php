<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ChainClones */

$this->title = 'Create Chain Clones';
$this->params['breadcrumbs'][] = ['label' => 'Chain Clones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chain-clones-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
