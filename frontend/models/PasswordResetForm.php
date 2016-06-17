<?php

namespace app\modules\user\frontend\models;

use Yii;
use yii\base\InvalidParamException;
use yii\base\Model;

use app\modules\user\common\models\User;

/**
 * Password reset form
 */
class PasswordResetForm extends Model {

	/**
	 * @var string Password
	 */
	public $password;

	/**
	 * @var string Password confirm
	 */
	public $confirm;

	/**
	 * @var app\modules\user\common\models\User User object
	 */
	private $_user;

	/**
	 * Constructor
	 * @param string $token 
	 * @param array $config 
	 * @return void
	 */
	public function __construct($token, $config = []) {
		if (empty($token) || !is_string($token)) {
			throw new InvalidParamException('Password reset token cannot be blank.');
		}
		$this->_user = User::findByPasswordResetToken($token);
		if (!$this->_user) {
			throw new InvalidParamException('Wrong password reset token.');
		}
		parent::__construct($config);
	}

	/**
	 * Attribute labels
	 * @return array
	 */
	public function attributeLabels() {
		return [
			'password'=>Yii::t('user', 'Password'),
			'confirm'=>Yii::t('user', 'Confirm'),
		];
	}

	/**
	 * Validation rules
	 * @return array
	 */
	public function rules() {
		return [
			['password', 'required'],
			['password', 'string', 'min'=>4],
			['confirm', 'required'],
			['confirm', 'compare', 'compareAttribute'=>'password'],
		];
	}

	/**
	 * Password reset
	 * @return boolean
	 */
	public function resetPassword() {
		$user = $this->_user;
		$user->setPassword($this->password);
		$user->removePasswordResetToken();

		return $user->save();
	}
}
