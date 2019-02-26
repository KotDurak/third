<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25.02.2019
 * Time: 22:49
 */

namespace app\models;


class SignupService
{
    public function signup(SignupForm $form)
    {
        $user = new User();
        $user->surname = $form->surname;
        $user->name = $form->name;
        $user->password = $form->password;
    }
}