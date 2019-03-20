<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 20.03.2019
 * Time: 21:20
 */

namespace app\controllers;


use Faker\Provider\File;
use yii\web\Controller;
use app\models\Files;
use Yii;

class FileController extends Controller
{
    public function actionDownload($id)
    {
        $file = Files::findOne($id);
        $path = Yii::getAlias('@webroot'). '/uploads/files/' . $file['tmp'];
        if(file_exists($path)){
            return Yii::$app->response->SendFile($path);
        } else{
            print_pre($path);
            die();
        }
    }

    public function actionDelete($id)
    {
        $file = Files::findOne($id);
        $file->deleteFile();
        $file->delete();
        return $this->redirect('/chain/index');
    }
}