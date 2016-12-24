<?php

namespace cms\users\backend\models;

use Yii;
use yii\base\Model;

/**
 * Base model for roles and permissions
 */
abstract class RbacForm extends Model
{

	/**
	 * @var string Name
	 */
	public $name;

	/**
	 * @var string Description
	 */
	public $description;

	/**
	 * @var yii\rbac\Item Rbac object
	 */
	public $item;

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'name' => Yii::t('users', 'Name'),
			'description' => Yii::t('users', 'Description'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['name', 'required'],
			['description', 'string'],
			['name', function() {
				$auth = Yii::$app->authManager;
				$name = $this->item === null ? null : $this->item->name;
				if ($name != $this->name && ($auth->getPermission($this->name) !== null || $auth->getRole($this->name) !== null)) {
					$this->addError('name', Yii::t('users', 'The name is already used.'));
				}
			}],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		
		if ($this->item !== null) $this->setAttributes([
			'name' => $this->item->name,
			'description' => $this->item->description,
		], false);
	}

	/**
	 * Rbac creation
	 * @return boolean
	 */
	abstract public function create();

	/**
	 * Rbac updating
	 * @return boolean
	 */
	public function update()
	{
		if ($this->item === null) return false;

		if (!$this->validate()) return false;

		$name = $this->item->name;

		$this->item->name = $this->name;
		$this->item->description = $this->description;

		return Yii::$app->authManager->update($name, $this->item);
	}

}
