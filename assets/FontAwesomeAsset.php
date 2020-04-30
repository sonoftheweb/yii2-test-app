<?php


namespace app\assets;


use yii\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@bower/fontawesome/';
    public $css = [
        'css/fontawesome.css',
        'css/solid.css',
        'css/regular.css'
    ];
}