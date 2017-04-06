<?php

namespace app\components\grid;

use yii\web\AssetBundle;

class AdminGridViewAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets';
    public $js = [
        'admin-grid-view.js',
    ];
    public $css = [
        'admin-grid-view.css'
    ];
    public $depends = [
        '\yii\web\JqueryAsset',
        '\yii\bootstrap\BootstrapAsset',
        '\yii\jui\JuiAsset',
    ];
}
