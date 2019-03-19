<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 18.03.2019
 * Time: 21:46
 */

namespace app\models;


use yii\base\Model;

class FileLoad extends  Model
{
    public $file;
    public $tmp;
    public $name;


    public function rules()
    {
        return [
            [['file'], 'file', 'maxFiles' => 5],
            [['file', 'tmp'], 'safe'],
            [['tmp'], 'string']
        ];
    }

    public function upload()
    {

    }
}