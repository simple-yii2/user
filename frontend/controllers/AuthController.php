<?php

namespace cms\user\frontend\controllers;

use yii\web\Controller;

use cms\user\common\components\AuthHandler;

class AuthController extends Controller
{

	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'index' => [
				'class' => 'yii\authclient\AuthAction',
				'successCallback' => [$this, 'onAuthSuccess'],
			],
		];
	}

	public function onAuthSuccess($client)
	{
		(new AuthHandler($client))->handle();
	}

}
