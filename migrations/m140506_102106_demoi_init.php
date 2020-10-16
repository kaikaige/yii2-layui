<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

/**
 * Initializes RBAC tables.
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @since 2.0
 */
class m140506_102106_demoi_init extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('demo', [
            'id' => $this->primaryKey(),
            'status' => $this->boolean()->comment('状态'),
            'title' => $this->string()->comment('标题'),
            'type_id' => $this->integer()->comment('类型'),
            'img_url' => $this->string(255)->comment('封面'),
            'file_url' => $this->string(255)->comment('文件'),
            'content' => $this->text()->comment('描述'),
            'create_time' => $this->dateTime()->comment('创建时间'),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('demo');
    }
}
