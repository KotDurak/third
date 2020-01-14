<?php


namespace app\helpers;


use app\models\ChainClonesSteps;
use app\models\Steps;
use app\models\Task;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class TasksHelper
{
    public static function getTaskStatusByUser(int $id_user, array $tasks)
    {
        $data = [];
        $statuses = Task::find()
            ->where(['in','task.id', $tasks])
            ->with([
                'chainClones.cloneSteps' => function (ActiveQuery $query) use ($id_user) {
                    $query->where(['id_user' => $id_user]);
                }
            ])->all();

        foreach ($statuses as $status) {
            $cloneStatus = array_shift($status['chainClones']);
            if (empty($cloneStatus)) {
                continue;
            }

            $cloneSteps = array_shift($cloneStatus['cloneSteps']);
            if(empty($cloneSteps)) {
                continue;
            }

           $data[$status['id']] = ChainClonesSteps::getLabel($cloneSteps['status']);

        }

        return $data;
    }

    public static function getTaskStages()
    {
        $steps = Steps::find()->select('name')->asArray()->indexBy('name')->all();
        $steps = ArrayHelper::map($steps, 'name', 'name');
        $stages = [];
        $stages[Task::STAGE_DONE] = Task::STAGE_DONE;
        return array_merge($stages, $steps);
    }
}