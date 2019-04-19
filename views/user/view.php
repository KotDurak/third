<?php
use yii\widgets\DetailView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

echo DetailView::widget([
   'model'  => $user,
    'attributes' => [
        'name',
        'surname',
        [
            'label' => 'Email',
            'format'    => 'html',
            'value' => function($data){
                return Html::mailto($data->email, $data->email);
            }
        ],
        [
            'label' => 'Должности',
            'format'   => 'html',
            'value' => function($data){
                $groups = ArrayHelper::map($data->groups, 'id', 'name');
                return Html::ul($groups, ['class' => 'ul-no-marker']);
            }
        ]
    ]
]);
?>