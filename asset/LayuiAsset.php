<?php

namespace kaikaige\layui\asset;
use yii\web\AssetBundle;
use yii\web\View;

/**
 * Created by PhpStorm.
 * User: gaokai
 * Date: 2019-10-21
 * Time: 10:58
 */

class LayuiAsset extends AssetBundle
{
    public $sourcePath = __DIR__.'/dist';

    public $js = [
        'libs/layui/layui.js',
    ];

    public $jsOptions = [
        'position' => View::POS_HEAD
    ];

    public $css = [
        'libs/layui/css/layui.css',
        'module/admin.css?v=315',
    ];

    public $depends = [
        CkeditorAsset::class
    ];
}