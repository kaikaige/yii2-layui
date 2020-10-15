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