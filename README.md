LayUi
=====
Layui admin

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require kaikaige/yii2-layui:"<version_number>"
```

to the require section of your `composer.json` file.
```
yii2-layui
    mdmsoft/yii2-admin #权限管理组件
    yii2-oss #上传文件至OSS
    yii2-tinymce #富文本编辑器
```



Usage
-----
> 项目初始化化、数据库配置、url美化查看官方文档即可 

#### Step 1 编辑配置文件
> `backend/config/main.php` 增加两个模块 `ms` and `mdm`，最好不要修改模块名，如需请参考配置篇
* ms 模块后台管理界面入口
* mdm 第三方rbac权限管理插件，依赖会同步安装
```
...
'language' => 'zh-CN',
'defaultRoute' => '/ms',
'modules' => [
    'ms' => [
        'class' => \kaikaige\layui\Module::class,
    ],
    'mdm' => [
        'class' => 'mdm\admin\Module',
        'layout' => 'main',
    ]
],
...
```

> 组件修改
* 使用db authManager基于数据库的权限管理
* log组件使用插件自己封装的插件
```
'components' => [
    ...

    'authManager' => [
        'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
    ],
    'log' => [
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => [
            [
                'class' => \kaikaige\layui\log\DbTarget::class,
                'levels' => ['error', 'warning'],
                'logTable' => 'log_backend',
            ],
        ],
    ],
    'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'rules' => [
        ],
    ],
    ...
]
```
> 增加mdm access 来过滤访问请求，不需要鉴权的URL可以修改该配置
```
'as access' => [
    'class' => 'mdm\admin\components\AccessControl',
    'allowActions' => [
        'site/login',
        'site/logout',
        'site/index',
        'debug/*',
        'admin/*',
    ]
],
```

#### Step 2 初始化数据库
> 使用migrate工具初始化数据库，会生成对应的数据表
```
./yii migrate --migrationPath=@kaikaige/layui/migrations
```
* auth_* 是rabc授权系统用到的数据表
* auth_menu 菜单表，会初始化一些系统管理菜单
* log_* 是系统日志表，对应main.php log组件自定义配置
* user 管理员用户表，这个表会单独说明

#### Step 3 gii配置
> 编辑配置文件 `config/main-local.php`
```
$config['modules']['gii'] = [
    'class' => 'yii\gii\Module',
    'generators'=> [
        'crud'=> [
            'class' => \kaikaige\layui\gii\crud\Generator::class,
            'templates'=> [
                'backend'=>'@kaikaige/layui/gii/crud/default'
            ],
        ],
        'model' => [
            'class' => \kaikaige\layui\gii\model\Generator::class,
            'templates'=> [
                'backend'=>'@kaikaige/layui/gii/model/default'
            ],
        ]
    ]
];
```
#### 关于配置
> 整个配置都基于yii2自带的params组件来管理的，配置字段名`layui.admin.configs`是插件预定的不可以修改，例如修改管理后台页面Title，编辑`config/params.php`
 
```
[
    ...
    'layui.admin.configs' => [
        'title'=>'Title' //
        ...
    ],
    ...
]
```

> 配置的加载原理是 `kaikaige\layui\components\Configs`通过初试加载params对应`layui.admin.configs`的配置，完整的配置项就是Configs类下的所有公有属性，建议如无特殊需求目前最有意义的修改就是`title`与`uploadConfig`
```
class Configs extends BaseObject
{
    ...
    /**
     * @var string 如果main.php定义的模块名不是ms则需要配置该项
     */
    public $moduleName = 'ms';

    /**
     * @var string 权限菜单表名
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
    ...
}
```

