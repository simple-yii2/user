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
				$client->apiBaseUrl = 'http://graph.facebook.com';
				$data = $client->api("/v2.8/{$attributes['id']}/picture?redirect=0&width=100&height=100", 'GET');

				return ArrayHelper::getValue($data, ['data', 'url']);
			},
		];
	}

}
