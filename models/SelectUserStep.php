<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 09.03.2019
 * Time: 9:58
 */

namespace app\models;

use yii\base\Model;
use Yii;

class SelectUserStep extends Model
{
    public $id_user;
    public $id_step;
    public $label;
    public $id_group;


    public function __construct(array $config = [])
    {
        $this->id_step = $config['id_step'];
        $this->label = $config['label'];
        $this->id_group = $config['id_group'];
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id_user', 'id_step'], 'integer']
        ];
    }
}