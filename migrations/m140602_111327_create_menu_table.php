<?php
use kaikaige\layui\components\Configs;
/**
 * Migration table of table_menu
 * 
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class m140602_111327_create_menu_table extends \yii\db\Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $menuTable = Configs::instance()->menuTable;
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable($menuTable, [
            'id' => $this->primaryKey(),
            'name' => $this->string(128)->notNull(),
            'parent' => $this->integer(),
            'route' => $this->string(),
            'order' => $this->integer()->defaultValue(100)->comment('权重'),
            'data' => $this->binary(),
            'icon' => $this->string(128)->comment('图标'),
            'useable' => $this->tinyInteger()->comment("是否可用")->defaultValue(1),
        ], $tableOptions);

        $moduleName = Configs::instance()->moduleName;
        $mdmModuleName = Configs::instance()->mdmModuleName;

        $this->batchInsert($menuTable, ['id', 'name', 'parent', 'route'], [
            [1, '业务模块', 0, null],
            [10, 'Demo', 1, '/demo'],
            [9, '系统管理', 0, null],
            [30, '权限管理', 9, null],
            [40, '系统日志', 9, null],
            [50, 'gii代码生成', 9, '/gii'],
            [31, '模块管理', 30, '/'.$moduleName.'/auth-menu/system'],
            [32, '菜单管理', 30, '/'.$moduleName.'/auth-menu'],
            [33, '用户授权', 30, '/'.$mdmModuleName.'/assignment'],
            [34, '权限列表', 30, '/'.$mdmModuleName.'/permission'],
            [35, '角色列表', 30, '/'.$mdmModuleName.'/role'],
            [36, '路由列表', 30, '/'.$mdmModuleName.'/route'],
            [41, '前台日志', 40, '/'.$moduleName.'/sys-log?sys=frontend'],
            [42, '后台日志', 40, '/'.$moduleName.'/sys-log?sys=backend'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(Configs::instance()->menuTable);
    }
}
