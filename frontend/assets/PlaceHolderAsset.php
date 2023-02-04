<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class PlaceHolderAsset extends AssetBundle 
{
    public $sourcePath = '@bower/jquery-placeholder'; 
    public $js = [
        'jquery.placeholder.min.js'
    ];
}