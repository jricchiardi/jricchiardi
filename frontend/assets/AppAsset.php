<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',        
    ];
    
    public $js = [
        'js/bootstrap-slider.js',
        'js/jquery.customSelect.js',
        'js/jquery.ui.touch-punch.min.js',
        'js/jquery-migrate-1.2.1.min.js',        
        'http://code.highcharts.com/highcharts.js',
        'http://code.highcharts.com/modules/exporting.js',
        'js/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    
//    public $jsOptions = ['position' => \yii\web\View::POS_LOAD];
}
