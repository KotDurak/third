<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "chain_clones".
 *
 * @property int $id
 * @property int $id_chain
 * @property int $id_task
 *
 * @property Chain $chain
 * @property Task $task
 */
class ChainClones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chain_clones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_chain', 'id_task'], 'integer'],
            [['id_chain'], 'exist', 'skipOnError' => true, 'targetClass' => Chain::className(), 'targetAttribute' => ['id_chain' => 'id']],
            [['id_task'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['id_task' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_chain' => 'Id Chain',
            'id_task' => 'Id Task',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChain()
    {
        return $this->hasOne(Chain::className(), ['id' => 'id_chain']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'id_task']);
    }

    public function getCloneSteps()
    {
        return $this->hasMany(ChainClonesSteps::className(), ['id_clone' => 'id']);
    }

    /**
     * Удаляем старый клон задачи;
     *
     * @param $id
     */
    public static function deleteByTaskId($id)
    {
        $old_clone = ChainClones::findOne(['id_task' => $id]);
        $old_clone->delete();
    }
}
