<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

use yii\base\InvalidConfigException;
use yii\db\Migration;
use yii\log\DbTarget;

/**
 * Initializes log table.
 *
 * The indexes declared are not required. They are mainly used to improve the performance
 * of some queries about message levels and categories. Depending on your actual needs, you may
 * want to create additional indexes (e.g. index on `log_time`).
 *
 * @author Alexander Makarov <sam@rmcreative.ru>
 * @since 2.0.1
 */
class m141106_185632_log_init extends Migration
{
    /**
     * @var DbTarget[] Targets to create log table for
     */
    private $dbTargets = [];

    /**
     * @throws InvalidConfigException
     * @return DbTarget[]
     */
    protected function getDbTargets()
    {
        return ['log_backend', 'log_frontend'];
    }

    public function up()
    {
        foreach ($this->getDbTargets() as $target) {
            $tableOptions = null;
            if ($this->db->driverName === 'mysql') {
                // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
                $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
            }

            $this->createTable($target, [
                'id' => $this->bigPrimaryKey(),
                'level' => $this->integer(),
                'category' => $this->string(),
                'log_time' => $this->dateTime(),
                'prefix' => $this->text(),
                'message' => $this->text(),
                'context' => $this->text(),
                'ip' => $this->string(255),
            ], $tableOptions);

            $this->createIndex('idx_log_level', $target, 'level');
            $this->createIndex('idx_log_category', $target, 'category');
            $this->createIndex('idx_log_ip', $target, 'ip');
        }
    }

    public function down()
    {
        foreach ($this->getDbTargets() as $target) {
            $this->dropTable($target);
        }
    }
}
