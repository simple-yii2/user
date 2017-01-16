<?php

namespace cms\user\common\components\auth;

use yii\authclient\ClientInterface;

/**
 * Auth map helper
 */
class AuthMapHelper
{

	/**
	 * Getting user attributes from client using custom maps
	 * @param ClientInterface $client 
	 * @return array
	 */
	public static function getUserAttributes(ClientInterface $client)
	{
		$authMap = AuthMapFactory::createObject($client);
		$map = $authMap->getMap();

		$client->setNormalizeUserAttributeMap($map);

		return array_intersect_key($client->getUserAttributes(), $map);
	}

}
