<?php

namespace cms\user\common\components\auth\map;

use yii\helpers\ArrayHelper;

class AuthMapVKontakte extends BaseAuthMap
{

	public function getMap()
	{
		return [
			'id' => 'user_id',
			'firstName' => 'first_name',
			'lastName' => 'last_name',
			'pic' => 'photo_100',
		];
	}

}
