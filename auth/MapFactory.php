<?php

namespace cms\user\auth;

use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;

class MapFactory
{

	private static $_classes = [
		'google' => 'cms\user\auth\map\Google',
		'vkontakte' => 'cms\user\auth\map\VKontakte',
		'facebook' => 'cms\user\auth\map\Facebook',
		'odnoklassniki' => 'cms\user\auth\map\Odnoklassniki',
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
