<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "files_clones_steps".
 *
 * @property int $id
 * @property int $id_file
 * @property int $id_clone_step
 *
 * @property Files $file
 * @property ChainClonesSteps $cloneStep
 */
class FilesClonesSteps extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files_clones_steps';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_file', 'id_clone_step'], 'integer'],
            [['id_file'], 'exist', 'skipOnError' => true, 'targetClass' => Files::className(), 'targetAttribute' => ['id_file' => 'id']],
            [['id_clone_step'], 'exist', 'skipOnError' => true, 'targetClass' => ChainClonesSteps::className(), 'targetAttribute' => ['id_clone_step' => 'id']],
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
            'id_clone_step' => 'Id Clone Step',
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
    public function getCloneStep()
    {
        return $this->hasOne(ChainClonesSteps::className(), ['id' => 'id_clone_step']);
    }
}
