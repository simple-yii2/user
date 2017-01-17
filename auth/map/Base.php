<?php

namespace cms\user\auth\map;

abstract class Base
{

	/**
	 * Get normalization map for auth client
	 * @param yii\authclient\ClientInterface $client
	 * @return array
	 */
	public abstract function getMap(\yii\authclient\ClientInterface $client); 

}
