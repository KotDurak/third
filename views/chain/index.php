<?php
use yii\widgets\LinkPager;
use yii\helpers\Html;

$this->registerCssFile('/css/chain.css');
?>


<div class="container">
    <div class="row top-button">
            <?= Html::a('Добавить новую цепочку', ['/controller/action'], ['class'=>'btn btn-default']) ?>
    </div>
    <div class="row chain-list">
        <?php foreach ($chains as $chain): ?>
            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading"><strong>Цеопчка этапов - <?= $chain->name ?></strong></div>
                <div class="panel-body">
                    <p>...</p>
                </div>

                <!-- Table -->
                <table class="table">
                    ...
                </table>
            </div>
        <?php endforeach; ?>
    </div>
</div>



<?=  LinkPager::widget([
    'pagination' => $pages,
]); ?>

<?php
$this->registerJsFile('@web/js/chain.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<div class="modal inmodal add-chain" id="add-chain" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg"></div>
</div>
