<?php
use app\models\User;
    $userArray = [];
    foreach ($users as $user){
        $userArray[] = [
            'id'    => $user['id'],
            'name'  => $user['name'] . ' ' . $user['surname']
        ];
    }
    $outer = \app\models\User::findOne(['is_outer' => 1]);
?>
<option style="color:blue" value="<?php echo User::CANCEL_TASK; ?>">Снять сотрудника с задачи</option>
<option  style="color:red;" value="<?php echo $outer->id; ?>"><?php echo $outer->name . ' ' . $outer->surname; ?></option>
<option style="color:red;" value="<?php echo Yii::$app->user->id; ?>">Мне</option>

<?php foreach ($userArray as $user): ?>
<option value="<?php echo $user['id']; ?>"><?php echo $user['name']; ?></option>
<?php endforeach; ?>