<?php

/* @var $this yii\web\View */

$this->title = 'TD CRM';
?>
<?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->is_admin()): ?>
<div class="row">
    <?php echo $this->render('admin-summary'); ?>
</div>
<?php else: ?>
   <?php echo  $this->render('workers'); ?>
<?php endif; ?>
