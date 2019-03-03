<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task_table".
 *
 * @property int $id
 * @property int $id_task
 *
 * @property Task $task
 * @property TaskTableRows[] $taskTableRows
 */
class TaskTable extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_table';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_task'], 'integer'],
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
            'id_task' => 'Id Task',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'id_task']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskTableRows()
    {
        return $this->hasMany(TaskTableRows::className(), ['id_table' => 'id']);
    }
}
