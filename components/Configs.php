<?php

namespace kaikaige\layui\components;

use Yii;
use yii\base\BaseObject;
use yii\caching\Cache;
use yii\db\Connection;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\rbac\ManagerInterface;


/**
 * params.php
 * [
 *     'layui.admin.configs' => [
 *          ...
 *          title => 'LayUI Admin'
 *          ...
 *      ]
 * ]
 * Class Configs
 * @package kaikaige\layui\components
 * @property string $moduleName
 */
class Configs extends BaseObject
{
    /**
     * @var string 如果main.php定义的模块名不是ms则需要配置该项
     */
    public $moduleName = 'ms';

    /**
     * @var string 权限至菜单表名
     */
    public $menuTable = '{{%auth_menu}}';

    /**
     * @var string 如果main.php定义的mdm-admin模块名不是mdm则需要配置该项，不然初始化数据库菜单表对应的route会报错
     */
    public $mdmModuleName = 'mdm';

    /**
     * @var string 默认生成的管理员表，如果修改该选项common/models/User对应的table也需要修改
     */
    public $userTable = '{{%user}}';

    /**
     * @var string 系统名称，影响page title
     */
    public $title = 'XXX管理系统';

    /**
     * @var array 上传配置项
     */
    public $uploadConfig = [
        'action' => '/ms/home/upload', //string 涉及上传的组件默认上传地址，组件也可以单独定义
        'domain' => '/', //默认就是根路径，如果上传至OSS或图片服务器则是对应的URL
        'dir' => 'upload', //本地存储，默认上传目录
        'inputName' => 'file', //上传域file input name
    ];

    private static $_instance;

    /**
     * Create instance of self
     * @return static
     */
    public static function instance()
    {
        if (self::$_instance === null) {
            $default = new static;
            $params = ArrayHelper::getValue(Yii::$app->params, 'layui.admin.configs', []);
            foreach ($params as $key=>$value) {
                if (is_array($value)) {
                    $params[$key] = ArrayHelper::merge($default->{$key}, $value);
                }
            }
            $params['class'] = static::className();
            return self::$_instance = Yii::createObject($params);
        }

        return self::$_instance;
    }

    public function getModuleName()
    {
        return self::instance()->moduleName;
    }
}
