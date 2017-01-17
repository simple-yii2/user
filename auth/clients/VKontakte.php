<?php

namespace cms\user\auth\clients;

class VKontakte extends \yii\authclient\clients\VKontakte
{

	/**
	 * @inheritdoc
	 */
	public $attributeNames = ['user_id', 'first_name', 'last_name', 'photo_100'];

	/**
	 * @inheritdoc
	 */
	protected function defaultNormalizeUserAttributeMap()
	{
		return [
			'id' => 'user_id',
			'firstName' => 'first_name',
			'lastName' => 'last_name',
			'pic' => 'photo_100',
		];
	}

}
