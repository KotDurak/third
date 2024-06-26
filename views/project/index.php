<?php
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\Project;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\bootstrap\Modal;

$this->params['breadcrumbs'][] = 'Проекты';
$this->registerJsFile('@web/js/project_add.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]);

?>
<?php if(Yii::$app->user->identity->is_root): ?>
<div class="col-md-12 text-right">
    <?= Html::a('<i class="glyphicon glyphicon-plus"></i>Создать проект', [ Yii::$app->urlManager->createUrl('project/add')], ['class'=>'btn btn-success circle-conttrols', 'id' => 'create-project']) ?>
</div>
<?php endif; ?>

<div class="list-content" url="<?php echo  Yii::$app->urlManager->createUrl('project/list'); ?>">
<?php echo $this->render('/project/list', compact('dataProvider', 'projectSearch')); ?>
</div>


<div class="modal inmodal add" id="add" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg"></div>
</div>

<div class="modal inmodal delte" id="delete" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg"></div>
</div>

<div class="modal inmodal import" id="import" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg"></div>
</div>