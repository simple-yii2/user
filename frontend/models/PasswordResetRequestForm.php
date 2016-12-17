<?php

namespace cms\user\frontend\models;

use Yii;
use yii\base\Model;

use cms\user\common\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model {

	/**
	 * @var string User e-mail
	 */
	public $email;

	/**
	 * @var string Verify code
	 */
	public $verifyCode;

	/**
	 * Attribute labels
	 * @return array
	 */
	public function attributeLabels() {
		return [
			'email' => Yii::t('user', 'E-mail'),
			'verifyCode' => Yii::t('user', 'Verify code'),
		];
	}

	/**
	 * Validation rules
	 * @return array
	 */
	public function rules() {
		return [
			['email', 'filter', 'filter' => 'trim'],
			[['email', 'verifyCode'], 'required'],
			['email', 'email'],
			['email', 'exist',
				'targetClass' => '\cms\user\common\models\User',
				'filter' => ['active' => true],
			],
			['verifyCode', 'captcha'],
		];
	}

	/**
	 * Send password reset email
	 * @return boolean
	 */
	public function sendEmail() {
		$user = User::findOne([
			'active' => true,
			'email' => $this->email,
		]);

		if ($user !== null) return $user->sendResetPasswordEmail();

		return false;
	}
}
