<?php

namespace app\models;

use Codeception\Lib\Generator\Group;
use Yii;
use yii\web\IdentityInterface;
use app\models\Groups;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $password
 * @property string $email
 * @property string $is_root
 * @property string $birthday
 * @property int $status
 * @property string $date_create
 * @property string $email_confirm_token
 *
 * @property Task[] $tasks
 * @property Task[] $tasks0
 * @property UserGroups[] $userGroups
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_WAIT = 5;
    const STATUS_DELETED = 2;
    const STATUS_ACTIVE = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'surname'], 'required'],
            [['name', 'surname', 'password', 'email', 'is_root'], 'string'],
            [['birthday', 'date_create'], 'safe'],
            [['status'], 'integer'],
            [['email_confirm_token'], 'string', 'max' => 255],
            [['email_confirm_token', 'email'], 'unique'],
            ['status', 'in', 'range' => [self::STATUS_DELETED, self::STATUS_WAIT, self::STATUS_ACTIVE]]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'password' => 'Password',
            'email' => 'Email',
            'is_root' => 'Root права',
            'birthday' => 'Дата рождения',
            'status' => 'Статус',
            'date_create' => 'Дата регистрации',
            'email_confirm_token' => 'Email Confirm Token',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['id_manager' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks0()
    {
        return $this->hasMany(Task::className(), ['id_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserGroups()
    {
        return $this->hasMany(UserGroups::className(), ['id_user' => 'id']);
    }

    public function getGroups()
    {
        return $this->hasMany(Groups::className(),['id' => 'id_group'])
            ->viaTable('user_groups', ['id_user' => 'id']);
    }


    public static function findIdentity($id)
    {
        return User::findOne($id);
    }


    public static function findIdentityByAccessToken($token, $type = null)
    {

    }


    public function getId()
    {
        return $this->id;
    }


    public function getAuthKey()
    {
        return $this->auth_key;
    }


    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    public static function findUserByEmail($email)
    {
        return self::findOne(['email' => $email]);
    }

    /***
     * - D- Сохраняем группы пользователей;
     */
    public function saveGroups($groups)
    {
        if(is_array($groups)){
            UserGroups::deleteAll(['id_user' => $this->id]);
            foreach ($groups as $id_group){
                $group = Groups::findOne($id_group);
                $this->link('groups', $group);
            }
        }
    }

    public function setPassword($password)
    {
        $this->password = Yii::$app->getSecurity()->generatePasswordHash($password);
        $this->save();
    }

    public function beforeSave($insert)
    {
       if(parent::beforeSave($insert)){
           if($this->isNewRecord){
               $this->auth_key = \Yii::$app->security->generateRandomString();
           }
           return true;
       }
       return false;
    }

    public static function getUserName($id)
    {
       $user = User::findOne($id);
       if($user->is_outer == 1){
           return  '<strong style="color:red">Внешний сотрудник!</strong>';
       }
       return   $user->surname. ' ' . $user->name . ' (' . $user->email .')';
    }

    public function is_admin()
    {
        return boolval($this->is_root);
    }
}
