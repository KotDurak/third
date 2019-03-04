<?php
    use yii\widgets\Menu;


    echo Menu::widget([
        'items' => [
            ['label' => 'Цепочки этапов', 'url' => '/admin/chain'],
            ['label' => 'Этапы', 'url' => '/admin/steps'],
            ['label'    => 'Клоны цепочек', 'url'   => 'admin/chain-clones']
        ],
        'options' => [
            'id'=>'navid',
            'class' => 'navbar-nav',
            'style'=>'font-size: 16px; list-style-type:none;',
            'data'=>'menu',
        ],
        'itemOptions'=>['style'=>'font-size = 12px; margin-left:10px'],
    ]);
?>