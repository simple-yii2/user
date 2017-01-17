<?php

namespace cms\user\auth\clients;

use yii\helpers\ArrayHelper;

class Google extends \yii\authclient\clients\Google
{

	/**
	 * @inheritdoc
	 */
	public $attributeNames = ['id', 'emails', 'name', 'image'];

	/**
	 * @inheritdoc
	 */
	protected function defaultNormalizeUserAttributeMap()
	{
		return [
			'id' => 'id',
			'email' => ['emails', 0, 'value'],
			'firstName' => ['name', 'givenName'],
			'lastName' => ['name', 'familyName'],
			'pic' => function($attributes) {
				$url = ArrayHelper::getValue($attributes, ['image', 'url']);

				if ($url === null)
					return null;

				$info = parse_url($url);
				if ($info === false)
					return null;

				return "{$info['scheme']}://{$info['host']}{$info['path']}?sz=100";
			},
		];
	}

}
