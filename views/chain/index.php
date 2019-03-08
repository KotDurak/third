<?php
use yii\widgets\LinkPager;
use yii\helpers\Html;
use Yii\helpers\Url;
use app\models\Steps;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

$this->registerCssFile('/css/chain.css');
?>


<div class="container">
    <div class="row top-button">
            <?= Html::a('Добавить новую цепочку', ['/chain/add'], ['class'=>'btn btn-default', 'id' => 'create-chain']) ?>
    </div>
    <?php
        Pjax::begin([
            'id'    => 'chain-pjax',
            'enablePushState' => false,
        ]);
    ?>
    <div class="row chain-list">
        <?php foreach ($chains as $chain): ?>
            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading"><strong>Цеопчка этапов - <?= $chain->name ?></strong></div>
                <div class="panel-body">
                    <?php
                        $steps = ($chain->getSteps()->orderBy(['sort' => SORT_ASC]));
                        $provider = new ActiveDataProvider([
                            'query' => $steps,
                        ]);
                        echo  GridView::widget([
                            'options'   => [
                               'class'   => 'step-table'
                            ],
                            'dataProvider' => $provider,
                            'columns' => [
                                [
                                    'class' => 'yii\grid\CheckboxColumn',
                                ],
                                [
                                    'attribute' => 'sort',
                                    'label'     => 'Порядок'
                                ],
                                [
                                    'attribute'  => 'name',
                                    'label'      => 'Название этапа'
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template'  => '{add-attr} {update} {delete}',
                                    'buttons'   => [
                                        'add-attr'  => function($url, $model){
                                            return Html::a(
                                                '<span class="glyphicon glyphicon-list-alt"></span>',
                                                   $url,[
                                                      'data-toggle' => 'modal',
                                                      'data-target' => '#add-attr',
                                                      'onclick'     => '$("#add-attr .modal-dialog").load($(this).attr("href"));'
                                                ]
                                            );
                                        },
                                        'update'    => function($url, $model){
                                            return Html::a(
                                                '<span class="glyphicon glyphicon-pencil"></span>',
                                                Url::to(['edit-step', 'id' => $model->id]),[
                                                    'data-toggle' => 'modal',
                                                    'data-target' => '#edit-step',
                                                    'onclick'     => '$("#edit-step .modal-dialog").load($(this).attr("href"));'
                                                ]
                                            );
                                        },
                                        'delete'      => function($url, $model, $key){
                                            $url =  Url::to(['delete-step', 'id' => $model->id]);
                                            return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                                                false,
                                                    [
                                                        'class' => 'ajaxDelete',
                                                        'delete-url'    => $url,
                                                        'pjax-container' => 'chain-pjax',
                                                        'title'          => Yii::t('app', 'Delete')
                                                    ]
                                                );

                                        }
                                    ]
                                ],
                            ],
                        ]);
                    ?>
                </div>

                <!-- Table -->
                <table class="table">
                    ...
                </table>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
        Pjax::end();
    ?>
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

<div class="modal inmodal add-attr" id="add-attr" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg"></div>
</div>

<div class="modal inmodal edit-step" id="edit-step" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg"></div>
</div>
