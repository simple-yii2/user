<?php

namespace app\modules\user\frontend\models;

use Yii;
use yii\base\Model;

use app\modules\user\common\models\User;

/**
 * Password change form
 */
class PasswordChangeForm extends Model {

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
	 * @var app\modules\user\common\models\User User object
	 */
	private $_user;

	/**
	 * Attribute labels
	 * @return array
	 */
	public function attributeLabels() {
		return [
			'oldPassword' => Yii::t('user', 'Current password'),
			'password' => Yii::t('user', 'New password'),
			'confirm' => Yii::t('user', 'Confirm'),
		];
	}

	/**
	 * Validation rules
	 * @return array
	 */
	public function rules() {
		return [
			[['oldPassword', 'password'], 'required'],
			['oldPassword', function($attribute) {
				if (!$this->hasErrors()) {
					$user = $this->getUser();

					if (!$user->validatePassword($this->$attribute)) {
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
	 * User getter
	 * @return yii\web\IdentityInterface
	 */
	public function getUser() {
		if ($this->_user !== null) return $this->_user;

		return $this->_user = Yii::$app->user->identity;
	}

	/**
	 * Password change function
	 * @return boolean
	 */
	public function changePassword() {
		$user = $this->getUser();
		$user->setPassword($this->password);

		return $user->save();
	}

}
