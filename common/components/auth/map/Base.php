<?php

namespace cms\user\common\components\auth\map;

abstract class Base
{

	/**
	 * Get normalization map for auth client
	 * @return array
	 */
	public abstract function getMap(); 

}
