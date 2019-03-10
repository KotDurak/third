<?php
    use yii\helpers\Html;
?>

<?php foreach ($modelsClonesSteps as $i => $modelsClonesStep): ?>
    <div class="col-md-12 step-item">
    <h4 class="text-left"><?php echo  $modelsClonesStep->step->name;?></h4>
    <br>
    <?php
    echo   $form->field($modelsClonesStep, "[{$i}]status")->radioList(array(2 => 2, 1 => 1,3 => 3), [
        'class' => 'btn-group radio-colors',
        'data-toggle' => 'buttons',
        'unselect' => null,
        'item'  => function($index,$label,$name,$checked,$value){
            switch ($value){
                case 1:{
                    $class = 'btn-warning';
                    break;
                }
                case  2:{
                    $class= 'btn-danger';
                    break;
                }
                case 3:{
                    $class = 'btn-success';
                    break;
                }
                default:
                    $class  = '';
                    break;
            }
            return Html::radio($name,
                $checked,
                [
                    'label' => \app\models\ChainClonesSteps::getLabel($label),
                    'value' => $value,
                    'labelOptions' => ['class' => 'btn  circle-conttrols ' . $class]
                ]);
        }
    ])->label(false);
?>

    </div>
<?php  endforeach; ?>