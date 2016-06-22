<?php

namespace app\modules\user\frontend\controllers;

use yii\web\Controller;

/**
 * Logout controller
 */
class LogoutController extends Controller {

	public function actions() {
		return [
			'index' => 'app\modules\user\common\actions\Logout',
		];
	}

}
