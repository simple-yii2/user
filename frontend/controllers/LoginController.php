<?php

namespace cms\user\frontend\controllers;

use yii\web\Controller;

/**
 * Login controller
 */
class LoginController extends Controller
{

	public function actions() {
		return [
			'index' => 'cms\user\common\actions\Login',
		];
	}

}
