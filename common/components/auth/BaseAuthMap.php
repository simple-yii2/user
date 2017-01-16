<?php

namespace cms\user\common\components\auth;

abstract class BaseAuthMap
{

	/**
	 * Get normalization map for auth client
	 * @return array
	 */
	public abstract function getMap(); 

}
