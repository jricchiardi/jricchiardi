<?php

namespace frontend\assets;

set_time_limit(120);

use yii\web\AssetBundle;

class KendoAsset extends AssetBundle
{
    public $sourcePath = '@frontend/Kendo/assets/';
    
    public $css = [
        'styles/kendo.common.min.css',
        'styles/kendo.default.min.css',
        //'styles/kendo.fiori.min.css',
       // 'styles/kendo.custom.css',
        
    ];
    public $js = [        
        'js/kendo.all.min.js',        
        'js/messages/kendo.messages.es-ES.min.js',
        'js/cultures/kendo.culture.es-AR.min.js',
        'js/config.js'
    ];

    
//    public $depends = [
//        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
//    ];
    
}