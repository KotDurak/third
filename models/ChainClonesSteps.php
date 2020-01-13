<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

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
        $step = Steps::findOne($this->id_step);
        $GLOBALS['sort'] = $step->sort;
        $result = ChainClonesSteps::find()->where(['chain_clones_steps.id' => $this->id])
            ->joinWith([
               'clone'  => function($query){
                    $query->joinWith([
                        'chain' => function($q){
                            $q->joinWith([
                                'steps' => function($q2){
                                        $q2->andWhere(['>', 'sort', $GLOBALS['sort']]);
                                        $q2->orderBy(['sort' => SORT_ASC]);
                                        $q2->limit(1);
                                }
                            ]);

                        }
                    ]);
               }
            ])->asArray()->one();

        if(!empty($result)){
            $step = $result['clone']['chain']['steps'];
            $step = array_shift($step);
            $clone_step = ChainClonesSteps::find()->where(['id_step' => $step['id']])
            ->andWhere(['id_clone' => $this->id_clone])->one();
            $clone_step->status = ChainClonesSteps::STATUS_WORK;
            $clone_step->save();
        }
        $this->status = $status;
        $this->save();
    }

    public function resetStatuses()
    {
        $step = Steps::findOne($this->id_step);
        $steps = Steps::find()->where(['id_chain' => $step->id_chain])
            ->andWhere(['>', 'sort', $step->sort])->asArray()->all();
        $ids = [];
        foreach ($steps as $step){
            $ids[] = $step['id'];
        }

        $clones = ChainClonesSteps::find()
            ->where(['id_clone' => $this->id_clone])
            ->andWhere(['in','id_step', $ids])->all();

        foreach ($clones as $clone){
            $clone->status = ChainClonesSteps::STATUS_NOT;
            $clone->save();
        }
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
            [['id_clone', 'id_step'], 'integer'],
            [['status'], 'default', 'value' => 0]
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

    public static function getStepsByProject($id_project)
    {
        $id_worker = Yii::$app->user->id;
        $tasks = Task::find()->where(['id_project' => $id_project])->joinWith('chainClones')
            ->asArray()
            ->all();
        $clones = ArrayHelper::getColumn($tasks,'chainClones');
        $ids = [];
        foreach ($clones as $clone){
            $ids[] = $clone[0]['id'];
        }

        $clones_steps = ChainClonesSteps::find()->select('COUNT(id), status')
            ->where(['id_user' => $id_worker])
           ->andWhere(['in', 'id_clone', $ids])
            ->groupBy('status')
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
        $groups['id_project'] = $id_project;
        return $groups;
    }

    public static function getStepsByDates($from, $to)
    {
        $id_worker = Yii::$app->user->id;
        $query = Task::find();
        if(!is_null($from)){
            $query->andWhere(['>=', 'deadline', $from]);
        }
        if(!is_null($to)){
            $query->andWhere(['<=', 'deadline', $to]);
        }
        $query->joinWith('chainClones');
        $tasks = $query->asArray()->all();
        $clones = ArrayHelper::getColumn($tasks,'chainClones');
        $ids = [];
        foreach ($clones as $clone){
            $ids[] = $clone[0]['id'];
        }

        $clones_steps = ChainClonesSteps::find()->select('COUNT(id), status')
            ->where(['id_user' => $id_worker])
            ->andWhere(['in', 'id_clone', $ids])
            ->groupBy('status')
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
        $groups['from'] = $from;
        $groups['to'] = $to;
        return $groups;
    }

    public static function getDisabled(ChainClonesSteps $step, $status)
    {

        return ($step->status == $status) ? 'disabled' : '';
    }
}
