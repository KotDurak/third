<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "steps".
 *
 * @property int $id
 * @property int $id_chain
 * @property string $name
 * @property string $type
 *
 * @property Chain $chain
 */
class Steps extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'steps';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_chain', 'sort'], 'integer'],
            [['type'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['id_chain'], 'exist', 'skipOnError' => true, 'targetClass' => Chain::className(), 'targetAttribute' => ['id_chain' => 'id']],
            [['id_group'], 'exist', 'skipOnError' => true, 'targetClass' => Groups::className(), 'targetAttribute' => ['id_group' => 'id']]
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
            'name' => 'Name',
            'type' => 'Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChain()
    {
        return $this->hasOne(Chain::className(), ['id' => 'id_chain']);
    }

    public function getStepAttributes()
    {
        return $this->hasMany(StepAttributes::className(), ['id_step' => 'id']);
    }

    public function getStepClones()
    {
        return $this->hasMany(ChainClonesSteps::className(), ['id_step' => 'id']);
    }

    public function getFiles()
    {
        return $this->hasMany(Files::className(), ['id' => 'id_file'])
            ->viaTable('step_files', ['id_file' => 'id']);
    }
}
