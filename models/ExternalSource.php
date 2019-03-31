<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "external_source".
 *
 * @property int $id
 * @property int $id_step
 * @property int $id_task
 * @property string $src
 *
 * @property Steps $step
 * @property Task $task
 */
class ExternalSource extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'external_source';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_step', 'id_task'], 'integer'],
            [['src'], 'string', 'max' => 255],
            [['src'], 'required'],
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
            'id_step' => 'Id Step',
            'id_task' => 'Id Task',
            'src' => 'Ссылка на внешний источник',
        ];
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
