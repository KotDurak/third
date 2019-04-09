<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "chain_clones_steps".
 *
 * @property int $id
 * @property int $id_clone
 * @property int $id_step
 * @property int $status
 */
class ChainClonesSteps extends \yii\db\ActiveRecord
{
    const STATUS_NOT = 0;
    const STATUS_WORK = 1;
    const STATUS_REWORK = 2;
    const STATUS_DONE = 3;


    public  function changeStatus($status)
    {
        $this->status = $status;
        $this->save();
    }

    public static function getLabel($val)
    {
        switch ($val){
            case self::STATUS_WORK:
                return 'В работе';
                break;
            case  self::STATUS_REWORK:
                return 'На доработку';
                break;
            case  self::STATUS_DONE:
                return 'Сделано';
                break;
            default:
                return 'Не установлен';
                break;
        }
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chain_clones_steps';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_clone', 'id_step', 'status'], 'integer'],
        ];
    }


    public function getStep()
    {
        return $this->hasOne(Steps::className(), ['id' => 'id_step']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }

    public function getClone()
    {
        return $this->hasOne(ChainClones::className(), ['id' => 'id_clone']);
    }

    public function getAttributesValues()
    {
        return $this->hasMany(AttributesValues::className(), ['id_step_clone' => 'id']);
    }

    public function getComments()
    {
        return $this->hasMany(StepClonesComment::className(), ['id_step_clone' => 'id']);
    }

    public function getTask()
    {
        return $this->hasOne(Task::className(),['id' => 'id_task'])
            ->viaTable('chain_clones', ['id' => 'id_clone']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_clone' => 'Id Clone',
            'id_step' => 'Id Step',
            'status' => 'Status',
        ];
    }

    public static function getStepsByWorker($id_worker = null)
    {
        if(is_null($id_worker)){
            $id_worker = Yii::$app->user->id;
        }
        $clones_steps = ChainClonesSteps::find()->select('COUNT(id), status')->where(['id_user' => $id_worker])->groupBy('status')
            ->asArray()
            ->all();
        $statuses = [
            ChainClonesSteps::STATUS_NOT,
            ChainClonesSteps::STATUS_DONE,
            ChainClonesSteps::STATUS_REWORK,
            ChainClonesSteps::STATUS_WORK
        ];
        $groups = [];
        $groups['count'] = 0;
        foreach ($statuses as  $status){
            $groups[$status] = 0;
            foreach ($clones_steps as $step){
                if($step['status'] == $status){
                    $groups['count'] += $step['COUNT(id)'];
                    $groups[$status] = $step['COUNT(id)'];
                    break;
                }
            }

        }
        return $groups;
    }
}
