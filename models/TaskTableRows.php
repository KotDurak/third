<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task_table_rows".
 *
 * @property int $id
 * @property int $id_table
 * @property int $base
 * @property string $phrase
 * @property int $frequence_e
 * @property int $frequence_f
 *
 * @property TaskTable $table
 */
class TaskTableRows extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_table_rows';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_table', 'base', 'frequence_e', 'frequence_f'], 'integer'],
            [['phrase'], 'string', 'max' => 255],
            [['id_table'], 'exist', 'skipOnError' => true, 'targetClass' => TaskTable::className(), 'targetAttribute' => ['id_table' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_table' => 'Id Table',
            'base' => 'Base',
            'phrase' => 'Phrase',
            'frequence_e' => 'Frequence E',
            'frequence_f' => 'Frequence F',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTable()
    {
        return $this->hasOne(TaskTable::className(), ['id' => 'id_table']);
    }
}
