<?php

namespace cms\user\backend\models;

use Yii;
use yii\base\Model;

use cms\user\common\models\User;

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
	 * @var cms\user\common\models\User
	 */
	private $_object;

	/**
	 * @inheritdoc
	 * @param cms\user\common\models\User $object 
	 */
	public function __construct(\cms\user\common\models\User $object, $config = [])
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
			'admin' => Yii::t('user', 'Administrator'),
			'email' => Yii::t('user', 'E-mail'),
			'active' => Yii::t('user', 'Active'),
			'firstName' => Yii::t('user', 'First name'),
			'lastName' => Yii::t('user', 'Last name'),
			'comment' => Yii::t('user', 'Comment'),
			'roles' => Yii::t('user', 'Roles'),
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
					$this->addError($attribute, Yii::t('user', 'The entered e-mail is already in use.'));
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
