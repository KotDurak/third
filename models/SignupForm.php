<?php


namespace app\models;


use yii\base\Model;
use Yii;

class SignupForm extends Model
{
    public $name;
    public $surname;
    public $email;
    public $password;
    public $confirmPassword;
    public $capture;

    public function rules()
    {
        return [
            ['capture', 'captcha'],
            [['surname','name' , 'email', 'password', 'confirmPassword'], 'required'],
            ['email', 'email'],
            ['password', 'matchPassword'],
        ];
    }

    public function matchPassword($attribute)
    {
        if($this->password != $this->confirmPassword){
            $this->addError($attribute, 'Пароли не совпадают');
        }
    }


    public function signup()
    {
        $user = new User();
        $user->name = $this->name;
        $user->surname = $this->surname;
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        $user->email = $this->email;
        $user->email_confirm_token = Yii::$app->security->generateRandomString();
        $user->status = User::STATUS_WAIT;

        if(!$user->save()){
            throw new \RuntimeException('Ошибка при сохранении');
        }

        return $user;
    }

    public function sentEmailConfirm(User $user){
        $email = $user->email;
        $sent = Yii::$app->mailer
            ->compose([
                'html' => 'layouts/confirm',
                'text' => 'layouts/text'
            ], [
                'user' => $user
            ])
            ->setTo($email)
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setSubject('Подверждение регистрации')
            ->send();
        if(!$sent){
            throw new \RuntimeException('Ощибка отравки');
        }
    }

    public static function sentEmailToUser(User $user, $password)
    {
        $email = $user->email;
        $sent = Yii::$app->mailer
            ->compose([
                'html' => 'layouts/user',
                'text' => 'layouts/user-text'
            ], [
                'user'  => $user,
                'password'  => $password
            ])
            ->setTo($email)
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setSubject('Ваши данные от сайте')
            ->send();
        if(!$sent)
            throw new \RuntimeException('Ошииька отправки сообщения');
    }

    public static function sentNewPassword(User $user, $password)
    {
        $email = $user->email;
        $sent = Yii::$app->mailer
            ->compose([
                'html'  =>  'layouts/password',
                'text'  => 'layouts/user-password',
            ], [
                'user'      => $user,
                'password'  => $password
            ])->setTo($email)
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setSubject('Новый пароль в системе third-dimension')
            ->send();
    }

    public function confirmation($token)
    {
        if(empty($token)){
            throw  new \DummyException('Empty confirm token');
        }

        $user = User::findOne(['email_confirm_token' => $token]);
        if (!$user) {
            throw new \DomainException('User is not found.');
        }

        $user->email_confirm_token = null;
        $user->status = User::STATUS_ACTIVE;
        if (!$user->save()) {
            throw new \RuntimeException('Saving error.');
        }

        if (!Yii::$app->getUser()->login($user)){
            throw new \RuntimeException('Error authentication.');
        }
    }
}