<?php

namespace cms\user\auth\map;

use yii\helpers\ArrayHelper;

class Odnoklassniki extends Base
{

	public function getMap(\yii\authclient\ClientInterface $client)
	{
		return [
			'id' => 'uid',
			'email' => 'email',
			'firstName' => 'first_name',
			'lastName' => 'last_name',
			'pic' => 'pic128x128',
		];
	}

}
