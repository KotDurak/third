<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.01.2020
 * Time: 21:57
 */

namespace app\services;


use app\models\Chain;
use app\models\ChainClonesSteps;
use app\models\Steps;

class TaskStatusService
{
    public function setStatusWork(ChainClonesSteps $clonesStep)
    {
        $step = $clonesStep->step;
        $sort = $step->sort;
        $chain = $this->getChain($step->id_chain);
        if (empty($chain->steps)) {
            return;
        }
        $nextStep = $this->getNextStep($chain->steps, $sort);

        if (empty($nextStep)) {
            return;
        }

        $nextCloneStep = ChainClonesSteps::find()
            ->where(['id_clone' => $clonesStep->id_clone, 'id_step' => $nextStep->id])
            ->limit(1)
            ->one();

        $clonesStep->status = ChainClonesSteps::STATUS_WORK;
        $clonesStep->save();
        $nextCloneStep->status = null;

        return $nextCloneStep->save();
    }

    private function getChain(int $idChain): Chain
    {
        return Chain::find()
            ->with(['steps'])
            ->limit(1)
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