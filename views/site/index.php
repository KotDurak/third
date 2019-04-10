<?php

/* @var $this yii\web\View */

$this->title = 'TD CRM';
?>
<?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->is_admin()): ?>
<div class="row">
</div>
<?php else: ?>
   <?php echo  $this->render('workers'); ?>
<?php endif; ?>
