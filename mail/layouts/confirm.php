<?php

use yii\helpers\Html;

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl([
    'auth/signup-confirm', 'token' => $user->email_confirm_token
]);
?>
<div class="password-reset">
    <p>Здравствуйте <?= Html::encode($user->name) ?>,</p>

    <p>Для регистарции на сервисе городов пройдите по ссылке:</p>

    <p><?= Html::a(Html::encode($confirmLink), $confirmLink) ?></p>
</div>
