<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 31.03.2019
 * Time: 20:22
 */

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Task;

class ChangeTask extends Model
{
    public $id_step;
    public $id_user;
    public $is_outer;
    public $is_self;
    public $is_clear;

    public function rules()
    {
        return [
            [['id_step', 'id_user', 'is_outer', 'is_self', 'is_clear'], 'integer']
        ];
    }

    public function doChange($tasks)
    {
        foreach ($tasks as $task){
            $clone = ChainClones::findOne(['id_task' => $task['id']]);
            $clone_step = $clone->getCloneSteps()->where(['id_step' => $this->id_step])->one();
            $clone_step->id_user = ($this->id_user != -1) ? $this->id_user : null;
            $clone_step->save(false);
        }
    }
}