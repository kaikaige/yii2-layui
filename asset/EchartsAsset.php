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

class EchartsAsset extends AssetBundle
{
    public $sourcePath = __DIR__.'/dist';

    public $js = [
        'libs/echarts/echarts.min.js',
        'libs/echarts/echartsTheme.js',
    ];

    public $jsOptions = [
        'position' => View::POS_HEAD
    ];

    public $depends = [
        LayuiAsset::class
    ];
}