<?php

namespace cms\users\common\actions;

use Yii;
use yii\base\Action;

/**
 * Logout action
 */
class Logout extends Action {

	public function run() {
		if (Yii::$app->user->isGuest) return $this->controller->goHome();

		Yii::$app->user->logout();
		return $this->controller->goBack();
	}

}
