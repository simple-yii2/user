<?php

namespace cms\user\frontend\controllers;

use yii\web\Controller;

use cms\user\auth\Handler;

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
		(new Handler($client))->handle();
	}

}
