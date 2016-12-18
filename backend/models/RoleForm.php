<?php

namespace cms\user\backend\models;

use Yii;

/**
 * Role form
 */
class RoleForm extends PermissionForm
{

	/**
	 * @var array Roles
	 */
	public $roles = [];

	/**
	 * @var array Permissions
	 */
	public $permissions = [];

	/**
	 * @var array Users
	 */
	public $users = [];

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), [
			'roles' => Yii::t('user', 'Roles'),
			'permissions' => Yii::t('user', 'Permissions'),
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return array_merge(parent::rules(), [
			[['roles', 'permissions'], 'each', 'rule' => ['string']],
			['users', 'each', 'rule' => ['integer']],
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		if ($this->item !== null) {
			$auth = Yii::$app->authManager;

			$children = $auth->getChildren($this->item->name);
			foreach ($children as $child) if ($child->type == $child::TYPE_ROLE) $this->roles[] = $child->name;
			else $this->permissions[] = $child->name;

			$this->users = $auth->getUserIdsByRole($this->item->name);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function setAttributes($values, $safeOnly = true)
	{
		parent::setAttributes($values, $safeOnly);

		if ($this->roles === '') $this->roles = [];
		if ($this->permissions === '') $this->permissions = [];
		if ($this->users === '') $this->users = [];
	}

	/**
	 * Role creation
	 * @return boolean
	 */
	public function create()
	{
		if (!$this->validate()) return false;

		$auth = Yii::$app->authManager;

		$this->item = $auth->createRole($this->name);
		$this->item->description = $this->description;

		if (!$auth->add($this->item)) return false;

		return $this->updateRelative();
	}

	/**
	 * Role updating
	 * @return boolean
	 */
	public function update()
	{
		if (parent::update() === false) return false;

		return $this->updateRelative();
	}

	/**
	 * Update relative
	 * @return boolean
	 */
	public function updateRelative()
	{
		$auth = Yii::$app->authManager;

		//children
		//old
		$oldChildren = $auth->getChildren($this->item->name);
		//new
		$children = [];
		foreach ($this->roles as $name) $children[$name] = $auth->getRole($name);
		foreach ($this->permissions as $name) $children[$name] = $auth->getPermission($name);
		//remove
		foreach (array_diff_key($oldChildren, $children) as $child) $auth->removeChild($this->item, $child);
		//add
		foreach (array_diff_key($children, $oldChildren) as $child) $auth->addChild($this->item, $child);

		//users
		//old
		$oldUsers = $auth->getUserIdsByRole($this->item->name);
		//new
		$users = $this->users;
		//revoke
		foreach (array_diff($oldUsers, $users) as $user) $auth->revoke($this->item, $user);
		//assign
		foreach (array_diff($users, $oldUsers) as $user) $auth->assign($this->item, $user);

		return true;
	}

}
