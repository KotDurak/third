<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 03.03.2019
 * Time: 10:56
 */

namespace app\models;


use Yii;
use yii\base\Model;

class Import extends Model
{
    public $file;
    public $id_chain;

    public function rules()
    {
        return [
           [['file'], 'file']
        ];
    }

    public function attributeLabels()
    {
        return [
            'file'  => 'Импорт файла'
        ];
    }

}