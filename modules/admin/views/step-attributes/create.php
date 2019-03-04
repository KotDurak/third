<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\StepAttributes */

$this->title = Yii::t('app', 'Create Step Attributes');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Step Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="step-attributes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
