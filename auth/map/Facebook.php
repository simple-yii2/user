<?php

namespace cms\user\auth\map;

use yii\helpers\ArrayHelper;

class Facebook extends Base
{

	public function getMap(\yii\authclient\ClientInterface $client)
	{
		return [
			'id' => 'id',
			'email' => 'email',
			'firstName' => 'first_name',
			'lastName' => 'last_name',
			'pic' => function($attributes) use ($client) {
				$client->apiBaseUrl = 'http://graph.facebook.com/v2.8';
				$data = $client->api("{$attributes['id']}/picture", 'GET', [
					'redirect' => 0,
					'width' => 100,
					'height' => 100,
				]);

				return ArrayHelper::getValue($data, ['data', 'url']);
			},
		];
	}

}
