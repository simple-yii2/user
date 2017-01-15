<?php

namespace cms\user\common\models;

use Yii;
use yii\base\Model;

use cms\user\common\models\User;

/**
 * Settings form
 */
class SettingsForm extends Model
{

	/**
	 * @var string User e-mail
	 */
	public $email;

	/**
	 * @var string First name
	 */
	public $firstName;

	/**
	 * @var string Last name
	 */
	public $lastName;

	/**
	 * @var boolean Agree for mailing
	 */
	public $mailing;

	/**
	 * @var User
	 */
	private $_object;

	/**
	 * @inheritdoc
	 * @param User $object 
	 */
	public function __construct(User $object, $config = [])
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
			'firstName' => Yii::t('user', 'First name'),
			'lastName' => Yii::t('user', 'Last name'),
			'mailing' => Yii::t('user', 'Notify about promotions, discounts, news'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['firstName', 'lastName'], 'string', 'max' => 50],
			['mailing', 'boolean'],
		];
	}

	/**
	 * Confirmed getter
	 * @return boolean
	 */
	public function getConfirmed()
	{
		return $this->_object->confirmed;
	}

	/**
	 * Save
	 * @return bool
	 */
	public function save()
	{
		if (!$this->validate())
			return false;

		$object = $this->_object;

		$object->firstName = $this->firstName;
		$object->lastName = $this->lastName;
		$object->mailing = $this->mailing == 1;

		if (!$object->save(false))
			return false;

		return $object->login();
	}

}
