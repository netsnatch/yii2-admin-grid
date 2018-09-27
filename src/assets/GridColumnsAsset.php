<?php

namespace Netsnatch\Yii2GridColumns\assets;

use yii\web\AssetBundle;

class GridColumnsAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/src';
    public $js = [
        'grid-columns.js',
    ];
    public $css = [
        'grid-columns.css'
    ];
    public $depends = [
        '\yii\web\JqueryAsset',
        '\yii\bootstrap\BootstrapAsset',
        '\yii\jui\JuiAsset',
    ];
}
