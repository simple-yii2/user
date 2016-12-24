<?php

namespace cms\users\backend\controllers;

use yii\web\Controller;

/**
 * Logout controller
 */
class LogoutController extends Controller
{

	public function actions()
	{
		return [
			'index' => 'cms\users\common\actions\Logout',
		];
	}

}
