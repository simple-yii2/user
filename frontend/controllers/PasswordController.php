<?php

namespace cms\user\frontend\controllers;

use Yii;
use yii\web\Controller;

use cms\user\frontend\models\PasswordChangeForm;

/**
 * Password change controller
 */
class PasswordController extends Controller {

	/**
	 * Password change
	 * @return void
	 */
	public function actionIndex() {
		//check login
		if (Yii::$app->user->isGuest) return Yii::$app->user->loginRequired();

		//model
		$model = new PasswordChangeForm;

		//read user data
		if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->changePassword()) {
			Yii::$app->getSession()->setFlash('success', Yii::t('user', 'The new password has been set.'));

			return $this->redirect(['settings/index']);
		}

		return $this->render('index', [
			'model' => $model,
		]);
	}

}
