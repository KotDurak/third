<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property string $tmp
 * @property string $real-name
 * @property string $name
 *
 * @property StepFiles[] $stepFiles
 */
class Files extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tmp', 'real-name', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tmp' => 'Tmp',
            'real-name' => 'Real Name',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStepFiles()
    {
        return $this->hasMany(StepFiles::className(), ['id_file' => 'id']);
    }

    public function deleteFile()
    {
        $path = Yii::getAlias('@webroot'). '/uploads/files/' . $this->tmp;
        if(file_exists($path)){
            unlink($path);
        }
    }

    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['id_archive' => 'id']);
    }

    public function beforeDelete()
    {
        $path = Yii::getAlias('@webroot'). '/uploads/files/' . $this->tmp;
        if(file_exists($path)){
            unlink($path);
        }
        return parent::beforeDelete();
    }
}
