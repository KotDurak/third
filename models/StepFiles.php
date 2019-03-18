<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "step_files".
 *
 * @property int $id
 * @property int $id_file
 * @property int $id_step
 *
 * @property Files $file
 * @property Steps $step
 */
class StepFiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'step_files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_file', 'id_step'], 'integer'],
            [['id_file'], 'exist', 'skipOnError' => true, 'targetClass' => Files::className(), 'targetAttribute' => ['id_file' => 'id']],
            [['id_step'], 'exist', 'skipOnError' => true, 'targetClass' => Steps::className(), 'targetAttribute' => ['id_step' => 'id']],
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
}
