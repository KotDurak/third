<?php

namespace app\models;

use Matrix\Exception;
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
    const STATUS_NOT = 0;
    const STATUS_WORK = 1;
    const STATUS_DONE = 2;
    const STATUS_ARCHIVE = 3;
    const STATUS_REWORK = 4;

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

    public function getReworkSteps()
    {
        return $this->getChainClones()->one()->getCloneSteps()->where(['status' => ChainClonesSteps::STATUS_REWORK])->all();
    }

    public function getFiles()
    {
        return $this->hasMany(Files::className(), ['id' => 'id_file'])->
            viaTable('files_task_steps', ['id_task' => 'id']);
    }

    public function getArchive()
    {
        return $this->hasOne(Files::className(), ['id' => 'id_archive']);
    }


    public static function getStrStatus($status){
        $statuses  = [Task::STATUS_NOT => 'Не настроен',
                    Task::STATUS_WORK => 'В работе',
                    Task::STATUS_REWORK => 'На доработке',
                    Task::STATUS_DONE => 'Принятные',
                    Task::STATUS_ARCHIVE => 'В архиве'
        ];
        return $statuses[$status];
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
       $this->status = Task::STATUS_ARCHIVE;
       $this->save();
   }

   public function complete()
   {
       $this->status = self::STATUS_DONE;
       $clone_steps = $this->getCloneSteps();
       $files = $this->getFiles()->all();
       $zip = new \ZipArchive();
       if(!empty($files)){
           $zip_name = Yii::getAlias('@webroot'). '/uploads/files/' . uniqid('z').'.zip';
           $model_zip = new Files();
           $model_zip->name = basename($zip_name);
           $model_zip->tmp = basename($zip_name);
           $model_zip['real-name'] = 'Архив по задаче ' . $this->name;
           $model_zip->save();
           $model_zip->link('tasks', $this);

           if($zip->open($zip_name,\ZipArchive::CREATE) !== false){
               foreach ($files as $file){
                   $path = Yii::getAlias('@webroot'). '/uploads/files/' . $file['tmp'];
                   if(file_exists($path)){
                       $zip->addFile($path, $file['real-name'].'.'.pathinfo($path, PATHINFO_EXTENSION));
                   }
               }
               $zip->close();

           }

           foreach ($files as $file){
               $file->delete();
           }
       }

       foreach ($clone_steps as $clone_step){
          $clone_step->status = ChainClonesSteps::STATUS_DONE;
          $clone_step->save();

       }
       $this->save();
       return isset($zip_name) ? $zip_name : false;
   }

   public function setWorkStatus()
   {
       $rework_steps = $this->getReworkSteps();


       if($this->status != self::STATUS_WORK && empty($rework_steps)){
           $this->status = self::STATUS_WORK;
           $this->save();
       }
   }

   public function setRework()
   {
       $this->status = self::STATUS_REWORK;
       $this->save();
   }

   public function getLastDoneStep()
   {
        $clone = $this->getChainClones()->one();
        $GLOBALS['clone'] = $clone;
        $chain = Chain::findOne($clone->id_chain);
        $steps = $chain->getSteps()->joinWith([
            'stepClones' => function($query){
                $query->onCondition(['chain_clones_steps.id_clone' => $GLOBALS['clone']['id']]);
                $query->andWhere(['status' => ChainClonesSteps::STATUS_DONE]);
            },
        ])->orderBy(['sort' => SORT_DESC])->asArray()->one();
        unset($GLOBALS['clone']);

        return (!empty($steps)) ? $steps['name'] : false;
   }

}
