<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $status
 * @property int $id_user
 * @property int $id_manager
 *
 * @property User $manager
 * @property User $user
 */
class Task extends \yii\db\ActiveRecord
{
    const STATUS_WORK = 1;
    const STATUS_DONE = 2;

    public function behaviors()
    {
       return [
           [
               'class' => AttributeBehavior::className(),
               'attributes' => [
                   ActiveRecord::EVENT_BEFORE_INSERT => 'created',
               ],
               'value' => function ($event) {
                   return date('Y-m-d H:i:s');
               },
           ],
       ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'string'],
            [['status', 'id_user', 'id_manager'], 'integer'],
            [['created'], 'required'],
            [['id_manager'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_manager' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'status' => 'Status',
            'id_user' => 'Id User',
            'id_manager' => 'Id Manager',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManager()
    {
        return $this->hasOne(User::className(), ['id' => 'id_manager']);
    }

    public function getTables()
    {
        return $this->hasMany(TaskTable::className(), ['id_task' => 'id']);
    }

    public function getChainClones()
    {
        return $this->hasMany(ChainClones::className(), ['id_task' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }

    public function getChains()
    {
        return $this->hasMany(Chain::className(), ['id' => 'id_chain'])
            ->viaTable('chain_clones', ['id_task' => 'id']);
    }

    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'id_project']);
    }

    public function getCloneSteps()
    {
        return $this->getChainClones()->one()-> getCloneSteps()->all();
    }

    public static function getStrStatus($status){
        switch ($status){
            case 0:
                return 'Не настроен';
                break;
            case 1:
                return 'В работе';
                break;
            case 2:
                return  'На доработке';
                break;
            case 3:
                return 'Принятые';
                break;
            case 4:
                return 'В архиве';
                break;
            default:
                return 'Не настроен';
                break;
        }
    }

   public static function getRows($id)
   {
       $table = TaskTable::findOne(['id_task' => $id]);
       $rows = $table->getTaskTableRows()->asArray()->all();
       return $rows;
   }

   public function archive()
   {
       $this->is_archive = 1;
       $this->save();
   }

   public function complete()
   {
       $this->status = self::STATUS_DONE;
       $clone_steps = $this->getCloneSteps();
       foreach ($clone_steps as $clone_step){
          $clone_step->status = ChainClonesSteps::STATUS_DONE;
          $clone_step->save();
       }
       $this->save();
   }

   public function setWorkStatus()
   {
       if($this->status != self::STATUS_WORK){
           $this->status = self::STATUS_WORK;
           $this->save();
       }
   }

}
