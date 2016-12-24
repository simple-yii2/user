<?php

namespace cms\users\frontend\models;

use Yii;
use yii\base\Model;

use cms\users\common\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{

	/**
	 * @var string User e-mail
	 */
	public $email;

	/**
	 * @var string Verify code
	 */
	public $verifyCode;

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'email' => Yii::t('users', 'E-mail'),
			'verifyCode' => Yii::t('users', 'Verify code'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['email', 'filter', 'filter' => 'trim'],
			[['email', 'verifyCode'], 'required'],
			['email', 'email'],
			['email', 'exist',
				'targetClass' => '\cms\users\common\models\User',
				'filter' => ['active' => true],
			],
			['verifyCode', 'captcha'],
		];
	}

	/**
	 * Send password reset email
	 * @return boolean
	 */
	public function sendEmail()
	{
		$user = User::findOne([
			'active' => true,
			'email' => $this->email,
		]);

		if ($user !== null)
			return $user->sendResetPasswordEmail();

		return false;
	}
	
}
