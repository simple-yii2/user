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
	 * @var string User picture
	 */
	public $image;
	public $thumb;

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

		$this->image = $this->thumb = $object->pic;
		$this->email = $object->email;
		$this->firstName = $object->firstName;
		$this->lastName = $object->lastName;
		$this->mailing = $object->mailing;

		Yii::$app->storage->cacheObject($object);

		parent::__construct($config);
	}

	/**
	 * Object getter
	 * @return User
	 */
	public function getObject()
	{
		return $this->_object;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'image' => Yii::t('user', 'Photo'),
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
			[['image', 'thumb'], 'string', 'max' => 200],
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

		$object->pic = empty($this->thumb) ? null : $this->thumb;
		$object->firstName = $this->firstName;
		$object->lastName = $this->lastName;
		$object->mailing = $this->mailing == 1;

		Yii::$app->storage->storeObject($object);

		if (!$object->save(false))
			return false;

		return $object->login();
	}

}
