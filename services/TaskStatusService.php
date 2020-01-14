<?php

namespace app\services;


use app\models\Chain;
use app\models\ChainClonesSteps;
use app\models\Steps;
use yii\db\ActiveQuery;

class TaskStatusService
{
    public function setStatusWork(ChainClonesSteps $clonesStep)
    {
        $this->setNextStep($clonesStep);
        $clonesStep->status = ChainClonesSteps::STATUS_WORK;
        return $clonesStep->save();
    }

    public function setStatusDone(ChainClonesSteps $clonesStep)
    {
        $this->setNextStep($clonesStep, ChainClonesSteps::STATUS_WORK);
        $clonesStep->status  = ChainClonesSteps::STATUS_DONE;
        return $clonesStep->save();
    }

    private function setNextStep(ChainClonesSteps $clonesStep, $status = null)
    {
        $step = $clonesStep->step;
        $sort = $step->sort;
        $chain = $this->getChain($step->id_chain);

        if (empty($chain->steps)) {
            return;
        }

        $nextStep = $this->getNextStep($chain->steps, $sort);
        if (!empty($nextStep)) {
            $nextCloneStep = ChainClonesSteps::find()
                ->where(['id_clone' => $clonesStep->id_clone, 'id_step' => $nextStep->id])
                ->limit(1)
                ->one();
            $nextCloneStep->status = $status;
            $nextCloneStep->save();
        }
    }

    private function getChain(int $idChain): Chain
    {
        return Chain::find()
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