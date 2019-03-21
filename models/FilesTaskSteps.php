<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "files_task_steps".
 *
 * @property int $id
 * @property int $id_file
 * @property int $id_step
 * @property int $id_task
 *
 * @property Files $file
 * @property Steps $step
 * @property Task $task
 */
class FilesTaskSteps extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files_task_steps';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_file', 'id_step', 'id_task'], 'integer'],
            [['id_file'], 'exist', 'skipOnError' => true, 'targetClass' => Files::className(), 'targetAttribute' => ['id_file' => 'id']],
            [['id_step'], 'exist', 'skipOnError' => true, 'targetClass' => Steps::className(), 'targetAttribute' => ['id_step' => 'id']],
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
            'id_file' => 'Id File',
            'id_step' => 'Id Step',
            'id_task' => 'Id Task',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(Files::className(), ['id' => 'id_file']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStep()
    {
        return $this->hasOne(Steps::className(), ['id' => 'id_step']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'id_task']);
    }
}
