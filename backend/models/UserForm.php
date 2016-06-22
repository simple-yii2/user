<?php

namespace user\backend\models;

use Yii;
use yii\base\Model;

/**
 * User editting form
 */
class UserForm extends Model {

	/**
	 * @var string Email
	 */
	public $email;

	/**
	 * @var boolean Active
	 */
	public $active;

	/**
	 * @var string Comment
	 */
	public $comment;

	/**
	 * @var array Roles
	 */
	public $roles = [];

	/**
	 * @var user\common\models\User User model
	 */
	public $item;

	/**
	 * Attribute names
	 * @return array
	 */
	public function attributeLabels() {
		return [
			'email' => Yii::t('user', 'E-mail'),
			'active' => Yii::t('user', 'Active'),
			'comment' => Yii::t('user', 'Comment'),
			'roles' => Yii::t('user', 'Roles'),
		];
	}

	/**
	 * Validation rules
	 * @return array
	 */
	public function rules() {
		return [
			['active', 'boolean'],
			['comment', 'string'],
			['roles', 'each', 'rule' => ['string']],
		];
	}

	/**
	 * Initialization
	 * Set default values
	 * @return void
	 */
	public function init() {
		parent::init();
		
		if ($this->item !== null) {
			$this->setAttributes([
				'email' => $this->item->email,
				'active' => $this->item->active,
				'comment' => $this->item->comment,
			], false);

			$this->roles = array_keys(Yii::$app->authManager->getAssignments($this->item->id));
		}
	}

	/**
	 * Override
	 */
	public function setAttributes($values, $safeOnly = true) {
		parent::setAttributes($values, $safeOnly);
		if ($this->roles === "") $this->roles = [];
	}

	/**
	 * Save changes
	 * @return boolean
	 */
	public function update() {
		if ($this->item === null) return false;

		$this->item->setAttributes([
			'active' => $this->active,
			'comment' => $this->comment,
		], false);
		if (!$this->item->save(false)) return false;

		$auth = Yii::$app->authManager;

		//roles
		//old
		$oldRoles = [];
		$names = array_keys($auth->getAssignments($this->item->id));
		foreach ($names as $name) $oldRoles[$name] = $auth->getRole($name);
		//new
		$roles = [];
		foreach ($this->roles as $name) $roles[$name] = $auth->getRole($name);
		//revoke
		foreach (array_diff_key($oldRoles, $roles) as $role) $auth->revoke($role, $this->item->id);
		//assign
		foreach (array_diff_key($roles, $oldRoles) as $role) $auth->assign($role, $this->item->id);

		return true;
	}

}
