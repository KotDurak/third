<?php
$url= Yii::$app->urlManager->createAbsoluteUrl(['site/index']);
?>
Здравствуйте <?= $user->name ?>

Ваш новый пароль в системе  <?php echo $password; ?> <br>
Ваш логин <?php echo $user->email; ?>



