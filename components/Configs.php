<?php

namespace kaikaige\layui\components;

use Yii;
use yii\caching\Cache;
use yii\db\Connection;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\rbac\ManagerInterface;


/**
 * Class Configs
 * @package kaikaige\layui\components
 * @property string $moduleName
 */
class Configs extends \mdm\admin\BaseObject
{
    public $moduleName = 'layui';

    public $menuTable = 'auth_menu';

    public $mdmModuleName = 'admin';

    private static $_instance;

    /**
     * Create instance of self
     * @return static
     */
    public static function instance()
    {
        if (self::$_instance === null) {
            $type = ArrayHelper::getValue(Yii::$app->params, 'layui.admin.configs', []);
            if (is_array($type) && !isset($type['class'])) {
                $type['class'] = static::className();
            }

            return self::$_instance = Yii::createObject($type);
        }

        return self::$_instance;
    }

    public function getModuleName()
    {
        return self::instance()->moduleName;
    }
}
