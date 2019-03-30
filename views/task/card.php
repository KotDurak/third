<?php

use app\models\Task;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\ChainClonesSteps;

$this->params['breadcrumbs'][] = ['label' => 'Список задач', 'url' => ['/task/list?id_project=' . $task->id_project]];
$this->params['breadcrumbs'][] = 'Карточка задачи';

$this->registerCssFile('/css/task.css');

$this->registerJsFile('@web/js/task/card.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]);

$clone_chain = $task->getChainClones()->one();
$steps = $clone_chain->getSteps()->orderBy(['sort' => SORT_ASC])->all();

$i = 1;

$access_step = true;
$fake_buttons = $this->render('fake-buttons');
?>

<div class="row top-task-info">
    <div class="col-md-3">
        <h5>Проект</h5>
        <span><?php echo $task->project['name']; ?></span>
    </div>
    <div class="col-md-3">
        <h5>Имя задачи</h5>
        <span><?php echo $task->name; ?></span>
    </div>
    <div class="col-md-3">
        <h5>Дата создания задачи</h5>
        <span><?php echo date('d.m.Y', strtotime($task->created)); ?></span>
    </div>
    <div class="col-md-3">
        <h5>Срок сдачи задачи</h5>
        <span><?php echo date('d.m.Y H:i', strtotime($task->deadline)); ?></span>
    </div>
</div>
<div class="row">
    <div class="col-md-9">
        <?php foreach ($steps as $step): ?>
            <div class="row">
                <div class="col-md-12">
                    <h3>Этап <?php echo $i++ . ' ' . $step->name; ?> </h3>
                </div>
                <div class="col-md-6">
                    <?php
                        $clone_step = $step->getStepClones()->where(['id_clone' => $clone_chain['id']])->one();
                        $is_self =  ($clone_step->user->id == Yii::$app->user->id);

                        if((isset($next_access) && !$next_access) || $clone_step->status == ChainClonesSteps::STATUS_DONE){
                            $access_step = false;
                        } else{
                            $access_step = ($is_self );
                        }
                        $access_step = ($access_step ||  Yii::$app->user->identity->is_admin());
                        $next_access = ($clone_step->status == ChainClonesSteps::STATUS_DONE);
                        if ($step->type == 'table') {
                            $rows = Task::getRows($task['id']);
                        }

                    ?>
                    <?php if ($step->type == 'table'): ?>
                        <div class="table-container">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Фраза</th>
                                    <th>Базовая</th>
                                    <th>Частота ""</th>
                                    <th>Частота "!"</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($rows as $row): ?>
                                    <tr>
                                        <td><?php echo $row['phrase']; ?></td>
                                        <td><?php echo $row['base']; ?></td>
                                        <td><?php echo $row['frequence_e']; ?></td>
                                        <td><?php echo $row['frequence_f']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                       <?php
                            $attributes = $clone_step->getAttributesValues()->all();
                        ?>
                        <?php if(!empty($attributes)): ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Атрибут</th>
                                    <th>Значение</th>
                                    <th>Изменить</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($attributes as $value): ?>
                                    <tr>
                                        <td><?php echo $value->attribute0->name; ?></td>
                                        <td><?php echo $value->value; ?></td>
                                        <td>
                                            <a  class="attr-link" href="<?php echo Url::toRoute(['attributes-values/change', 'id' => $value['id']]) ?>">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <?php

                        $files = $step->getFiles()->asArray()->all();
                    ?>
                    <?php if(!empty($files)): ?>
                    <div class="files-block">
                        <h4>Файлы вводные</h4>
                        <ul class="files-list list-group">
                            <?php foreach ($files as $file): ?>
                                <?php
                                    $word = Url::to('@images/word.png');
                                    $url = Url::to(['file/download', 'id' => $file['id'],  ['data-pjax' => '0']]);
                                    $a = Html::a($file['real-name'], $url);
                                ?>
                                <li class="word-item list-group-item">
                                   <?php echo \yii\helpers\Html::img($word, ['width' => '20']) ?>
                                   <?php echo $a; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <div class="results-files">
                        <h4>Файлы результата
                            <?php echo Html::a('Загрузить' ,Url::toRoute(['task/upload', 'id_task' => $task->id, 'id_step' => $step->id]), [
                                'class' => 'upload-task'
                            ]);?>
                        </h4>
                        <?php
                            $result_files = $step->showTaskFiles($task->id);
                        ?>
                        <ul class="files-list list-group">
                            <?php foreach ($result_files as $file): ?>
                                <?php
                                $word = Url::to('@images/word.png');
                                $url = Url::to(['file/download', 'id' => $file['id'],  ['data-pjax' => '0']]);
                                $path = Yii::getAlias('@webroot') . '/uploads/files/' . $file['tmp'];
                                $type = FileHelper::getMimeType($path);
                                $a = Html::a($file['real-name'], $url);
                                ?>
                                <li class="word-item list-group-item">
                                 <!--   <?php echo \yii\helpers\Html::img($word, ['width' => '20']) ?> -->
                                    <?php echo $a; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php if ($step == end($steps) && (Yii::$app->user->identity->is_admin())): ?>
                        <?php if(!empty($comments) && $clone_step->status == \app\models\ChainClonesSteps::STATUS_REWORK): ?>
                            <a href="<?php echo Url::toRoute(['task/step-comments', 'id_clone' => $clone_step->id]); ?>" class="notice-comment">
                                <?php
                                $src = Url::to('@images/notice.png');
                                echo \yii\helpers\Html::img($src);
                                ?>
                            </a>
                        <?php endif; ?>

                       <div class="btn-group steps-btn">
                           <a type="button"  class="btn btn-default" href="<?php echo Url::toRoute(['task/archive', 'id' => $task->id]); ?>">Архивировать</a>
                           <a href="<?php echo Url::toRoute(['/task/rework', 'id_clone' => $clone_step->id, 'id_task' => $_GET['id']]) ?>"
                              type="button" class="btn btn-danger  rework" modal-url="<?php echo Url::toRoute(['/task/comment', 'id_clone' => $clone_step->id]); ?>">На доработку
                           </a>
                           <a type="button" href="<?php echo Url::toRoute(['/task/working', 'id_clone' => $clone_step->id, 'id_task' => $task->id]); ?>" class="btn btn-warning work">В работе</a>
                           <a href="<?php echo Url::toRoute(['/task/complete', 'id' => $task->id]) ?>"
                              type="button" class="btn btn-success done">Принять</a>

                       </div>
                    <?php else: ?>
                        <?php
                            $comments = $clone_step->getComments()->all();
                        ?>
                        <?php if(!empty($comments) && $clone_step->status == \app\models\ChainClonesSteps::STATUS_REWORK): ?>
                            <a href="<?php echo Url::toRoute(['task/step-comments', 'id_clone' => $clone_step->id]); ?>" class="notice-comment">
                                <?php
                                    $src = Url::to('@images/notice.png');
                                    echo \yii\helpers\Html::img($src);
                                ?>
                            </a>
                        <?php endif; ?>
                        <div class="btn-group steps-btn">
                            <?php if(!$access_step): ?>
                                <?php echo $fake_buttons; ?>
                            <?php else: ?>
                                <?php if(Yii::$app->user->identity->is_admin()): ?>
                                 <a href="<?php echo Url::toRoute(['/task/rework', 'id_clone' => $clone_step->id, 'id_task' => $_GET['id']]) ?>"
                                   type="button" class="btn btn-danger  rework" modal-url="<?php echo Url::toRoute(['/task/comment', 'id_clone' => $clone_step->id]); ?>">На доработку
                                </a>
                                <?php endif; ?>
                                <a type="button" href="<?php echo Url::toRoute(['/task/working', 'id_clone' => $clone_step->id, 'id_task' => $task->id]); ?>" class="btn btn-warning work">В работе</a>
                                <a href="<?php echo Url::toRoute(['/task/done', 'id_clone' => $clone_step->id, 'id_task' => $_GET['id']]) ?>"
                                   type="button" class="btn btn-success done">Сделано</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="col-md-3">
       <h4>Цепочка этапов <?php echo $task->getChains()->one()->name; ?></h4>
        <?php
            $step_clones = $task->getCloneSteps();


        ?>
        <ol>
            <?php foreach ($step_clones as $step_clone): ?>
                <?php
                    $status = $step_clone->status;
                    $class = 'status-'.$status;
                ?>
                <li>
                    <?php echo  $step_clone->step->name; ?>
                    <span class="status-step <?php echo $class; ?>"></span>
                </li>
            <?php endforeach; ?>
        </ol>
        <h4>Кому назначено</h4>
        <ol>
            <?php foreach($step_clones as $step_clone): ?>
                <?php
                    $user = $step_clone->user;
                    $group = $step_clone->step->group->name;
                    $user_name = $user->surname . ' ' . $user->name;
                ?>
                <li>
                    <strong><?php echo $group; ?></strong><br>
                    <span class="user-step-info"><?php echo $user_name; ?></span>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</div>


<div class="modal inmodal add-comment" id="add-comment" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg"></div>
</div>

<div class="modal inmodal change-attr" id="change-attr" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg"></div>
</div>

<div class="modal inmodal upload" id="upload" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg"></div>
</div>

<div class="modal inmodal comment" id="comment" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg"></div>
</div>