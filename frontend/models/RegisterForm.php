<?php

namespace cms\user\frontend\models;

use Yii;
use yii\base\Model;

use cms\user\common\models\User;

/**
 * Register form
 */
class RegisterForm extends Model
{

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
	public $mailing;

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

		$this->email = $object->email;
		$this->firstName = $object->firstName;
		$this->lastName = $object->lastName;
		$this->mailing = $object->mailing;

		parent::__construct($config);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'email' => Yii::t('user', 'E-mail'),
			'password' => Yii::t('user', 'Password'),
			'confirm' => Yii::t('user', 'Confirm'),
			'firstName' => Yii::t('user', 'First name'),
			'lastName' => Yii::t('user', 'Last name'),
			'verifyCode' => Yii::t('user', 'Verify code'),
			'mailing' => Yii::t('user', 'Notify about promotions, discounts, news'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['email', 'password', 'confirm', 'verifyCode'], 'required'],
			['email', 'email'],
			['password', 'string', 'min' => 4],
			['confirm', 'compare', 'compareAttribute' => 'password'],
			[['firstName', 'lastName'], 'string', 'max' => 50],
			['verifyCode', 'captcha'],
			['mailing', 'boolean'],
			['email', function($attribute, $params) {
				if (User::find()->where(['email' => $this->email])->count() > 0) {
					$this->addError($attribute, Yii::t('user', 'The entered e-mail is already in use.'));
				}
			}],
		];
	}

	/**
	 * Registration
	 * @return bool
	 */
	public function register()
	{
		if (!$this->validate())
			return false;

		$object = $this->_object;

		$object->email = $this->email;
		$object->firstName = $this->firstName;
		$object->lastName = $this->lastName;
		$object->mailing = $this->mailing == 1;

		$object->setPassword($this->password);

		if (!$object->save(false))
			return false;

		$auth = Yii::$app->authManager;
		$author = $auth->getRole('author');
		if ($author !== null)
			$auth->assign($author, $object->id);

		return $object->login();
	}

	/**
	 * Email sending
	 * @return boolean
	 */
	public function sendEmail()
	{
		$object = $this->_object;

		if ($object !== null)
			return $object->sendConfirmEmail();

		return false;
	}

}
