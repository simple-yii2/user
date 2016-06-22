<?php

namespace user\backend\controllers;

use yii\web\Controller;

/**
 * Login controller
 */
class LoginController extends Controller {

	public function actions() {
		return [
			'index' => 'user\common\actions\Login',
		];
	}

}
