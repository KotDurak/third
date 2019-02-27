<?php

namespace app\modules\admin;

use Yii;
/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{

    public $layout = '@app/modules/admin/views/layouts/main';
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public function beforeAction($action)
    {
       if(parent::beforeAction($action)){
           if(!Yii::$app->user->identity->is_root){
               Yii::$app->response->redirect(Yii::$app->homeUrl)->send();
           }
           return true;
       } else {
           return false;
       }
    }
}
