<?php

namespace user\common\actions;

use Yii;
use yii\base\Action;

use user\common\models\LoginForm;

/**
 * User login action
 */
class Login extends Action {

	public function run() {
		if (!Yii::$app->user->isGuest) return $this->controller->goHome();

		$model = new LoginForm;

		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			Yii::$app->session->setFlash('lastModified', time());
			return $this->controller->goBack();
		};

		return $this->controller->render('index', [
			'model' => $model,
		]);
	}

}
