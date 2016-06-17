<?php

namespace app\modules\user\frontend\models;

use Yii;
use yii\base\Model;

use app\modules\user\common\models\User;

/**
 * Register form
 */
class RegisterForm extends Model {

	/**
	 * @var string User e-mail
	 */
	public $email;

	/**
	 * @var string Password
	 */
	public $password;

	/**
	 * @var string Password confirm
	 */
	public $confirm;

	/**
	 * @var string First name
	 */
	public $firstName;

	/**
	 * @var string Last name
	 */
	public $lastName;

	/**
	 * @var string Verify code
	 */
	public $verifyCode;

	/**
	 * @var boolean Agree for mailing
	 */
	public $mailing = true;

	/**
	 * @var app\modules\user\common\models\User User object
	 */
	private $_user;

	/**
	 * Attribute labels
	 * @return array
	 */
	public function attributeLabels() {
		return [
			'email'=>Yii::t('user', 'E-mail'),
			'password'=>Yii::t('user', 'Password'),
			'confirm'=>Yii::t('user', 'Confirm'),
			'firstName'=>Yii::t('user', 'First name'),
			'lastName'=>Yii::t('user', 'Last name'),
			'verifyCode'=>Yii::t('user', 'Verify code'),
			'mailing'=>Yii::t('user', 'Notify about promotions, discounts, news'),
		];
	}

	/**
	 * Validation rules
	 * @return array
	 */
	public function rules() {
		return [
			[['email', 'password', 'verifyCode'], 'required'],
			['email', 'email'],
			['password', 'string', 'min'=>4],
			['confirm', 'compare', 'compareAttribute'=>'password'],
			[['firstName', 'lastName'], 'string', 'max'=>50],
			['verifyCode', 'captcha'],
			['mailing', 'boolean'],
			['email', function($attribute, $params) {
				if (User::find()->where(['email'=>$this->email])->count() > 0) {
					$this->addError($attribute, Yii::t('user', 'The entered e-mail is already in use.'));
				}
			}],
		];
	}

	/**
	 * Registration
	 * @return bool
	 */
	public function register() {
		if ($this->validate()) {
			$user = $this->_user = new User;
			$user->setAttributes([
				'active'=>true,
				'email'=>$this->email,
				'firstName'=>$this->firstName,
				'lastName'=>$this->lastName,
				'mailing'=>$this->mailing,
			], false);
			$user->setPassword($this->password);
			if (!$user->save(false)) return false;

			$auth = Yii::$app->authManager;
			$author = $auth->getRole('author');
			if ($author !== null) $auth->assign($author, $user->id);

			return $user->login();
		} else {
			return false;
		}
	}

	/**
	 * Email sending
	 * @return boolean
	 */
	public function sendEmail() {
		$user = $this->_user;

		if ($user !== null) return $user->sendConfirmEmail();

		return false;
	}

}
