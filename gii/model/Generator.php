<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace kaikaige\layui\gii\model;
use yii\gii\generators\model\Generator as BaseGnerator;

class Generator extends BaseGnerator {
	
	const TYPE_TEXT = 1;
	const TYPE_UEDITOR = 2;
	const TYPE_DATE = 3;
	const TYPE_SELECT = 4;
	const TYPE_SWITCH = 5;
	const TYPE_FILE = 6;
	
	public $selectList = [];
	
	public $generateLabelsFromComments = true;
	
	public $dateBehavior = false;
	
	/**
	 * @inheritdoc
	 */
	public function getName()
	{
		return 'Model Generator for LayUi';
	}
	
	public function fieldTypes() {
		return [
			self::TYPE_TEXT => 'text',
			self::TYPE_UEDITOR => 'ueditor',
			self::TYPE_DATE => 'date',
			self::TYPE_SELECT => 'select',
			self::TYPE_SWITCH => 'boolean',
			self::TYPE_FILE => 'file'
		];
	}
	
	public function rules() {
		return array_merge(parent::rules(), [
			[['selectList', 'dateBehavior'], 'safe']
		]);
	}
	
	public function getTableSchema() {
		$db = $this->getDbConnection();
		foreach ($this->getTableNames() as $tableName) {
			$tableSchema = $db->getTableSchema($tableName);
		}
		return $tableSchema;
	}
}