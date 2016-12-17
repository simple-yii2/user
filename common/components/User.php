<?php

namespace cms\user\common\components;

class User extends \yii\web\User {

	private $_isAdmin;

	private $_username;

	public function getIsAdmin() {
		if ($this->_isAdmin !== null) return $this->_isAdmin;
		return $this->_isAdmin = $this->getIsGuest() ? null : (boolean) $this->getIdentity()->admin;
	}

	public function can($permissionName, $params = [], $allowCaching = true) {
		return $this->getIsAdmin() ? true : parent::can($permissionName, $params, $allowCaching);
	}

	public function getUsername() {
		if ($this->_username !== null) return $this->_username;
		return $this->_username = $this->getIsGuest() ? Yii::t('user', 'Guest') : $this->getIdentity()->getUsername();
	}

}
