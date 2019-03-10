<?php

namespace app\models;

use Yii;

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
    const STATUS_WORK = 1;
    const STATUS_REWORK = 2;
    const STATUS_DONE = 3;
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
            [['id_clone', 'id_step', 'status'], 'integer'],
        ];
    }


    public function getStep()
    {
        return $this->hasOne(Steps::className(), ['id' => 'id_step']);
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
}
