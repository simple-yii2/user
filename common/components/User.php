<?php

namespace cms\user\common\components;

class User extends \yii\web\User {

	/**
	 * @var boolean
	 */
	private $_isAdmin;

	/**
	 * @var string
	 */
	private $_username;

	/**
	 * Checking that user is administrator
	 * @return boolean
	 */
	public function getIsAdmin() {
		if ($this->_isAdmin !== null)
			return $this->_isAdmin;

		if ($this->getIsGuest())
			return false;

		return (boolean) $this->getIdentity()->admin;
	}

	/**
	 * @inheritdoc
	 */
	public function can($permissionName, $params = [], $allowCaching = true) {
		if ($this->getIsAdmin())
			return true;

		return parent::can($permissionName, $params, $allowCaching);
	}

	/**
	 * Return user name
	 * @return string
	 */
	public function getUsername() {
		if ($this->_username !== null)
			return $this->_username;

		if ($this->getIsGuest())
			return Yii::t('user', 'Guest');

		return $this->getIdentity()->getUsername();
	}

}
