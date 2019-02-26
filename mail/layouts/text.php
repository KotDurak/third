<?php
$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/signup-confirm', 'token' => $user->email_confirm_token]);
?>
Здравствуйте <?= $user->name ?>,

Перейдите по ссылке для завершения регистрации

<?= $confirmLink ?>;