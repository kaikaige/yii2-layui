<?php
namespace kaikaige\layui\log;

use Yii;
use yii\db\Connection;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\helpers\VarDumper;
use yii\log\Target;
use yii\helpers\ArrayHelper;

class DbTarget extends Target
{
    /**
     * @var Connection|array|string the DB connection object or the application component ID of the DB connection.
     * After the DbTarget object is created, if you want to change this property, you should only assign it
     * with a DB connection object.
     * Starting from version 2.0.2, this can also be a configuration array for creating the object.
     */
    public $db = 'db';
    /**
     * @var string name of the DB table to store cache content. Defaults to "log".
     */
    public $logTable = '{{%log}}';


    /**
     * Initializes the DbTarget component.
     * This method will initialize the [[db]] property to make sure it refers to a valid DB connection.
     * @throws InvalidConfigException if [[db]] is invalid.
     */
    public function init()
    {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::className());
    }

    /**
     * Stores log messages to DB.
     */
    public function export()
    {
        $tableName = $this->db->quoteTableName($this->logTable);
        $sql = "INSERT INTO $tableName ([[level]], [[category]], [[log_time]], [[prefix]], [[message]], [[context]], [[ip]])
        VALUES (:level, :category, :log_time, :prefix, :message, :context, :ip)";
        $command = $this->db->createCommand($sql);
        foreach ($this->messages as $message) {
            list($text, $level, $category, $timestamp) = $message;
            if ($category === 'application' || $category == 'yii\web\HttpException:401') {
                continue;
            }
            
            if (!is_string($text)) {
                // exceptions may not be serializable if in the call stack somewhere is a Closure
                if ($text instanceof \Exception) {
                    $text = (string) $text;
                } else {
                    $text = VarDumper::export($text);
                }
            }
            $context = ArrayHelper::filter($GLOBALS, $this->logVars);
            $result = [];
            foreach ($context as $key => $value) {
            		if (isset($value['_csrf'])) unset($value['_csrf']);
            		$result[] = "\${$key} = " . VarDumper::dumpAsString($value);
            }
            $context = implode("\n\n", $result);
	        $command->bindValues([
	            ':level' => $level,
	            ':category' => $category,
                ':log_time' => date('Y-m-d H:i:s', time()),
	            ':prefix' => $this->getMessagePrefix($message),
	            ':message' => $text,
	            ':context' => $context,
	            ':ip' =>  static::IP()
	        ])->execute();
        }
    }
    
    /**
     *
     * @Title: getClientIP
     * @Description: 获取客户端ip
     * @return: NULL|Ambigous <string, unknown>
     * @author: kai.gao
     * @date: 2014-11-20 下午10:56:42
     */
    public static function IP() {
        static $ip = NULL;
        if ($ip !== NULL)
            return $ip;
        if (isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos)
                unset($arr[$pos]);
            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $ip = ( false !== ip2long($ip) ) ? $ip : '0.0.0.0';
        return $ip;
    }
    
}
