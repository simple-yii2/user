<?php

namespace cms\user\common\components\auth;

use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;

class AuthMapFactory
{

	private static $_classes = [
		'google' => 'cms\user\common\components\auth\map\AuthMapGoogle',
		'vkontakte' => 'cms\user\common\components\auth\map\AuthMapVKontakte',
	];

	/**
	 * Map object factory
	 * @param ClientInterface $client 
	 * @return BaseAuthMap
	 */
	public static function createObject(ClientInterface $client)
	{
		$class = ArrayHelper::getValue(self::$_classes, $client->getId());

		if ($class === null)
			throw new \Exception('Auth client "' . $client->getId() . '" is not supported.');

		return new $class;
	}

}
