<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 08.03.2019
 * Time: 18:16
 */

namespace app\assets;

use yii\web\AssetBundle;
use Yii;


class BootboxAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootbox.js';

    public $js = [
        'bootbox.js',
    ];
}