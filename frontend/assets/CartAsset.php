<?php
namespace frontend\assets;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class CartAsset extends AssetBundle{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'style/global.css',
        'style/header.css',
        'style/footer.css',

    ];
    public $js = [

    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}