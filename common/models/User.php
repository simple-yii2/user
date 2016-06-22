<?php

namespace user\common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User acrive record
 */
class User extends ActiveRecord implements IdentityInterface {

	/**
	 * Table name
	 * @return string
	 */
	public static function tableName() {
		return 'User';
	}

	/**
	 * Attribute labels
	 * @return array
	 */
	public function attributeLabels() {
		return [
			'email' => Yii::t('user', 'E-mail'),
			'firstName' => Yii::t('user', 'First name'),
			'lastName' => Yii::t('user', 'Last name'),
			'mailing' => Yii::t('user', 'Notify about promotions, discounts, news'),
		];
	}

	/**
	 * Find user by e-mail
	 * @param sring $email 
	 * @return User
	 */
	public static function findByEmail($email) {
		return static::findOne(['email' => $email]);
	}

	/**
	 * Set user password
	 * @param string $password 
	 * @return void
	 */
	public function setPassword($password) {
		$this->passwordHash = Yii::$app->security->generatePasswordHash($password);
	}

	/**
	 * Password validation
	 * @param string $password 
	 * @return boolean
	 */
	public function validatePassword($password) {
		return Yii::$app->security->validatePassword($password, $this->passwordHash);
	}

	/**
	 * Login
	 * @param integer|null $duration 
	 * @return boolean
	 */
	public function login($duration = null) {
		if ($duration === null) $duration = 3600*24*30;

		$this->loginDate = gmdate('Y-m-d H:i:s');
		$this->loginIP = Yii::$app->request->userIP;

		return $this->save() && Yii::$app->user->login($this, $duration);
	}

	/**
	 * Username getter
	 * @return string
	 */
	public function getUsername() {
		$name = trim($this->firstName.' '.$this->lastName);
		return empty($name) ? $this->email : $name;
	}

	/**
	 * Send confirm e-mail
	 * @return boolean
	 */
	public function sendConfirmEmail() {
		if (empty($this->confirmToken)) {
			$this->confirmToken = Yii::$app->security->generateRandomString().'_'.time();
			if (!$this->save()) return false;
		}

		return Yii::$app->mailer->compose('@app/modules/user/mail/confirm', ['user' => $this])
			->setTo($this->email)
			->setFrom(Yii::$app->mailer->transport->getUsername())
			->setSubject(Yii::t('user', 'E-mail confirmation'))
			->send();
	}

	/**
	 * Find user by e-mail confirm token
	 * @param string $token 
	 * @return User
	 */
	public static function findByConfirmToken($token) {
		return static::findOne(['confirmToken' => $token]);
	}

	/**
	 * Removes e-mail confirm token
	 * @return void
	 */
	public function removeConfirmToken() {
		$this->confirmToken = null;
	}

	/**
	 * Check password reset token
	 * @param string $token 
	 * @return boolean
	 */
	public static function isPasswordResetTokenValid($token) {
		if (empty($token)) {
			return false;
		}
		$expire = 24*3600;
		$parts = explode('_', $token);
		$timestamp = (int) end($parts);
		return $timestamp + $expire >= time();
	}

	/**
	 * Send reset password e-mail
	 * @return boolean
	 */
	public function sendResetPasswordEmail() {
		if (!static::isPasswordResetTokenValid($this->passwordResetToken)) {
			$this->passwordResetToken = Yii::$app->security->generateRandomString().'_'.time();
			if (!$this->save()) return false;
		}

		return Yii::$app->mailer->compose('@app/modules/user/mail/reset', ['user' => $this])
			->setTo($this->email)
			->setFrom(Yii::$app->mailer->transport->getUsername())
			->setSubject(Yii::t('user', 'Password reset'))
			->send();
	}

	/**
	 * Find user for password reset
	 * @param string $token 
	 * @return User
	 */
	public static function findByPasswordResetToken($token) {
		if (!static::isPasswordResetTokenValid($token)) {
			return null;
		}

		return static::findOne(['passwordResetToken' => $token]);
	}

	/**
	 * Removs password reset token
	 * @return type
	 */
	public function removePasswordResetToken() {
		$this->passwordResetToken = null;
	}


	//IdentityInterface
	public static function findIdentity($id) {
		return static::findOne(['id' => $id]);
	}

	public static function findIdentityByAccessToken($token, $type = null) {
		throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
	}

	public function getId() {
		return $this->getPrimaryKey();
	}

	public function getAuthKey() {
		return $this->authKey;
	}

	public function validateAuthKey($authKey) {
		return $this->getAuthKey() === $authKey;
	}

}
