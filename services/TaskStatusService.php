<?php

namespace app\services;


use app\models\Chain;
use app\models\ChainClonesSteps;
use app\models\Steps;
use app\models\Task;
use yii\db\ActiveQuery;

class TaskStatusService
{
    public function setStatusWork(ChainClonesSteps $clonesStep, Task $task)
    {
        $this->setNextStep($clonesStep);
        $clonesStep->status = ChainClonesSteps::STATUS_WORK;
        $task->stage = $clonesStep->step->name;
        return $clonesStep->save();
    }

    public function setStatusDone(ChainClonesSteps $clonesStep, Task $task)
    {
        $step = $this->setNextStep($clonesStep, ChainClonesSteps::STATUS_WORK);
        $task->stage = !empty($step) ? $step->name : Task::STAGE_DONE;
        $clonesStep->status  = ChainClonesSteps::STATUS_DONE;
        return $clonesStep->save();
    }

    private function setNextStep(ChainClonesSteps $clonesStep, $status = null)
    {
        $step = $clonesStep->step;
        $sort = $step->sort;
        $chain = $this->getChain($step->id_chain);

        if (empty($chain->steps)) {
            return null;
        }

        $nextStep = $this->getNextStep($chain->steps, $sort);
        if (!empty($nextStep)) {
            $nextCloneStep = ChainClonesSteps::find()
                ->where(['id_clone' => $clonesStep->id_clone, 'id_step' => $nextStep->id])
                ->limit(1)
                ->one();
            if (empty($nextCloneStep)) {
                $nextCloneStep = new ChainClonesSteps(
                    ['id_clone' => $clonesStep->id_clone, 'id_step' => $nextStep->id]
                );
            }
            $nextCloneStep->status = $status;
            $nextCloneStep->save();
            return $nextStep;
        }

        return null;
    }

    private function getChain(int $idChain): Chain
    {
        return Chain::find()
            ->where(['id' => $idChain])
            ->with([
                'steps' => function (ActiveQuery $query) {
                    $query->orderBy(['sort' => SORT_ASC]);
                }
            ])->limit(1)
            ->one();
    }

    /**
     * @param $steps Steps[]
    */
    private function getNextStep(array $steps, int $sort)
    {
        foreach ($steps as $step) {
            if ($step->sort > $sort) {
                return $step;
            }
        }

        return null;
    }
}