<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 10.03.2019
 * Time: 11:28
 */

namespace app\models;

use Yii;
use yii\base\Model;

class TaskEdit  extends  Model
{
    public $id_chain;

    public function rules()
    {
       return [
         [['id_chain'], 'integer']
       ];
    }
}