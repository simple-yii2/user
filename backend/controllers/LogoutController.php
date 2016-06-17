<?php

namespace user\backend\controllers;

use yii\web\Controller;

/**
 * Logout controller
 */
class LogoutController extends Controller {

	public function actions() {
		return [
			'index'=>'user\common\actions\Logout',
		];
	}

}
