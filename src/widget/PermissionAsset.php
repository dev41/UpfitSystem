<?php
namespace app\src\widget;

use app\assets\AppAsset;
use yii\web\AssetBundle;

class PermissionAsset extends AssetBundle
{
    public $sourcePath = '@widget';

    public $js = [
        'js/PermissionHelper.js',
    ];

//    public $publishOptions = [
//        'forceCopy' => true,
//    ];

    public $depends = [
        AppAsset::class,
    ];
}