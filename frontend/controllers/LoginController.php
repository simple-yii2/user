<?php

namespace cms\users\frontend\controllers;

use yii\web\Controller;

/**
 * Login controller
 */
class LoginController extends Controller
{

	public function actions() {
		return [
			'index' => 'cms\users\common\actions\Login',
		];
	}

}
