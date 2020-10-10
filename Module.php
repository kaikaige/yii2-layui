<?php

namespace kaikaige\layui;

use Yii;

class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $defaultRoute = 'home';
    /**
     * @var string Main layout using for module. Default to layout of parent module.
     * Its used when `layout` set to 'left-menu', 'right-menu' or 'top-menu'.
     */
    public $mainLayout = '@kaikaige/layui/views/layouts/main.php';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'kaikaige\layui\controllers';

    public $homeUrl = [];
}
