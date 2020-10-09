<?php
use yii\helpers\Inflector;
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $properties array list of properties (property => [type, name. comment]) */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;
<?php if ($generator->dateBehavior):?>
use kaikaige\layui\behaviors\DateBehavior;
<?php endif;?>
<?php if (!$generator->selectList) $generator->selectList = [];?>
<?php if (!empty($generator->selectList)):?>
use yii\helpers\ArrayHelper;
<?php endif;?>
/**
 * This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($properties as $property => $data): ?>
 * @property <?= "{$data['type']} \${$property}"  . ($data['comment'] ? ' ' . strtr($data['comment'], ["\n" => ' ']) : '') . "\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
<?php foreach ($generator->selectList as $val) :?>
	/**
	 *
	 */
	//const <?php echo strtoupper($val)?>_ = ;
	
	/**
	 *
	 */
	//const <?php echo strtoupper($val)?>_ = ;
<?php endforeach;?>

<?php foreach ($generator->selectList as $val) :?>
	/**
	 * $key为空返回[statsCode=>commmet, statsCode=>commmet]
	 * $key不为空 && $color等于flase 返回commnet
	 * $key不为空 && $color等于ture 返回带有色标的标签
	 * @param string $key 
	 * @param boolean $color
	 * @return array or string
	 */
	public static function <?=lcfirst(Inflector::camelize($val))?>List($key=null, $color=false) {
		$list = [
			//self::<?php echo strtoupper($val)?>_ => ['key' => self::<?php echo strtoupper($val)?>_ , 'comment' => '', 'color' => '#fff'],
			//self::<?php echo strtoupper($val)?>_ => ['key' => self::<?php echo strtoupper($val)?>_ , 'comment' => '', 'color' => '#fff'],
		];
		if (is_null($key) ) {
			return ArrayHelper::map($list, 'key', 'comment');
		}
		return !$color ? $list[$key]['comment'] : f_status($list[$key]['comment'], $list[$key]['color']);
	}
<?php endforeach;?>
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= empty($rules) ? '' : ("\n            " . implode(",\n            ", $rules) . ",\n        ") ?>];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
<?php if ($generator->dateBehavior):?>
	public function behaviors() {
        return [
            ['class'=>DateBehavior::class]
        ];
    }
<?php endif;?>
    
<?php foreach ($relations as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
<?php if ($queryClassName): ?>
<?php
    $queryClassFullName = ($generator->ns === $generator->queryNs) ? $queryClassName : '\\' . $generator->queryNs . '\\' . $queryClassName;
    echo "\n";
?>
    /**
     * @inheritdoc
     * @return <?= $queryClassFullName ?> the active query used by this AR class.
     */
    public static function find()
    {
        return new <?= $queryClassFullName ?>(get_called_class());
    }
<?php endif; ?>
}
