<?php

namespace cms\user\auth\clients;

use yii\authclient\OAuth2;

class Odnoklassniki extends OAuth2
{

	/**
	 * @inheritdoc
	 */
	public $authUrl = 'https://connect.ok.ru/oauth/authorize';

	/**
	 * @inheritdoc
	 */
	public $tokenUrl = 'https://api.ok.ru/oauth/token.do';

	/**
	 * @inheritdoc
	 */
	public $apiBaseUrl = 'https://api.ok.ru/api';

	/**
	 * @inheritdoc
	 */
	public $attributeNames = ['email', 'first_name', 'last_name', 'pic128x128'];

	/**
	 * @var string Application public key
	 */
	public $clientPublic;

	/**
	 * @inheritdoc
	 */
	protected function initUserAttributes()
	{
		return $this->api('users/getCurrentUser', 'GET', [
			'fields' => implode(',', $this->attributeNames),
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function applyAccessTokenToRequest($request, $accessToken)
	{
		parent::applyAccessTokenToRequest($request, $accessToken);
		$data = $request->getData();

		$data['application_key'] = $this->clientPublic;
		$data['__online'] = 0;

		$session_secret_key = md5($accessToken->getToken() . $this->clientSecret);

		$a = $data;
		unset($a['session_key'], $a['access_token']);
		ksort($a);
		$params = implode('', array_map(function($v, $k) {return "$k=$v";}, $a, array_keys($a)));
		$data['sig'] = strtolower(md5($params . $session_secret_key));

		$request->setData($data);
	}

	/**
	 * @inheritdoc
	 */
	protected function defaultName()
	{
		return 'odnoklassniki';
	}

	/**
	 * @inheritdoc
	 */
	protected function defaultTitle()
	{
		return 'Odnoklassniki';
	}

	/**
	 * @inheritdoc
	 */
	protected function defaultNormalizeUserAttributeMap()
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
