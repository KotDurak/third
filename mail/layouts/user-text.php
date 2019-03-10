<?php
$url= Yii::$app->urlManager->createAbsoluteUrl(['site/indec']);
?>
Здравствуйте <?= $user->name ?>

Ваш пароль от сайта <?php echo $password; ?> <br>
Ваш логин <?php echo $user->email; ?>
