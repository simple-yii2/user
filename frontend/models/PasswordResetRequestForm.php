<?php

namespace cms\user\frontend\models;

use Yii;
use yii\base\Model;

use cms\user\common\models\User;

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
	 * @var string Verification code
	 */
	public $verificationCode;

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'email' => Yii::t('user', 'E-mail'),
			'verificationCode' => Yii::t('user', 'Verification code'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['email', 'filter', 'filter' => 'trim'],
			[['email', 'verificationCode'], 'required'],
			['email', 'email'],
			['email', 'exist',
				'targetClass' => '\cms\user\common\models\User',
				'filter' => ['active' => true],
			],
			['verificationCode', 'captcha'],
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
