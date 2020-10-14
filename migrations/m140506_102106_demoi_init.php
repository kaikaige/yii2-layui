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
            'status' => $this->boolean(),
            'title' => $this->string(),
            'type_id' => $this->integer(),
            'imgurl' => $this->string(255),
            'fileurl' => $this->string(255),
            'content' => $this->text(),
            'create_time' => $this->dateTime(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('demo');
    }
}
