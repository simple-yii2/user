<?php

namespace cms\user\auth\map;

abstract class Base
{

	/**
	 * Get normalization map for auth client
	 * @return array
	 */
	public abstract function getMap(); 

}
