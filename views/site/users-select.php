<?php
use yii\helpers\Html;
?>

<label for="">Сотрудник</label>
<?php echo Html::dropDownList('users', 's', $users, [
'id'    => 'select-user',
'class' => 'form-control select-user'
]); ?>