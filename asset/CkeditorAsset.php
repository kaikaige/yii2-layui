<?php

namespace kaikaige\layui\asset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;
use yii\web\View;

/**
 * Created by PhpStorm.
 * User: gaokai
 * Date: 2019-10-21
 * Time: 10:58
 */

class CkeditorAsset extends AssetBundle
{
    public $sourcePath = __DIR__.'/dist';

    public $js = [
        'ck.js',
    ];

    public $depends = [
        JqueryAsset::class,
    ];
}