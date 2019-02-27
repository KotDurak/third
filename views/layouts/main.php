<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?php if(!Yii::$app->user->isGuest || Yii::$app->request->pathInfo == 'auth/signup' || Yii::$app->request->pathInfo == 'auth/login'): ?>
<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'TD-CRM',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo '<div class="header-about">
       Система управления<br>
       созданием контента
</div>';
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left third-navbar'],
        'items' => [
            ['label' => 'Сводка', 'url' => ['/site/index']],
            ['label' => 'Проекты', 'url' => ['site/projects']],
            ['label' => 'Настройки',   'items' => [
                ['label' => 'Должности', 'url' => ['/site/index']],
                ['label' => 'Сотрудники', 'url' => ['/site/index']],
                ['label' => 'Цепочка этапов', 'url' => ['/site/index']]
            ]],

        ],

    ]);
    if(!Yii::$app->user->isGuest)
    echo '<div class="right-menu">'

        . Html::beginForm(['/site/logout'], 'post')
        . Html::submitButton(
            'Выход (' . Yii::$app->user->identity->name . ')',
            ['class' => 'btn btn-link logout']
        )
        . Html::endForm()
         .'
        </div>';
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>
<?php else:  ?>
<div class="auth-display">
    <div class="hello-form">
        <h1>CRM</h1>
        <div class="description text-center">
            Система управления созданием контента<br>
            от third-dimension.ru <br>
            <?= Html::a('Регистрация', ['auth/signup'], ['class' => 'auth-links']) ?>
            <br>
            <?= Html::a('Вход', ['auth/login'], ['class' => 'auth-links']) ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
