<?php

namespace cms\user\frontend\models;

use Yii;
use yii\base\InvalidParamException;
use yii\base\Model;

use cms\user\common\models\User;

/**
 * Password reset form
 */
class PasswordResetForm extends Model
{

	/**
	 * @var string Password
	 */
	public $password;

	/**
	 * @var string Password confirm
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
	public function __construct($object, $config = [])
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
			'password' => Yii::t('user', 'Password'),
			'confirm' => Yii::t('user', 'Confirm'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['password', 'required'],
			['password', 'string', 'min' => 4],
			['confirm', 'required'],
			['confirm', 'compare', 'compareAttribute' => 'password'],
		];
	}

	/**
	 * Password reset
	 * @return boolean
	 */
	public function resetPassword()
	{
		if (!$this->validate())
			return false;

		$object = $this->_object;

		$object->setPassword($this->password);
		$object->removePasswordResetToken();

		return $object->save();
	}

}
