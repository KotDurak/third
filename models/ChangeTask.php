<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 31.03.2019
 * Time: 20:22
 */

namespace app\models;

use Yii;
use yii\base\Model;

class ChangeTask extends Model
{
    public $id_step;
    public $id_user;
    public $is_outer;
    public $is_self;
    public $is_clear;

    public function rules()
    {
        
    }
}