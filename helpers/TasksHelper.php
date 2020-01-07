<?php


namespace app\helpers;


use app\models\ChainClonesSteps;
use app\models\Task;
use yii\db\ActiveQuery;

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
}