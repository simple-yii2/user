<?php

namespace user\common\components;

use yii\rbac\Rule;

/**
 * Author rule
 */
class AuthorRule extends Rule {

	/**
	 * @var string Name of author rule
	 */
	public $name = 'author';

	/**
	 * Checking
	 * @param type $user 
	 * @param type $item 
	 * @param type $params 
	 * @return type
	 */
	public function execute($user, $item, $params) {
		return isset($params[0]) && ($params[0] instanceof \yii\db\BaseActiveRecord) && $params[0]->hasAttribute('user_id') && $params->user_id == $user;
	}

}
