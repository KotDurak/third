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


    public  function changeStatus($status)
    {
        $this->status = $status;
        $this->save();
    }

    public static function getLabel($val)
    {
        switch ($val){
            case self::STATUS_WORK:
                return 'В работе';
                break;
            case  self::STATUS_REWORK:
                return 'На доработку';
                break;
            case  self::STATUS_DONE:
                return 'Сделано';
                break;
            default:
                return 'Не установлен';
                break;
        }
    }
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

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }

    public function getAttributesValues()
    {
        return $this->hasMany(AttributesValues::className(), ['id_step_clone' => 'id']);
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
