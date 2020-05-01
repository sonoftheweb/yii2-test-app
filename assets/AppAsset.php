<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap-datepicker3.min.css',
        'css/site.css',
        'css/datatables.min.css',
        'css/fixedHeader.bootstrap4.min.css',
    ];
    public $js = [
        'js/bootstrap-datepicker.min.js',
        'js/application.js',
        'js/bootstrap-autocomplete.min.js',
        'js/datatables.min.js',
        //'js/fixedHeader.bootstrap4.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}
