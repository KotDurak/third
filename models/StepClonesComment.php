<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "step_clones_comment".
 *
 * @property int $id
 * @property int $id_step_clone
 * @property int $id_user
 * @property string $comment
 * @property string $timestamp
 *
 * @property User $user
 * @property ChainClonesSteps $stepClone
 */
class StepClonesComment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'step_clones_comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_step_clone', 'id_user'], 'integer'],
            [['comment'], 'string'],
            [['timestamp'], 'safe'],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
            [['id_step_clone'], 'exist', 'skipOnError' => true, 'targetClass' => ChainClonesSteps::className(), 'targetAttribute' => ['id_step_clone' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_step_clone' => 'Id Step Clone',
            'id_user' => 'Id User',
            'comment' => 'Комментарий',
            'timestamp' => 'Timestamp',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStepClone()
    {
        return $this->hasOne(ChainClonesSteps::className(), ['id' => 'id_step_clone']);
    }
}
