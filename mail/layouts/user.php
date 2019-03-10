<?php

use yii\helpers\Html;

$url = Yii::$app->urlManager->createAbsoluteUrl(['site/index']);
?>
<div class="password-reset">
    <p>Здравствуйте <?= Html::encode($user->name) ?>,</p>

    <p>Ваши данные для входа в   <?php
            Html::a('crm', $url);
        ?>
    </p>
    <p><strong>Логин:</strong> <?php echo $user->email;?> </p>
    <p><strong>Пароль:</strong>  <?php echo $password; ?></p>
</div>
