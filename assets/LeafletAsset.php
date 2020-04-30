<?php

namespace app\assets;

use yii\web\AssetBundle;

class LeafletAsset extends AssetBundle
{
    public $sourcePath = '@npm/leaflet/dist';
    public $css = [
        'leaflet.css'
    ];
    public $js = [
        'leaflet.js'
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG
    ];
}