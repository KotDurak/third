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
 * @property int $is_archive
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
            [['status', 'id_user', 'id_manager', 'is_archive'], 'integer'],
            [['created'], 'required'],
            [['timestamp'], 'safe'],
            [['id_manager'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_manager' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
            [['id_project'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['id_project' => 'id']]
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
            'is_archive'    => 'Is Archive'
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

    public function getChain()
    {
        return $this->hasOne(Chain::className(), ['id' => 'id_chain'])
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
       $rows = array();
       $table = TaskTable::findOne(['id_task' => $id]);
        if(!empty($table)){
            $rows = $table->getTaskTableRows()->asArray()->all();
        }
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

   public static function createTask(Task $task, $post, TaskEdit $modelEdit = NULL, $id_project)
   {
       $task->created = date('Y-m-d H:i:s', strtotime($task->created));
       $task->deadline = date('Y-m-d H:i:s', strtotime($post['Task']['deadline']));
       $task->id_project = $id_project;
       $task->status = Task::STATUS_NOT;
       $task->save();
       $id = $task->id;
       $table = new TaskTable();
       $table->id_task = $task->id;
       $table->save();
       $modelEdit->load($post);
       $clone = new ChainClones();
       $clone->id_task = $id;
       $clone->id_chain = $modelEdit->id_chain;
       $clone->save();
       foreach ($post['SelectUserStep'] as $i => $step) {
           $post['SelectUserStep'][$i]['status'] = $post['ChainClonesSteps'][$i]['status'];

       }
       $is_rework = false;
       foreach ($post['SelectUserStep'] as $item) {
           $clone_step = new ChainClonesSteps();
           $clone_step->id_clone = $clone->id;
           $clone_step->id_step = $item['id_step'];

           $clone_step->id_user = $item['id_user'];
           $clone_step->status = (!empty($item['status'])) ? $item['status'] : ChainClonesSteps::STATUS_WORK;

           if ($item['status'] == 2) {
               $is_rework = true;
           }
           $clone_step->save();
           $step = Steps::findOne($item['id_step']);;
           $attributes =  $step->getStepAttributes()->all();
           self::setStepAttributes($attributes, $clone_step);

       }
       if ($is_rework) {
           $task->status = 2;
       }
       return $task;
   }

   public static function setStepAttributes($attributes, $clone_step)
   {
       foreach ($attributes as $attr){
           $attr_val = new AttributesValues();
           $attr_val->id_attribute = $attr->id;
           $attr_val->id_step_clone = $clone_step->id;
           $attr_val->value = $attr->def_value;
           $attr_val->save(false);
       }
   }

   public static function FilterByFirst($tasks)
   {
       if(empty($tasks)){
           return NULL;
       }
       $task = Task::findOne($tasks[0]);
       $chain = $task->getChains()->one();
       $filters = $chain->getTasks()->where(['in', 'id', $tasks])->asArray()->all();
       return [
           'id_chain'   => $chain->id,
           'tasks'      => $filters
       ];
   }

    /**
     * Создаем копии задач
     *
     * @param $tasks массив идентификаторов задач
     */
    public static function copyTasks($tasks)
   {
       foreach ($tasks as $task){
           $old_task = Task::findOne($task);
           $name = $old_task->name;
           $new_task = new Task();
           $atributes = $old_task->attributes;
           unset($atributes['id']);
           $i = 2;
           while (!empty($current = Task::find()->where(['name' => $atributes['name'] .'(' . $i . ')'])->one())){
                $i++;
           }
           $atributes['name'] .= '(' . $i . ')';
           $chain = $old_task->chain;
           $clone_steps = $old_task->getCloneSteps();
           $new_task->setAttributes($atributes);
           $new_task->deadline = $old_task->deadline;
           $new_task->save(false);
           $chain_clones = new ChainClones();
           $chain_clones->id_chain = $chain->id;
           $chain_clones->id_task = $new_task->id;
           $chain_clones->save();
           foreach ($clone_steps as $clone_step){
               $model = new ChainClonesSteps();
               $model->id_step = $clone_step->id_step;
               $model->id_user = $clone_step->id_user;
               $model->status = $clone_step->status;
               $model->id_clone = $chain_clones->id;
               $model->save(false);
               $attributes_vals = $clone_step->getAttributesValues()->all();
               foreach ($attributes_vals as $attributes_val){
                   $model_attr = new AttributesValues();
                   $model_attr->id_attribute = $attributes_val->id_attribute;
                   $model_attr->id_step_clone = $model->id;
                   $model_attr->value = $attributes_val->value;
                   $model_attr->save();
               }
           }
       }
        return true;
   }

   public static function setStatusAccept($tasks)
   {
       $models = Task::find()->where(['in', 'id', $tasks])->all();
       foreach ($models as $model){
            $steps = $model->getCloneSteps();
            foreach ($steps as $step){
                $step->status = ChainClonesSteps::STATUS_DONE;
                $step->save();
            }
            $model->status = Task::STATUS_DONE;
            $model->save();
       }
   }

}
