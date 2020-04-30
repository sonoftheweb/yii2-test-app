<?php

namespace app\assets;

use yii\web\AssetBundle;

class BowerAsset extends AssetBundle
{
    public $basePath = '@bower';
    public $css = [
        'fontawesome/css/fontawesome.css',
        'leaflet/dist/leaflet.css'
    ];
    public $js = [
        'fontawesome/js/all.js',
        'leaflet/dist/leaflet.js'
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG
    ];
}