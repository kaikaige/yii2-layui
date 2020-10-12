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
            [1, '系统管理', 0, null],
            [2, '业务模块', 0, null],
            [3, '权限管理', 1, null],
            [4, '系统日志', 1, null],

            [30, '模块管理', 3, '/'.$moduleName.'/auth-menu/system'],
            [31, '菜单管理', 3, '/'.$moduleName.'/auth-menu'],
            [32, '用户授权', 3, '/'.$mdmModuleName.'/assignment'],
            [32, '权限列表', 3, '/'.$mdmModuleName.'/permission'],
            [32, '角色列表', 3, '/'.$mdmModuleName.'/role'],
            [32, '路由列表', 3, '/'.$mdmModuleName.'/route'],

            [40, '前台日志', 4, '/'.$moduleName.'/sys-log?sys=frontend'],
            [41, '后台日志', 4, '/'.$moduleName.'/sys-log?sys=backend'],
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
