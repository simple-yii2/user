<?php

namespace app\modules\user\frontend\controllers;

use yii\web\Controller;

/**
 * Login controller
 */
class LoginController extends Controller {

	public function actions() {
		return [
			'index' => 'app\modules\user\common\actions\Login',
		];
	}

}
