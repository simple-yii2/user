<?php

namespace cms\user\frontend\models;

use Yii;
use yii\base\Model;

/**
 * Password change form
 */
class PasswordChangeForm extends Model
{

	/**
	 * @var string Old password
	 */
	public $oldPassword;

	/**
	 * @var string New password
	 */
	public $password;

	/**
	 * @var string New password confirm
	 */
	public $confirm;

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

		parent::__construct($config);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'oldPassword' => Yii::t('user', 'Current password'),
			'password' => Yii::t('user', 'New password'),
			'confirm' => Yii::t('user', 'Confirm'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['oldPassword', 'password'], 'required'],
			['oldPassword', function($attribute) {
				if (!$this->hasErrors()) {
					if (!$this->_object->validatePassword($this->$attribute)) {
						$this->addError($attribute, Yii::t('user', 'The password is entered incorrectly.'));
					}
				}
			}],
			['password', 'string', 'min' => 4],
			['confirm', 'required'],
			['confirm', 'compare', 'compareAttribute' => 'password'],
		];
	}

	/**
	 * Password change
	 * @return boolean
	 */
	public function changePassword()
	{
		if (!$this->validate())
			return false;
		
		$object = $this->_object;

		$object->setPassword($this->password);

		return $object->save();
	}

}
