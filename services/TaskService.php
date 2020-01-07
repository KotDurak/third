<?php


namespace app\services;


use app\models\Task;

class TaskService
{
    public function setWorkStatus(Task $task)
    {
        $task->status = Task::STATUS_WORK;
        return $task->save();
    }
}