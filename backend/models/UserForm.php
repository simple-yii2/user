<?php

namespace cms\users\backend\models;

use Yii;
use yii\base\Model;

use cms\users\common\models\User;

/**
 * User editting form
 */
class UserForm extends Model
{

	/**
	 * @var boolean
	 */
	public $admin;

	/**
	 * @var string Email
	 */
	public $email;

	/**
	 * @var boolean Active
	 */
	public $active;

	/**
	 * @var string
	 */
	public $firstName;

	/**
	 * @var string
	 */
	public $lastName;

	/**
	 * @var string Comment
	 */
	public $comment;

	/**
	 * @var array Roles
	 */
	public $roles;

	/**
	 * @var cms\users\common\models\User
	 */
	private $_object;

	/**
	 * @inheritdoc
	 * @param cms\users\common\models\User $object 
	 */
	public function __construct(\cms\users\common\models\User $object, $config = [])
	{
		$this->_object = $object;

		//attributes
		$this->admin = $object->admin == 0 ? '0' : '1';
		$this->email = $object->email;
		$this->active = $object->active == 0 ? '0' : '1';
		$this->firstName = $object->firstName;
		$this->lastName = $object->lastName;
		$this->comment = $object->comment;

		$this->roles = array_keys(Yii::$app->authManager->getAssignments($object->id));

		parent::__construct($config);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'admin' => Yii::t('users', 'Administrator'),
			'email' => Yii::t('users', 'E-mail'),
			'active' => Yii::t('users', 'Active'),
			'firstName' => Yii::t('users', 'First name'),
			'lastName' => Yii::t('users', 'Last name'),
			'comment' => Yii::t('users', 'Comment'),
			'roles' => Yii::t('users', 'Roles'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['admin', 'active'], 'boolean'],
			['email', 'email', 'on' => 'create'],
			['email', 'required', 'on' => 'create'],
			['email', function($attribute, $params) {
				if (User::find()->where(['email' => $this->email])->count() > 0) {
					$this->addError($attribute, Yii::t('users', 'The entered e-mail is already in use.'));
				}
			}, 'on' => 'create'],
			[['firstName', 'lastName'], 'string', 'max' => 50],
			['comment', 'string', 'max' => 200],
			['roles', 'each', 'rule' => ['string']],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function setAttributes($values, $safeOnly = true)
	{
		parent::setAttributes($values, $safeOnly);

		if ($this->roles === '')
			$this->roles = [];
	}

	/**
	 * User name getter
	 * @return string
	 */
	public function getUsername()
	{
		return $this->_object->getUsername();
	}

	/**
	 * Save
	 * @return boolean
	 */
	public function save()
	{
		if (!$this->validate())
			return false;

		$object = $this->_object;
		$isNewRecord = $object->getIsNewRecord();

		$object->admin = $this->admin == 1;
		$object->email = $this->email;
		$object->active = $this->active == 1;
		$object->firstName = $this->firstName;
		$object->lastName = $this->lastName;
		$object->comment = $this->comment;

		if (!$object->save(false))
			return false;

		//roles
		$auth = Yii::$app->authManager;
		$oldRoles = [];
		$names = array_keys($auth->getAssignments($object->id));
		foreach ($names as $name)
			$oldRoles[$name] = $auth->getRole($name);
		$roles = [];
		foreach ($this->roles as $name)
			$roles[$name] = $auth->getRole($name);

		//author
		if ($isNewRecord) {
			$author = $auth->getRole('author');
			if ($author !== null)
				$auth->assign($author, $object->id);
		}
		//revoke
		foreach (array_diff_key($oldRoles, $roles) as $role)
			$auth->revoke($role, $object->id);
		//assign
		foreach (array_diff_key($roles, $oldRoles) as $role)
			$auth->assign($role, $object->id);

		return true;
	}

}
